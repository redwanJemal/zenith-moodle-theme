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

namespace theme_zenith\output;

defined('MOODLE_INTERNAL') || die;

/**
 * Zenith theme renderer.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Render the Zenith footer content block (admin-configurable HTML).
     *
     * @return string HTML for the footer content area.
     */
    public function zenith_footer_content() {
        $theme = $this->page->theme;
        if (!empty($theme->settings->footercontent)) {
            return format_text($theme->settings->footercontent, FORMAT_HTML);
        }
        return '';
    }

    /**
     * Render the Zenith footer copyright line.
     *
     * @return string Copyright text with year placeholder replaced.
     */
    public function zenith_footer_copyright() {
        $theme = $this->page->theme;
        if (!empty($theme->settings->footercopyright)) {
            return htmlspecialchars(str_replace('{year}', date('Y'), $theme->settings->footercopyright));
        }
        return '';
    }

    /**
     * Check if Zenith branding should be shown.
     *
     * @return bool True to show "Powered by Zenith" branding.
     */
    public function zenith_show_branding() {
        $theme = $this->page->theme;
        return !empty($theme->settings->footershowbranding);
    }

    /**
     * Render social media links as HTML.
     *
     * @return string HTML for social media icon links.
     */
    public function zenith_social_links() {
        $theme = $this->page->theme;
        $networks = [
            'facebook'  => 'fa-facebook-f',
            'twitter'   => 'fa-x-twitter',
            'linkedin'  => 'fa-linkedin-in',
            'youtube'   => 'fa-youtube',
            'instagram' => 'fa-instagram',
        ];

        $html = '';
        foreach ($networks as $network => $icon) {
            $settingname = 'social' . $network;
            if (!empty($theme->settings->$settingname)) {
                $url = $theme->settings->$settingname;
                $label = ucfirst($network);
                // Use FontAwesome 6 brands (fa-brands prefix).
                $html .= '<a href="' . htmlspecialchars($url) . '" '
                    . 'class="z-footer__social-link" '
                    . 'target="_blank" rel="noopener noreferrer" '
                    . 'aria-label="' . $label . '">'
                    . '<i class="fa-brands ' . $icon . '" aria-hidden="true"></i>'
                    . '</a>';
            }
        }
        return $html;
    }
}
