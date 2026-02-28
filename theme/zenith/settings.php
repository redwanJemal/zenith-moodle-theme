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

    // ========================================================================
    // Footer settings.
    // ========================================================================

    $settings->add(new admin_setting_heading(
        'theme_zenith/footerheading',
        get_string('footersettings', 'theme_zenith'),
        ''
    ));

    // Footer content (HTML).
    $name = 'theme_zenith/footercontent';
    $title = get_string('footercontent', 'theme_zenith');
    $description = get_string('footercontent_desc', 'theme_zenith');
    $setting = new admin_setting_confightmleditor($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Copyright text.
    $name = 'theme_zenith/footercopyright';
    $title = get_string('footercopyright', 'theme_zenith');
    $description = get_string('footercopyright_desc', 'theme_zenith');
    $default = '© {year} ' . format_string($SITE->fullname);
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Show Zenith branding.
    $name = 'theme_zenith/footershowbranding';
    $title = get_string('footershowbranding', 'theme_zenith');
    $description = get_string('footershowbranding_desc', 'theme_zenith');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // ========================================================================
    // Social media settings.
    // ========================================================================

    $settings->add(new admin_setting_heading(
        'theme_zenith/socialheading',
        get_string('socialmedia', 'theme_zenith'),
        ''
    ));

    $socialnetworks = ['facebook', 'twitter', 'linkedin', 'youtube', 'instagram'];
    foreach ($socialnetworks as $network) {
        $name = 'theme_zenith/social' . $network;
        $title = get_string('social' . $network, 'theme_zenith');
        $description = get_string('social' . $network . '_desc', 'theme_zenith');
        $setting = new admin_setting_configtext($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);
    }

    // ========================================================================
    // Login page settings.
    // ========================================================================

    $settings->add(new admin_setting_heading(
        'theme_zenith/loginheading_setting',
        get_string('loginsettings', 'theme_zenith'),
        ''
    ));

    // Login layout chooser.
    $name = 'theme_zenith/loginlayout';
    $title = get_string('loginlayout', 'theme_zenith');
    $description = get_string('loginlayout_desc', 'theme_zenith');
    $choices = [
        'center' => get_string('loginlayout_center', 'theme_zenith'),
        'left'   => get_string('loginlayout_left', 'theme_zenith'),
        'right'  => get_string('loginlayout_right', 'theme_zenith'),
    ];
    $setting = new admin_setting_configselect($name, $title, $description, 'center', $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Login background image.
    $name = 'theme_zenith/loginbackgroundimage';
    $title = get_string('loginbackgroundimage', 'theme_zenith');
    $description = get_string('loginbackgroundimage_desc', 'theme_zenith');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Login heading text.
    $name = 'theme_zenith/loginheadingtext';
    $title = get_string('loginheading', 'theme_zenith');
    $description = get_string('loginheading_desc', 'theme_zenith');
    $setting = new admin_setting_configtext($name, $title, $description, get_string('loginheading', 'theme_zenith'));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Login description (for hero panels).
    $name = 'theme_zenith/logindescription';
    $title = get_string('logindescription', 'theme_zenith');
    $description = get_string('logindescription_desc', 'theme_zenith');
    $setting = new admin_setting_confightmleditor($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // ========================================================================
    // Advanced settings.
    // ========================================================================

    $settings->add(new admin_setting_heading(
        'theme_zenith/advancedheading',
        get_string('advancedsettings', 'theme_zenith'),
        ''
    ));

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
