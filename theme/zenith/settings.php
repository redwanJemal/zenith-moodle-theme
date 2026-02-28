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
 * Zenith theme settings.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Brand color.
    $name = 'theme_zenith/brandcolor';
    $title = get_string('brandcolor', 'theme_zenith');
    $description = get_string('brandcolor_desc', 'theme_zenith');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#6366f1');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Logo upload.
    $name = 'theme_zenith/logo';
    $title = get_string('logo', 'theme_zenith');
    $description = get_string('logo_desc', 'theme_zenith');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Logo mini upload.
    $name = 'theme_zenith/logomini';
    $title = get_string('logomini', 'theme_zenith');
    $description = get_string('logomini_desc', 'theme_zenith');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logomini');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Raw SCSS before compilation.
    $name = 'theme_zenith/scsspre';
    $title = get_string('rawscsspre', 'theme_zenith');
    $description = get_string('rawscsspre_desc', 'theme_zenith');
    $setting = new admin_setting_scsscode($name, $title, $description, '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Raw SCSS after compilation.
    $name = 'theme_zenith/scss';
    $title = get_string('rawscss', 'theme_zenith');
    $description = get_string('rawscss_desc', 'theme_zenith');
    $setting = new admin_setting_scsscode($name, $title, $description, '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}
