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

// Tab titles.
$string['generalsettings'] = 'General';
$string['homepagesettings'] = 'Homepage';
$string['coursesettings'] = 'Courses';
$string['footersettings'] = 'Footer';
$string['loginsettings'] = 'Login page';
$string['advancedsettings'] = 'Advanced';

// General — Brand.
$string['brandcolor'] = 'Brand colour';
$string['brandcolor_desc'] = 'The primary brand colour used throughout the theme.';
$string['secondarycolor'] = 'Secondary colour';
$string['secondarycolor_desc'] = 'A secondary accent colour used for highlights and links.';

// General — Logo.
$string['logo'] = 'Logo';
$string['logo_desc'] = 'Upload your site logo. Displayed in the navbar. Recommended size: 200x50px.';
$string['logomini'] = 'Logo (compact)';
$string['logomini_desc'] = 'A compact version of your logo for mobile and collapsed navigation. Recommended size: 50x50px.';
$string['favicon'] = 'Favicon';
$string['favicon_desc'] = 'Upload a favicon (.ico, .png, or .svg). Displayed in browser tabs.';

// General — Analytics.
$string['googleanalytics'] = 'Google Analytics ID';
$string['googleanalytics_desc'] = 'Enter your Google Analytics tracking ID (e.g. G-XXXXXXXXXX) to enable site analytics.';

// Homepage — Hero section.
$string['herosection'] = 'Hero section';
$string['heroenabled'] = 'Enable hero section';
$string['heroenabled_desc'] = 'Show a hero banner on the site front page.';
$string['herotitle'] = 'Hero title';
$string['herotitle_desc'] = 'The main heading displayed in the hero section.';
$string['herotitle_default'] = 'Learn without limits';
$string['herosubtitle'] = 'Hero subtitle';
$string['herosubtitle_desc'] = 'Supporting text below the hero title.';
$string['herosubtitle_default'] = 'Access world-class courses, connect with expert instructors, and advance your career at your own pace.';
$string['herobackgroundimage'] = 'Hero background image';
$string['herobackgroundimage_desc'] = 'Upload a background image for the hero section. Recommended size: 1920x800px.';
$string['herobuttontext'] = 'Button text';
$string['herobuttontext_desc'] = 'Text for the call-to-action button in the hero section.';
$string['herobuttontext_default'] = 'Browse courses';
$string['herobuttonurl'] = 'Button URL';
$string['herobuttonurl_desc'] = 'URL the call-to-action button links to.';

// Homepage — About section.
$string['aboutsection'] = 'About section';
$string['aboutenabled'] = 'Enable about section';
$string['aboutenabled_desc'] = 'Show an about section below the hero on the front page.';
$string['aboutcontent'] = 'About content';
$string['aboutcontent_desc'] = 'HTML content for the about section. Supports rich text and images.';

// Homepage — Featured courses.
$string['featuredcourses'] = 'Featured courses';
$string['featuredcoursesenabled'] = 'Enable featured courses';
$string['featuredcoursesenabled_desc'] = 'Show a curated selection of courses on the front page.';
$string['featuredcourseids'] = 'Featured course IDs';
$string['featuredcourseids_desc'] = 'Comma-separated list of course IDs to feature (e.g. 2,5,8). Leave empty to show the most recent courses.';

// Course settings.
$string['coursearchivelayout'] = 'Default archive layout';
$string['coursearchivelayout_desc'] = 'The default view for the course catalogue page.';
$string['coursesperpage'] = 'Courses per page';
$string['coursesperpage_desc'] = 'Number of courses displayed per page in the course catalogue.';
$string['courseanimations'] = 'Card hover animations';
$string['courseanimations_desc'] = 'Enable hover lift and shadow animations on course cards.';
$string['coursecategorybadge'] = 'Show category badge';
$string['coursecategorybadge_desc'] = 'Display the course category as a badge on course cards.';
$string['courseinstructor'] = 'Show instructor names';
$string['courseinstructor_desc'] = 'Display instructor names on course cards.';

// Footer.
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

// Course archive.
$string['searchcourses'] = 'Search courses...';
$string['allcategories'] = 'All categories';
$string['sortdefault'] = 'Default';
$string['sortaz'] = 'A–Z';
$string['sortza'] = 'Z–A';
$string['sortnewest'] = 'Newest';
$string['gridview'] = 'Grid view';
$string['listview'] = 'List view';
$string['viewtoggle'] = 'Toggle view';
$string['nocoursefound'] = 'No courses found';
$string['nocoursefounddesc'] = 'Try adjusting your search or filter to find what you are looking for.';
$string['coursecountfmt'] = '{$a} courses';
$string['coursearchive'] = 'Course catalogue';

// Advanced — SCSS.
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Custom SCSS code appended after the theme SCSS. Use this for minor style adjustments.';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'SCSS code prepended before compilation. Use this to define variables that override the theme defaults.';

// Advanced — Custom JS.
$string['customjs'] = 'Custom JavaScript';
$string['customjs_desc'] = 'JavaScript code injected into every page. Use for tracking scripts or minor customisations.';

// Advanced — Developer mode.
$string['developermode'] = 'Developer mode';
$string['developermode_desc'] = 'Enable theme designer mode and disable caching. Only use during development.';
