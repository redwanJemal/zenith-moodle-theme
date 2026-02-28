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
 * Zenith login page layout.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$bodyattributes = $OUTPUT->body_attributes();

// Get login layout setting.
$loginlayout = get_config('theme_zenith', 'loginlayout') ?: 'center';

// Build background image URL.
$loginbgimage = '';
if (!empty($PAGE->theme->settings->loginbackgroundimage)) {
    $loginbgimage = $PAGE->theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
}

// Get login page logo.
$loginlogo = '';
if (!empty($PAGE->theme->settings->logo)) {
    $loginlogo = $PAGE->theme->setting_file_url('logo', 'logo');
}

// Get login heading text.
$loginheading = get_config('theme_zenith', 'loginheadingtext') ?: get_string('loginheading', 'theme_zenith');

// Get login description for hero panels.
$logindescription = '';
if (!empty($PAGE->theme->settings->logindescription)) {
    $logindescription = format_text($PAGE->theme->settings->logindescription, FORMAT_HTML);
}

$templatecontext = [
    'sitename' => format_string(
        $SITE->shortname,
        true,
        ['context' => context_course::instance(SITEID), 'escape' => false]
    ),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'loginlayout' => $loginlayout,
    'layoutcenter' => ($loginlayout === 'center'),
    'layoutleft' => ($loginlayout === 'left'),
    'layoutright' => ($loginlayout === 'right'),
    'loginbgimage' => $loginbgimage,
    'hasbgimage' => !empty($loginbgimage),
    'loginlogo' => $loginlogo,
    'haslogo' => !empty($loginlogo),
    'loginheading' => $loginheading,
    'logindescription' => $logindescription,
    'hasdescription' => !empty($logindescription),
];

echo $OUTPUT->render_from_template('theme_zenith/login', $templatecontext);
