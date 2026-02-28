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
 * External API for the Zenith visual customizer.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use theme_zenith\customizer\customizer;

/**
 * Customizer AJAX endpoints: save, get, reset.
 */
class customizer_api extends external_api {

    // ========================================================================
    // save_settings
    // ========================================================================

    /**
     * Parameter definition for save_settings.
     * @return external_function_parameters
     */
    public static function save_settings_parameters(): external_function_parameters {
        return new external_function_parameters([
            'settings' => new external_multiple_structure(
                new external_single_structure([
                    'key' => new external_value(PARAM_ALPHANUMEXT, 'Setting key'),
                    'value' => new external_value(PARAM_RAW, 'Setting value'),
                ])
            ),
        ]);
    }

    /**
     * Save customizer settings via AJAX.
     *
     * @param array $settings Array of ['key' => ..., 'value' => ...].
     * @return array Result with success status.
     */
    public static function save_settings(array $settings): array {
        $params = self::validate_parameters(self::save_settings_parameters(), [
            'settings' => $settings,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        $values = [];
        foreach ($params['settings'] as $setting) {
            $values[$setting['key']] = $setting['value'];
        }

        $success = customizer::save($values);

        return ['success' => $success];
    }

    /**
     * Return definition for save_settings.
     * @return external_single_structure
     */
    public static function save_settings_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the save succeeded'),
        ]);
    }

    // ========================================================================
    // get_settings
    // ========================================================================

    /**
     * Parameter definition for get_settings.
     * @return external_function_parameters
     */
    public static function get_settings_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Get all customizer settings.
     *
     * @return array Settings data.
     */
    public static function get_settings(): array {
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        $settings = customizer::get_settings();
        $result = [];
        foreach ($settings as $key => $value) {
            $result[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return ['settings' => $result];
    }

    /**
     * Return definition for get_settings.
     * @return external_single_structure
     */
    public static function get_settings_returns(): external_single_structure {
        return new external_single_structure([
            'settings' => new external_multiple_structure(
                new external_single_structure([
                    'key' => new external_value(PARAM_ALPHANUMEXT, 'Setting key'),
                    'value' => new external_value(PARAM_RAW, 'Setting value'),
                ])
            ),
        ]);
    }

    // ========================================================================
    // reset_settings
    // ========================================================================

    /**
     * Parameter definition for reset_settings.
     * @return external_function_parameters
     */
    public static function reset_settings_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Reset all customizer settings to defaults.
     *
     * @return array Result with success status.
     */
    public static function reset_settings(): array {
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        $success = customizer::reset();

        return ['success' => $success];
    }

    /**
     * Return definition for reset_settings.
     * @return external_single_structure
     */
    public static function reset_settings_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the reset succeeded'),
        ]);
    }
}
