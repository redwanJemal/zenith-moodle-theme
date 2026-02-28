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
 * External API for the Zenith setup wizard.
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
use theme_zenith\setupwizard;
use theme_zenith\customizer\customizer;

/**
 * Setup wizard AJAX endpoints: save_step, complete_wizard.
 */
class setupwizard_api extends external_api {

    // ========================================================================
    // save_step
    // ========================================================================

    /**
     * Parameter definition for save_step.
     * @return external_function_parameters
     */
    public static function save_step_parameters(): external_function_parameters {
        return new external_function_parameters([
            'step' => new external_value(PARAM_ALPHA, 'Step name'),
            'settings' => new external_multiple_structure(
                new external_single_structure([
                    'key' => new external_value(PARAM_ALPHANUMEXT, 'Setting key'),
                    'value' => new external_value(PARAM_RAW, 'Setting value'),
                ])
            ),
        ]);
    }

    /**
     * Save settings for a wizard step.
     *
     * @param string $step Step name (colors, homepage).
     * @param array $settings Array of ['key' => ..., 'value' => ...].
     * @return array Result with success status.
     */
    public static function save_step(string $step, array $settings): array {
        $params = self::validate_parameters(self::save_step_parameters(), [
            'step' => $step,
            'settings' => $settings,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        $step = $params['step'];

        if (!setupwizard::is_valid_step($step)) {
            return ['success' => false];
        }

        if ($step === 'colors') {
            // Apply preset via customizer — settings array should contain all preset values.
            $values = [];
            foreach ($params['settings'] as $setting) {
                $values[$setting['key']] = $setting['value'];
            }
            customizer::save($values);
        } else if ($step === 'homepage') {
            foreach ($params['settings'] as $setting) {
                if (in_array($setting['key'], setupwizard::HOMEPAGE_SETTINGS)) {
                    set_config($setting['key'], $setting['value'], 'theme_zenith');
                }
            }
            theme_reset_all_caches();
        }

        return ['success' => true];
    }

    /**
     * Return definition for save_step.
     * @return external_single_structure
     */
    public static function save_step_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the save succeeded'),
        ]);
    }

    // ========================================================================
    // complete_wizard
    // ========================================================================

    /**
     * Parameter definition for complete_wizard.
     * @return external_function_parameters
     */
    public static function complete_wizard_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Mark the setup wizard as completed.
     *
     * @return array Result with success status.
     */
    public static function complete_wizard(): array {
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        setupwizard::mark_completed();

        return ['success' => true];
    }

    /**
     * Return definition for complete_wizard.
     * @return external_single_structure
     */
    public static function complete_wizard_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the completion succeeded'),
        ]);
    }
}
