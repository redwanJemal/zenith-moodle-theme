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
 * Zenith theme external service declarations.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'theme_zenith_customizer_save' => [
        'classname' => 'theme_zenith\external\customizer_api',
        'methodname' => 'save_settings',
        'description' => 'Save customizer settings',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'moodle/site:config',
    ],
    'theme_zenith_customizer_get' => [
        'classname' => 'theme_zenith\external\customizer_api',
        'methodname' => 'get_settings',
        'description' => 'Get customizer settings',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'moodle/site:config',
    ],
    'theme_zenith_customizer_reset' => [
        'classname' => 'theme_zenith\external\customizer_api',
        'methodname' => 'reset_settings',
        'description' => 'Reset customizer settings to defaults',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'moodle/site:config',
    ],
];
