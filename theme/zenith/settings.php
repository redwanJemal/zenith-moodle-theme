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
 * Zenith theme settings — tabbed admin UI.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Use Boost's tabbed settings page class.
    $settings = new theme_boost_admin_settingspage_tabs(
        'themesettingzenith',
        get_string('configtitle', 'theme_zenith')
    );

    // =========================================================================
    // TAB 1: General settings.
    // =========================================================================
    $page = new admin_settingpage(
        'theme_zenith_general',
        get_string('generalsettings', 'theme_zenith')
    );

    // Brand colour.
    $setting = new admin_setting_configcolourpicker(
        'theme_zenith/brandcolor',
        get_string('brandcolor', 'theme_zenith'),
        get_string('brandcolor_desc', 'theme_zenith'),
        '#6366f1'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Secondary colour.
    $setting = new admin_setting_configcolourpicker(
        'theme_zenith/secondarycolor',
        get_string('secondarycolor', 'theme_zenith'),
        get_string('secondarycolor_desc', 'theme_zenith'),
        '#0ea5e9'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Logo upload.
    $setting = new admin_setting_configstoredfile(
        'theme_zenith/logo',
        get_string('logo', 'theme_zenith'),
        get_string('logo_desc', 'theme_zenith'),
        'logo'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Logo mini upload.
    $setting = new admin_setting_configstoredfile(
        'theme_zenith/logomini',
        get_string('logomini', 'theme_zenith'),
        get_string('logomini_desc', 'theme_zenith'),
        'logomini'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Favicon upload.
    $setting = new admin_setting_configstoredfile(
        'theme_zenith/favicon',
        get_string('favicon', 'theme_zenith'),
        get_string('favicon_desc', 'theme_zenith'),
        'favicon',
        0,
        ['accepted_types' => ['.ico', '.png', '.svg']]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Re-launch setup wizard.
    $page->add(new admin_setting_description(
        'theme_zenith/wizardrelaunch',
        get_string('wizard_relaunch', 'theme_zenith'),
        get_string('wizard_relaunch_desc', 'theme_zenith')
    ));

    // Google Analytics ID.
    $setting = new admin_setting_configtext(
        'theme_zenith/googleanalytics',
        get_string('googleanalytics', 'theme_zenith'),
        get_string('googleanalytics_desc', 'theme_zenith'),
        '',
        PARAM_ALPHANUMEXT
    );
    $page->add($setting);

    $settings->add($page);

    // =========================================================================
    // TAB 2: Homepage settings.
    // =========================================================================
    $page = new admin_settingpage(
        'theme_zenith_homepage',
        get_string('homepagesettings', 'theme_zenith')
    );

    // --- Hero section ---
    $page->add(new admin_setting_heading(
        'theme_zenith/heroheading',
        get_string('herosection', 'theme_zenith'),
        ''
    ));

    // Enable hero section.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/heroenabled',
        get_string('heroenabled', 'theme_zenith'),
        get_string('heroenabled_desc', 'theme_zenith'),
        1
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hero title.
    $setting = new admin_setting_configtext(
        'theme_zenith/herotitle',
        get_string('herotitle', 'theme_zenith'),
        get_string('herotitle_desc', 'theme_zenith'),
        get_string('herotitle_default', 'theme_zenith')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hero subtitle.
    $setting = new admin_setting_configtextarea(
        'theme_zenith/herosubtitle',
        get_string('herosubtitle', 'theme_zenith'),
        get_string('herosubtitle_desc', 'theme_zenith'),
        get_string('herosubtitle_default', 'theme_zenith')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hero background image.
    $setting = new admin_setting_configstoredfile(
        'theme_zenith/herobackgroundimage',
        get_string('herobackgroundimage', 'theme_zenith'),
        get_string('herobackgroundimage_desc', 'theme_zenith'),
        'herobackgroundimage'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hero CTA button text.
    $setting = new admin_setting_configtext(
        'theme_zenith/herobuttontext',
        get_string('herobuttontext', 'theme_zenith'),
        get_string('herobuttontext_desc', 'theme_zenith'),
        get_string('herobuttontext_default', 'theme_zenith')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Hero CTA button URL.
    $setting = new admin_setting_configtext(
        'theme_zenith/herobuttonurl',
        get_string('herobuttonurl', 'theme_zenith'),
        get_string('herobuttonurl_desc', 'theme_zenith'),
        '/course/index.php',
        PARAM_URL
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // --- About section ---
    $page->add(new admin_setting_heading(
        'theme_zenith/aboutheading',
        get_string('aboutsection', 'theme_zenith'),
        ''
    ));

    // Enable about section.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/aboutenabled',
        get_string('aboutenabled', 'theme_zenith'),
        get_string('aboutenabled_desc', 'theme_zenith'),
        0
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // About section content.
    $setting = new admin_setting_confightmleditor(
        'theme_zenith/aboutcontent',
        get_string('aboutcontent', 'theme_zenith'),
        get_string('aboutcontent_desc', 'theme_zenith'),
        ''
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // --- Featured courses ---
    $page->add(new admin_setting_heading(
        'theme_zenith/featuredcoursesheading',
        get_string('featuredcourses', 'theme_zenith'),
        ''
    ));

    // Enable featured courses.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/featuredcoursesenabled',
        get_string('featuredcoursesenabled', 'theme_zenith'),
        get_string('featuredcoursesenabled_desc', 'theme_zenith'),
        0
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Featured course IDs.
    $setting = new admin_setting_configtext(
        'theme_zenith/featuredcourseids',
        get_string('featuredcourseids', 'theme_zenith'),
        get_string('featuredcourseids_desc', 'theme_zenith'),
        ''
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // =========================================================================
    // TAB 3: Course settings.
    // =========================================================================
    $page = new admin_settingpage(
        'theme_zenith_course',
        get_string('coursesettings', 'theme_zenith')
    );

    // Course archive layout.
    $setting = new admin_setting_configselect(
        'theme_zenith/coursearchivelayout',
        get_string('coursearchivelayout', 'theme_zenith'),
        get_string('coursearchivelayout_desc', 'theme_zenith'),
        'grid',
        [
            'grid' => get_string('gridview', 'theme_zenith'),
            'list' => get_string('listview', 'theme_zenith'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Courses per page.
    $setting = new admin_setting_configselect(
        'theme_zenith/coursesperpage',
        get_string('coursesperpage', 'theme_zenith'),
        get_string('coursesperpage_desc', 'theme_zenith'),
        '12',
        ['6' => '6', '9' => '9', '12' => '12', '18' => '18', '24' => '24']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Card animations.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/courseanimations',
        get_string('courseanimations', 'theme_zenith'),
        get_string('courseanimations_desc', 'theme_zenith'),
        1
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Show course category badge.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/coursecategorybadge',
        get_string('coursecategorybadge', 'theme_zenith'),
        get_string('coursecategorybadge_desc', 'theme_zenith'),
        1
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Show instructor names.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/courseinstructor',
        get_string('courseinstructor', 'theme_zenith'),
        get_string('courseinstructor_desc', 'theme_zenith'),
        1
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // =========================================================================
    // TAB 4: Footer settings.
    // =========================================================================
    $page = new admin_settingpage(
        'theme_zenith_footer',
        get_string('footersettings', 'theme_zenith')
    );

    // Footer content (HTML).
    $setting = new admin_setting_confightmleditor(
        'theme_zenith/footercontent',
        get_string('footercontent', 'theme_zenith'),
        get_string('footercontent_desc', 'theme_zenith'),
        ''
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Copyright text.
    $default = '© {year} ' . (isset($SITE) ? format_string($SITE->fullname) : 'Your Site');
    $setting = new admin_setting_configtext(
        'theme_zenith/footercopyright',
        get_string('footercopyright', 'theme_zenith'),
        get_string('footercopyright_desc', 'theme_zenith'),
        $default
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Show Zenith branding.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/footershowbranding',
        get_string('footershowbranding', 'theme_zenith'),
        get_string('footershowbranding_desc', 'theme_zenith'),
        1
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // --- Social media ---
    $page->add(new admin_setting_heading(
        'theme_zenith/socialheading',
        get_string('socialmedia', 'theme_zenith'),
        ''
    ));

    $socialnetworks = ['facebook', 'twitter', 'linkedin', 'youtube', 'instagram'];
    foreach ($socialnetworks as $network) {
        $setting = new admin_setting_configtext(
            'theme_zenith/social' . $network,
            get_string('social' . $network, 'theme_zenith'),
            get_string('social' . $network . '_desc', 'theme_zenith'),
            '',
            PARAM_URL
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    $settings->add($page);

    // =========================================================================
    // TAB 5: Login page settings.
    // =========================================================================
    $page = new admin_settingpage(
        'theme_zenith_login',
        get_string('loginsettings', 'theme_zenith')
    );

    // Login layout chooser.
    $setting = new admin_setting_configselect(
        'theme_zenith/loginlayout',
        get_string('loginlayout', 'theme_zenith'),
        get_string('loginlayout_desc', 'theme_zenith'),
        'center',
        [
            'center' => get_string('loginlayout_center', 'theme_zenith'),
            'left'   => get_string('loginlayout_left', 'theme_zenith'),
            'right'  => get_string('loginlayout_right', 'theme_zenith'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login background image.
    $setting = new admin_setting_configstoredfile(
        'theme_zenith/loginbackgroundimage',
        get_string('loginbackgroundimage', 'theme_zenith'),
        get_string('loginbackgroundimage_desc', 'theme_zenith'),
        'loginbackgroundimage'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login heading text.
    $setting = new admin_setting_configtext(
        'theme_zenith/loginheadingtext',
        get_string('loginheading', 'theme_zenith'),
        get_string('loginheading_desc', 'theme_zenith'),
        get_string('loginheading', 'theme_zenith')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Login description (for hero panels).
    $setting = new admin_setting_confightmleditor(
        'theme_zenith/logindescription',
        get_string('logindescription', 'theme_zenith'),
        get_string('logindescription_desc', 'theme_zenith'),
        ''
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // =========================================================================
    // TAB 6: Advanced settings.
    // =========================================================================
    $page = new admin_settingpage(
        'theme_zenith_advanced',
        get_string('advancedsettings', 'theme_zenith')
    );

    // Raw SCSS before compilation.
    $setting = new admin_setting_scsscode(
        'theme_zenith/scsspre',
        get_string('rawscsspre', 'theme_zenith'),
        get_string('rawscsspre_desc', 'theme_zenith'),
        '',
        PARAM_RAW
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS after compilation.
    $setting = new admin_setting_scsscode(
        'theme_zenith/scss',
        get_string('rawscss', 'theme_zenith'),
        get_string('rawscss_desc', 'theme_zenith'),
        '',
        PARAM_RAW
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Custom JS.
    $setting = new admin_setting_configtextarea(
        'theme_zenith/customjs',
        get_string('customjs', 'theme_zenith'),
        get_string('customjs_desc', 'theme_zenith'),
        ''
    );
    $page->add($setting);

    // Developer mode.
    $setting = new admin_setting_configcheckbox(
        'theme_zenith/developermode',
        get_string('developermode', 'theme_zenith'),
        get_string('developermode_desc', 'theme_zenith'),
        0
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
