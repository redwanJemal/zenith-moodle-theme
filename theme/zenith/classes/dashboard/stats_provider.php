<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Dashboard statistics provider.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith\dashboard;

defined('MOODLE_INTERNAL') || die();

use cache;

/**
 * Provides dashboard statistics: enrollment counts, course progress, deadlines.
 */
class stats_provider {

    /** @var int Cache TTL in seconds. */
    private const CACHE_TTL = 300;

    /**
     * Get aggregate stats for a user.
     *
     * @param int $userid User ID.
     * @return array {enrolled: int, completed: int, inprogress: int, averageprogress: int}
     */
    public static function get_stats(int $userid): array {
        $cache = cache::make('theme_zenith', 'dashboard_stats');
        $cachekey = 'stats_' . $userid;
        $cached = $cache->get($cachekey);
        if ($cached !== false) {
            return $cached;
        }

        $courses = enrol_get_users_courses($userid, true);
        $enrolled = count($courses);
        $completed = 0;
        $inprogress = 0;
        $totalprogress = 0;

        foreach ($courses as $course) {
            $progress = \core_completion\progress::get_course_progress_percentage($course, $userid);
            if ($progress !== null) {
                $totalprogress += $progress;
                if ($progress >= 100) {
                    $completed++;
                } else {
                    $inprogress++;
                }
            } else {
                $inprogress++;
            }
        }

        $result = [
            'enrolled' => $enrolled,
            'completed' => $completed,
            'inprogress' => $inprogress,
            'averageprogress' => $enrolled > 0 ? (int) round($totalprogress / $enrolled) : 0,
        ];

        $cache->set($cachekey, $result);
        return $result;
    }

    /**
     * Get recent courses with progress percentage.
     *
     * @param int $userid User ID.
     * @param int $limit Maximum number of courses.
     * @return array List of {id, fullname, shortname, progress, courseimage}.
     */
    public static function get_recent_courses(int $userid, int $limit = 5): array {
        global $CFG, $PAGE;
        require_once($CFG->dirroot . '/course/lib.php');

        $cache = cache::make('theme_zenith', 'dashboard_stats');
        $cachekey = 'recent_' . $userid . '_' . $limit;
        $cached = $cache->get($cachekey);
        if ($cached !== false) {
            return $cached;
        }

        $courses = course_get_recent_courses($userid, $limit);
        $result = [];

        foreach ($courses as $course) {
            $progress = \core_completion\progress::get_course_progress_percentage($course, $userid);
            $courseobj = new \core_course_list_element($course);
            $courseimage = '';

            // Get course image URL.
            foreach ($courseobj->get_course_overviewfiles() as $file) {
                if ($file->is_valid_image()) {
                    $courseimage = \moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        null,
                        $file->get_filepath(),
                        $file->get_filename()
                    )->out(false);
                    break;
                }
            }

            $result[] = [
                'id' => $course->id,
                'fullname' => format_string($course->fullname, true, [
                    'context' => \context_course::instance($course->id),
                ]),
                'shortname' => format_string($course->shortname, true, [
                    'context' => \context_course::instance($course->id),
                ]),
                'progress' => $progress !== null ? (int) round($progress) : 0,
                'courseimage' => $courseimage,
                'viewurl' => (new \moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
            ];
        }

        $cache->set($cachekey, $result);
        return $result;
    }

    /**
     * Get upcoming deadlines within a number of days.
     *
     * @param int $userid User ID.
     * @param int $days Number of days ahead.
     * @return array List of {name, duedate, duedateformatted, coursename, courseid, activityurl, overdue}.
     */
    public static function get_upcoming_deadlines(int $userid, int $days = 7): array {
        global $DB;

        $cache = cache::make('theme_zenith', 'dashboard_stats');
        $cachekey = 'deadlines_' . $userid . '_' . $days;
        $cached = $cache->get($cachekey);
        if ($cached !== false) {
            return $cached;
        }

        $now = time();
        // Look back 1 day for overdue items, forward by $days.
        $start = $now - DAYSECS;
        $end = $now + ($days * DAYSECS);

        $courses = enrol_get_users_courses($userid, true);
        if (empty($courses)) {
            $cache->set($cachekey, []);
            return [];
        }

        $courseids = array_keys($courses);
        list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $params['start'] = $start;
        $params['end'] = $end;
        $params['userid'] = $userid;

        // Get assign deadlines.
        $sql = "SELECT a.id, a.name, a.duedate, a.course, cm.id AS cmid
                  FROM {assign} a
                  JOIN {course_modules} cm ON cm.instance = a.id AND cm.module = (
                      SELECT id FROM {modules} WHERE name = 'assign'
                  )
                 WHERE a.course {$insql}
                   AND a.duedate BETWEEN :start AND :end
                   AND cm.visible = 1
              ORDER BY a.duedate ASC";

        $assigns = $DB->get_records_sql($sql, $params, 0, 10);
        $result = [];

        foreach ($assigns as $assign) {
            // Check if already submitted.
            $submitted = $DB->record_exists('assign_submission', [
                'assignment' => $assign->id,
                'userid' => $userid,
                'status' => 'submitted',
            ]);
            if ($submitted) {
                continue;
            }

            $result[] = [
                'name' => format_string($assign->name, true, [
                    'context' => \context_course::instance($assign->course),
                ]),
                'duedate' => $assign->duedate,
                'duedateformatted' => userdate($assign->duedate, get_string('strftimedatetimeshort', 'langconfig')),
                'coursename' => format_string($courses[$assign->course]->fullname, true, [
                    'context' => \context_course::instance($assign->course),
                ]),
                'courseid' => $assign->course,
                'activityurl' => (new \moodle_url('/mod/assign/view.php', ['id' => $assign->cmid]))->out(false),
                'overdue' => $assign->duedate < $now,
            ];
        }

        // Get quiz deadlines.
        $sql = "SELECT q.id, q.name, q.timeclose AS duedate, q.course, cm.id AS cmid
                  FROM {quiz} q
                  JOIN {course_modules} cm ON cm.instance = q.id AND cm.module = (
                      SELECT id FROM {modules} WHERE name = 'quiz'
                  )
                 WHERE q.course {$insql}
                   AND q.timeclose BETWEEN :start AND :end
                   AND cm.visible = 1
              ORDER BY q.timeclose ASC";

        $quizzes = $DB->get_records_sql($sql, $params, 0, 10);

        foreach ($quizzes as $quiz) {
            $result[] = [
                'name' => format_string($quiz->name, true, [
                    'context' => \context_course::instance($quiz->course),
                ]),
                'duedate' => $quiz->duedate,
                'duedateformatted' => userdate($quiz->duedate, get_string('strftimedatetimeshort', 'langconfig')),
                'coursename' => format_string($courses[$quiz->course]->fullname, true, [
                    'context' => \context_course::instance($quiz->course),
                ]),
                'courseid' => $quiz->course,
                'activityurl' => (new \moodle_url('/mod/quiz/view.php', ['id' => $quiz->cmid]))->out(false),
                'overdue' => $quiz->duedate < $now,
            ];
        }

        // Sort by duedate.
        usort($result, function($a, $b) {
            return $a['duedate'] <=> $b['duedate'];
        });

        // Limit to 10 items total.
        $result = array_slice($result, 0, 10);

        $cache->set($cachekey, $result);
        return $result;
    }
}
