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
 * Zenith theme lib functions.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content for the theme.
 *
 * @param theme_config $theme The theme config object.
 * @return string SCSS content.
 */
function theme_zenith_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    // Load the Zenith preset (Bootstrap variable overrides + design tokens).
    $presetfile = $CFG->dirroot . '/theme/zenith/scss/preset/default.scss';
    if (is_readable($presetfile)) {
        $scss .= file_get_contents($presetfile);
    }

    return $scss;
}

/**
 * Inject SCSS variables before compilation.
 *
 * @param theme_config $theme The theme config object.
 * @return string Pre-SCSS content.
 */
function theme_zenith_get_pre_scss($theme) {
    $scss = '';

    // Inject brand color from settings.
    $configurable = [
        'brandcolor' => ['primary'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (!empty($value)) {
            foreach ($targets as $target) {
                $scss .= '$' . $target . ': ' . $value . ";\n";
            }
        }
    }

    // Prepend pre-SCSS from settings.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Get extra SCSS appended after main SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string Extra SCSS content.
 */
function theme_zenith_get_extra_scss($theme) {
    $scss = '';

    // Append custom SCSS from settings.
    if (!empty($theme->settings->scss)) {
        $scss .= $theme->settings->scss;
    }

    return $scss;
}

/**
 * Get precompiled CSS fallback.
 *
 * @return string CSS content.
 */
function theme_zenith_get_precompiled_css() {
    global $CFG;

    $cssfile = $CFG->dirroot . '/theme/zenith/style/moodle.css';
    if (is_readable($cssfile)) {
        return file_get_contents($cssfile);
    }
    return '';
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course Course object.
 * @param stdClass $cm Course module object.
 * @param context $context Context object.
 * @param string $filearea File area.
 * @param array $args Extra arguments.
 * @param bool $forcedownload Whether to force download.
 * @param array $options Additional options.
 * @return bool False if file not found.
 */
function theme_zenith_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('zenith');

        // Serve theme setting files (logo, favicon, background images).
        $settingsfiles = ['logo', 'logomini', 'favicon', 'backgroundimage', 'loginbackgroundimage'];
        if (in_array($filearea, $settingsfiles)) {
            if (!array_key_exists('cacheability', $options)) {
                $options['cacheability'] = 'public';
            }
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        }
    }

    send_file_not_found();
}
