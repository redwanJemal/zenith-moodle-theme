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
 * Zenith theme language strings.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Zenith';
$string['configtitle'] = 'Zenith';
$string['choosereadme'] = 'Zenith is a modern, premium Moodle theme built on Bootstrap 5. It features a design token system, dark mode, accessibility toolkit, and visual customizer.';

// Settings.
$string['generalsettings'] = 'General';
$string['advancedsettings'] = 'Advanced';

// Brand color.
$string['brandcolor'] = 'Brand colour';
$string['brandcolor_desc'] = 'The primary brand colour used throughout the theme.';

// Logo.
$string['logo'] = 'Logo';
$string['logo_desc'] = 'Upload your site logo. Displayed in the navbar. Recommended size: 200x50px.';
$string['logomini'] = 'Logo (compact)';
$string['logomini_desc'] = 'A compact version of your logo for mobile and collapsed navigation. Recommended size: 50x50px.';

// Footer.
$string['footersettings'] = 'Footer';
$string['footercontent'] = 'Footer content';
$string['footercontent_desc'] = 'Add HTML content to the footer area. Supports rich text, links, and basic formatting.';
$string['footercopyright'] = 'Copyright text';
$string['footercopyright_desc'] = 'Copyright notice displayed at the bottom of the footer. Use {year} for the current year.';
$string['footershowbranding'] = 'Show Zenith branding';
$string['footershowbranding_desc'] = 'Display "Powered by Zenith" in the footer.';

// Social media.
$string['socialmedia'] = 'Social media';
$string['socialfacebook'] = 'Facebook URL';
$string['socialfacebook_desc'] = 'Your Facebook page URL.';
$string['socialtwitter'] = 'X (Twitter) URL';
$string['socialtwitter_desc'] = 'Your X (formerly Twitter) profile URL.';
$string['sociallinkedin'] = 'LinkedIn URL';
$string['sociallinkedin_desc'] = 'Your LinkedIn company or profile URL.';
$string['socialyoutube'] = 'YouTube URL';
$string['socialyoutube_desc'] = 'Your YouTube channel URL.';
$string['socialinstagram'] = 'Instagram URL';
$string['socialinstagram_desc'] = 'Your Instagram profile URL.';

// Dark mode.
$string['switchtomode'] = 'Switch theme mode';

// Login page.
$string['loginsettings'] = 'Login page';
$string['loginlayout'] = 'Login page layout';
$string['loginlayout_desc'] = 'Choose the login page layout style.';
$string['loginlayout_center'] = 'Centred form';
$string['loginlayout_left'] = 'Left panel';
$string['loginlayout_right'] = 'Right panel';
$string['loginbackgroundimage'] = 'Login background image';
$string['loginbackgroundimage_desc'] = 'Upload a background image for the login page. Used as a full-screen background (centred layout) or as the hero panel image (left/right layouts). Recommended size: 1920x1080px.';
$string['loginheading'] = 'Welcome back';
$string['loginheading_desc'] = 'Heading text displayed above the login form.';
$string['logindescription'] = 'Login description';
$string['logindescription_desc'] = 'Description text displayed below the heading on the hero panel (left/right layouts only). Supports HTML.';

// Advanced - SCSS.
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Custom SCSS code appended after the theme SCSS. Use this for minor style adjustments.';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'SCSS code prepended before compilation. Use this to define variables that override the theme defaults.';
