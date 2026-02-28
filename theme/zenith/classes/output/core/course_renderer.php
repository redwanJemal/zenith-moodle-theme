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

namespace theme_zenith\output\core;

use core_course_category;
use core_course_list_element;
use coursecat_helper;
use moodle_url;
use html_writer;
use stdClass;

defined('MOODLE_INTERNAL') || die;

/**
 * Zenith course renderer — card-based course listing.
 *
 * Overrides core_course_renderer to render .z-coursecard BEM markup
 * instead of .coursebox divs on category pages and frontpage.
 *
 * Auto-discovered by theme_overridden_renderer_factory via the class name
 * convention: theme_zenith\output\core\course_renderer.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

    /**
     * Render the list of courses as a card grid.
     *
     * @param coursecat_helper $chelper
     * @param array $courses
     * @param int|null $totalcount
     * @return string HTML
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;

        if ($totalcount === null) {
            $totalcount = count($courses);
        }
        if (!$totalcount) {
            return '';
        }

        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // Pagination.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        $pagingbar = '';
        $morelink = '';

        if ($totalcount > count($courses)) {
            if ($paginationurl) {
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                    $paginationurl->out(false, ['perpage' => $perpage]));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div',
                        html_writer::link($paginationurl->out(false, ['perpage' => 'all']),
                            get_string('showall', '', $totalcount)),
                        ['class' => 'paging paging-showall']);
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext',
                    new \lang_string('viewmore'));
                $morelink = html_writer::tag('div',
                    html_writer::link($viewmoreurl, $viewmoretext, ['class' => 'btn btn-secondary']),
                    ['class' => 'paging paging-morelink']);
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            $pagingbar = html_writer::tag('div',
                html_writer::link($paginationurl->out(false, ['perpage' => $CFG->coursesperpage]),
                    get_string('showperpage', '', $CFG->coursesperpage)),
                ['class' => 'paging paging-showperpage']);
        }

        // Render toolbar.
        $toolbar = $this->render_archive_toolbar($totalcount);

        // Render card grid.
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', ['class' => 'z-archive-wrapper',
            'data-region' => 'course-archive']);
        $content .= $toolbar;
        $content .= html_writer::start_tag('div', array_merge($attributes, [
            'class' => 'z-coursecards z-coursecards--archive',
            'data-region' => 'course-archive-grid',
        ]));

        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course);
        }

        $content .= html_writer::end_tag('div'); // .z-coursecards

        if (!empty($pagingbar)) {
            $content .= html_writer::tag('div', $pagingbar, ['class' => 'z-archive-pagination']);
        }
        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // .z-archive-wrapper

        // Load the coursearchive AMD module.
        $this->page->requires->js_call_amd('theme_zenith/coursearchive', 'init');

        return $content;
    }

    /**
     * Render a single course as a card.
     *
     * @param coursecat_helper $chelper
     * @param stdClass|core_course_list_element $course
     * @param string $additionalclasses
     * @return string HTML
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }

        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $courseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
        $imageurl = $this->get_course_image_url($course);
        $categoryname = $this->get_course_category_name_text($course);
        $contacts = $this->get_course_contacts_text($course);

        // Determine background style — image or gradient fallback.
        $hastrueimage = (strpos($imageurl, 'pluginfile.php') !== false);
        if ($hastrueimage) {
            $bgstyle = 'background-image: url("' . $imageurl . '");';
        } else {
            $bgstyle = 'background: ' . $this->get_course_gradient($course->id) . ';';
        }

        $content = html_writer::start_tag('div', [
            'class' => 'z-coursecard',
            'data-courseid' => $course->id,
        ]);

        // Image.
        $content .= html_writer::start_tag('a', [
            'href' => $courseurl,
            'tabindex' => '-1',
            'aria-hidden' => 'true',
        ]);
        $content .= html_writer::tag('div', '', [
            'class' => 'z-coursecard__image',
            'style' => $bgstyle,
        ]);
        $content .= html_writer::end_tag('a');

        // Body.
        $content .= html_writer::start_tag('div', ['class' => 'z-coursecard__body']);

        // Category badge.
        if ($categoryname) {
            $content .= html_writer::tag('span', $categoryname, [
                'class' => 'z-coursecard__category',
            ]);
        }

        // Title.
        $content .= html_writer::link($courseurl, format_string($course->fullname), [
            'class' => 'z-coursecard__title',
        ]);

        // Instructor.
        if ($contacts) {
            $content .= html_writer::tag('div', $contacts, [
                'class' => 'z-coursecard__instructor',
            ]);
        }

        // Hidden badge.
        if (!$course->visible) {
            $content .= html_writer::tag('span',
                get_string('hiddenfromstudents'),
                ['class' => 'z-coursecard__hidden-badge']);
        }

        $content .= html_writer::end_tag('div'); // .z-coursecard__body
        $content .= html_writer::end_tag('div'); // .z-coursecard

        return $content;
    }

    /**
     * Render frontpage "Available courses" as card grid.
     *
     * @return string HTML
     */
    public function frontpage_available_courses() {
        global $CFG;

        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)
            ->set_courses_display_options([
                'recursive' => true,
                'limit' => $CFG->frontpagecourselimit,
                'viewmoreurl' => new moodle_url('/course/index.php'),
                'viewmoretext' => new \lang_string('fulllistofcourses'),
            ]);

        $chelper->set_attributes(['class' => 'frontpage-course-list-all']);
        $courses = core_course_category::top()->get_courses(
            $chelper->get_courses_display_options()
        );
        $totalcount = core_course_category::top()->get_courses_count(
            $chelper->get_courses_display_options()
        );

        if (!$totalcount && !$this->page->user_is_editing()
            && has_capability('moodle/course:create', \context_system::instance())) {
            return $this->add_new_course_button();
        }

        return $this->coursecat_courses($chelper, $courses, $totalcount);
    }

    /**
     * Get course overview image URL.
     *
     * @param core_course_list_element $course
     * @return string URL
     */
    protected function get_course_image_url(core_course_list_element $course) {
        global $CFG;

        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                return moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename(),
                    false
                );
            }
        }

        // No image found — return empty (caller will use gradient fallback).
        return '';
    }

    /**
     * Get the gradient style for a course without an image.
     *
     * @param int $courseid
     * @return string CSS gradient
     */
    protected function get_course_gradient($courseid) {
        $gradients = [
            'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
            'linear-gradient(135deg, #fccb90 0%, #d57eeb 100%)',
            'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
        ];
        return $gradients[$courseid % count($gradients)];
    }

    /**
     * Get the course category name as plain text.
     *
     * @param core_course_list_element $course
     * @return string Category name or empty string
     */
    protected function get_course_category_name_text(core_course_list_element $course) {
        $cat = core_course_category::get($course->category, IGNORE_MISSING);
        if ($cat) {
            return $cat->get_formatted_name();
        }
        return '';
    }

    /**
     * Get formatted instructor names.
     *
     * @param core_course_list_element $course
     * @return string Instructor names or empty string
     */
    protected function get_course_contacts_text(core_course_list_element $course) {
        if (!$course->has_course_contacts()) {
            return '';
        }

        $names = [];
        foreach ($course->get_course_contacts() as $contact) {
            $names[] = $contact['username'];
        }

        return implode(', ', $names);
    }

    /**
     * Render the archive toolbar (search, filter, sort, view toggle).
     *
     * @param int $totalcount Total number of courses
     * @return string HTML
     */
    protected function render_archive_toolbar($totalcount) {
        // Get all categories for the filter dropdown.
        $categories = core_course_category::make_categories_list();
        $catitems = [];
        foreach ($categories as $catid => $catname) {
            $catitems[] = [
                'id' => $catid,
                'name' => $catname,
            ];
        }

        $context = [
            'totalcount' => $totalcount,
            'categories' => $catitems,
            'hascategories' => !empty($catitems),
            'counttext' => get_string('coursecountfmt', 'theme_zenith', $totalcount),
        ];

        return $this->render_from_template('theme_zenith/coursearchive_toolbar', $context);
    }
}
