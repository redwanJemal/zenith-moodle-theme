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
 * Setup wizard controller for Zenith theme.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith;

defined('MOODLE_INTERNAL') || die();

use theme_zenith\customizer\presets;

/**
 * Guided first-time setup wizard — 5 steps for new installations.
 */
class setupwizard {

    /** @var string[] Step identifiers in order. */
    public const STEPS = ['welcome', 'branding', 'colors', 'homepage', 'complete'];

    /** @var string[] Valid step names for save_step validation. */
    public const SAVEABLE_STEPS = ['colors', 'homepage'];

    /** @var string[] Settings that can be saved in the homepage step. */
    public const HOMEPAGE_SETTINGS = ['heroenabled', 'herotitle', 'herosubtitle'];

    /**
     * Check whether the wizard should be shown to the current user.
     *
     * @return bool True if wizard should display.
     */
    public static function should_show(): bool {
        $completed = get_config('theme_zenith', 'wizard_completed');
        if ($completed) {
            return false;
        }
        $context = \context_system::instance();
        return has_capability('moodle/site:config', $context);
    }

    /**
     * Mark the wizard as completed so it does not show again.
     */
    public static function mark_completed(): void {
        set_config('wizard_completed', '1', 'theme_zenith');
    }

    /**
     * Reset the wizard so it shows again on next admin visit.
     */
    public static function reset(): void {
        unset_config('wizard_completed', 'theme_zenith');
    }

    /**
     * Build the template context for the wizard overlay.
     *
     * @return array Data for setupwizard/main.mustache.
     */
    public static function get_template_context(): array {
        global $SITE;

        $presetdata = presets::get_for_template();
        $sitename = format_string($SITE->fullname, true,
            ['context' => \context_course::instance(SITEID), 'escape' => false]);

        // Current homepage settings.
        $heroenabled = get_config('theme_zenith', 'heroenabled');
        $herotitle = get_config('theme_zenith', 'herotitle');
        $herosubtitle = get_config('theme_zenith', 'herosubtitle');

        return [
            'presets' => $presetdata,
            'presetsjs' => json_encode($presetdata),
            'sitename' => $sitename,
            'heroenabled' => ($heroenabled !== false) ? (bool) $heroenabled : true,
            'herotitle' => ($herotitle !== false) ? $herotitle : get_string('herotitle_default', 'theme_zenith'),
            'herosubtitle' => ($herosubtitle !== false) ? $herosubtitle
                : get_string('herosubtitle_default', 'theme_zenith'),
            'settingsurl' => (new \moodle_url('/admin/settings.php', ['section' => 'themesettingzenith']))->out(false),
            'dashboardurl' => (new \moodle_url('/my/'))->out(false),
        ];
    }

    /**
     * Validate that a step name is valid for saving.
     *
     * @param string $step Step name.
     * @return bool
     */
    public static function is_valid_step(string $step): bool {
        return in_array($step, self::SAVEABLE_STEPS);
    }
}
