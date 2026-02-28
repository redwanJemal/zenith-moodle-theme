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
 * External API for the Zenith dashboard widgets.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use theme_zenith\dashboard\stats_provider;

/**
 * Dashboard AJAX endpoints: get_stats, get_recent_courses, get_upcoming_deadlines.
 */
class dashboard_api extends external_api {

    // ========================================================================
    // get_stats
    // ========================================================================

    /**
     * Parameter definition for get_stats.
     * @return external_function_parameters
     */
    public static function get_stats_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Get dashboard statistics for the current user.
     *
     * @return array Stats data.
     */
    public static function get_stats(): array {
        global $USER;

        $context = \context_system::instance();
        self::validate_context($context);
        require_login();

        return stats_provider::get_stats((int) $USER->id);
    }

    /**
     * Return definition for get_stats.
     * @return external_single_structure
     */
    public static function get_stats_returns(): external_single_structure {
        return new external_single_structure([
            'enrolled' => new external_value(PARAM_INT, 'Number of enrolled courses'),
            'completed' => new external_value(PARAM_INT, 'Number of completed courses'),
            'inprogress' => new external_value(PARAM_INT, 'Number of in-progress courses'),
            'averageprogress' => new external_value(PARAM_INT, 'Average progress percentage'),
        ]);
    }

    // ========================================================================
    // get_recent_courses
    // ========================================================================

    /**
     * Parameter definition for get_recent_courses.
     * @return external_function_parameters
     */
    public static function get_recent_courses_parameters(): external_function_parameters {
        return new external_function_parameters([
            'limit' => new external_value(PARAM_INT, 'Max courses to return', VALUE_DEFAULT, 5),
        ]);
    }

    /**
     * Get recent courses with progress for the current user.
     *
     * @param int $limit Maximum number of courses.
     * @return array Recent courses data.
     */
    public static function get_recent_courses(int $limit = 5): array {
        global $USER;

        $params = self::validate_parameters(self::get_recent_courses_parameters(), [
            'limit' => $limit,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_login();

        $courses = stats_provider::get_recent_courses((int) $USER->id, $params['limit']);

        return ['courses' => $courses];
    }

    /**
     * Return definition for get_recent_courses.
     * @return external_single_structure
     */
    public static function get_recent_courses_returns(): external_single_structure {
        return new external_single_structure([
            'courses' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Course ID'),
                    'fullname' => new external_value(PARAM_RAW, 'Course full name'),
                    'shortname' => new external_value(PARAM_RAW, 'Course short name'),
                    'progress' => new external_value(PARAM_INT, 'Completion progress percentage'),
                    'courseimage' => new external_value(PARAM_URL, 'Course image URL', VALUE_OPTIONAL),
                    'viewurl' => new external_value(PARAM_URL, 'Course view URL'),
                ])
            ),
        ]);
    }

    // ========================================================================
    // get_upcoming_deadlines
    // ========================================================================

    /**
     * Parameter definition for get_upcoming_deadlines.
     * @return external_function_parameters
     */
    public static function get_upcoming_deadlines_parameters(): external_function_parameters {
        return new external_function_parameters([
            'days' => new external_value(PARAM_INT, 'Number of days ahead', VALUE_DEFAULT, 7),
        ]);
    }

    /**
     * Get upcoming deadlines for the current user.
     *
     * @param int $days Number of days ahead.
     * @return array Deadlines data.
     */
    public static function get_upcoming_deadlines(int $days = 7): array {
        global $USER;

        $params = self::validate_parameters(self::get_upcoming_deadlines_parameters(), [
            'days' => $days,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_login();

        $deadlines = stats_provider::get_upcoming_deadlines((int) $USER->id, $params['days']);

        return ['deadlines' => $deadlines];
    }

    /**
     * Return definition for get_upcoming_deadlines.
     * @return external_single_structure
     */
    public static function get_upcoming_deadlines_returns(): external_single_structure {
        return new external_single_structure([
            'deadlines' => new external_multiple_structure(
                new external_single_structure([
                    'name' => new external_value(PARAM_RAW, 'Activity name'),
                    'duedate' => new external_value(PARAM_INT, 'Due date timestamp'),
                    'duedateformatted' => new external_value(PARAM_RAW, 'Formatted due date'),
                    'coursename' => new external_value(PARAM_RAW, 'Course name'),
                    'courseid' => new external_value(PARAM_INT, 'Course ID'),
                    'activityurl' => new external_value(PARAM_URL, 'Activity URL'),
                    'overdue' => new external_value(PARAM_BOOL, 'Whether the deadline is overdue'),
                ])
            ),
        ]);
    }
}
