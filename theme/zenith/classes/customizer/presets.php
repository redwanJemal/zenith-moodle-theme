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
 * Preset definitions for the Zenith customizer.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith\customizer;

/**
 * 7 color presets for one-click theme switching.
 */
class presets {

    /** @var array All preset definitions. */
    public const PRESETS = [
        'zenith' => [
            'name' => 'Zenith Default',
            'description' => 'Modern indigo with clean lines',
            'colors' => [
                'primary' => '#6366f1',
                'secondary' => '#64748b',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#ef4444',
                'info' => '#3b82f6',
                'navbar_bg' => '#ffffff',
                'footer_bg' => '#0f172a',
            ],
            'typography' => [
                'font_family' => 'Inter',
                'font_size' => '15',
                'heading_weight' => '700',
                'body_weight' => '400',
                'line_height' => '1.5',
            ],
            'buttons' => [
                'btn_radius' => '8',
                'btn_padding_y' => '10',
                'btn_padding_x' => '16',
                'btn_weight' => '600',
            ],
            'header' => [
                'navbar_height' => '64',
                'navbar_shadow' => '1',
                'navbar_border' => '1',
                'navbar_style' => 'light',
            ],
            'footer' => [
                'footer_text' => '#94a3b8',
                'footer_link' => '#cbd5e1',
                'footer_border' => '#1e293b',
            ],
        ],
        'ocean' => [
            'name' => 'Ocean',
            'description' => 'Cool sky blue professional',
            'colors' => [
                'primary' => '#0ea5e9',
                'secondary' => '#64748b',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#ef4444',
                'info' => '#6366f1',
                'navbar_bg' => '#ffffff',
                'footer_bg' => '#0c4a6e',
            ],
            'typography' => [
                'font_family' => 'Inter',
                'font_size' => '15',
                'heading_weight' => '700',
                'body_weight' => '400',
                'line_height' => '1.5',
            ],
            'buttons' => [
                'btn_radius' => '8',
                'btn_padding_y' => '10',
                'btn_padding_x' => '16',
                'btn_weight' => '600',
            ],
            'header' => [
                'navbar_height' => '64',
                'navbar_shadow' => '1',
                'navbar_border' => '1',
                'navbar_style' => 'light',
            ],
            'footer' => [
                'footer_text' => '#7dd3fc',
                'footer_link' => '#bae6fd',
                'footer_border' => '#075985',
            ],
        ],
        'forest' => [
            'name' => 'Forest',
            'description' => 'Natural green organic',
            'colors' => [
                'primary' => '#059669',
                'secondary' => '#64748b',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#ef4444',
                'info' => '#3b82f6',
                'navbar_bg' => '#ffffff',
                'footer_bg' => '#064e3b',
            ],
            'typography' => [
                'font_family' => 'Source Sans Pro',
                'font_size' => '16',
                'heading_weight' => '700',
                'body_weight' => '400',
                'line_height' => '1.6',
            ],
            'buttons' => [
                'btn_radius' => '6',
                'btn_padding_y' => '10',
                'btn_padding_x' => '16',
                'btn_weight' => '600',
            ],
            'header' => [
                'navbar_height' => '64',
                'navbar_shadow' => '1',
                'navbar_border' => '1',
                'navbar_style' => 'light',
            ],
            'footer' => [
                'footer_text' => '#6ee7b7',
                'footer_link' => '#a7f3d0',
                'footer_border' => '#065f46',
            ],
        ],
        'sunset' => [
            'name' => 'Sunset',
            'description' => 'Warm orange vibrant',
            'colors' => [
                'primary' => '#ea580c',
                'secondary' => '#78716c',
                'success' => '#16a34a',
                'warning' => '#eab308',
                'danger' => '#dc2626',
                'info' => '#0284c7',
                'navbar_bg' => '#ffffff',
                'footer_bg' => '#431407',
            ],
            'typography' => [
                'font_family' => 'Nunito',
                'font_size' => '15',
                'heading_weight' => '800',
                'body_weight' => '400',
                'line_height' => '1.5',
            ],
            'buttons' => [
                'btn_radius' => '12',
                'btn_padding_y' => '10',
                'btn_padding_x' => '20',
                'btn_weight' => '700',
            ],
            'header' => [
                'navbar_height' => '64',
                'navbar_shadow' => '1',
                'navbar_border' => '0',
                'navbar_style' => 'light',
            ],
            'footer' => [
                'footer_text' => '#fdba74',
                'footer_link' => '#fed7aa',
                'footer_border' => '#7c2d12',
            ],
        ],
        'rose' => [
            'name' => 'Rose',
            'description' => 'Soft pink elegant',
            'colors' => [
                'primary' => '#e11d48',
                'secondary' => '#6b7280',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#dc2626',
                'info' => '#6366f1',
                'navbar_bg' => '#ffffff',
                'footer_bg' => '#1c1917',
            ],
            'typography' => [
                'font_family' => 'DM Sans',
                'font_size' => '15',
                'heading_weight' => '700',
                'body_weight' => '400',
                'line_height' => '1.5',
            ],
            'buttons' => [
                'btn_radius' => '24',
                'btn_padding_y' => '10',
                'btn_padding_x' => '20',
                'btn_weight' => '600',
            ],
            'header' => [
                'navbar_height' => '64',
                'navbar_shadow' => '0',
                'navbar_border' => '1',
                'navbar_style' => 'light',
            ],
            'footer' => [
                'footer_text' => '#a8a29e',
                'footer_link' => '#d6d3d1',
                'footer_border' => '#292524',
            ],
        ],
        'midnight' => [
            'name' => 'Midnight',
            'description' => 'Dark violet dramatic',
            'colors' => [
                'primary' => '#7c3aed',
                'secondary' => '#6b7280',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#ef4444',
                'info' => '#06b6d4',
                'navbar_bg' => '#1e1b4b',
                'footer_bg' => '#0f0a2e',
            ],
            'typography' => [
                'font_family' => 'Inter',
                'font_size' => '15',
                'heading_weight' => '700',
                'body_weight' => '400',
                'line_height' => '1.5',
            ],
            'buttons' => [
                'btn_radius' => '8',
                'btn_padding_y' => '10',
                'btn_padding_x' => '16',
                'btn_weight' => '600',
            ],
            'header' => [
                'navbar_height' => '64',
                'navbar_shadow' => '1',
                'navbar_border' => '0',
                'navbar_style' => 'dark',
            ],
            'footer' => [
                'footer_text' => '#a5b4fc',
                'footer_link' => '#c7d2fe',
                'footer_border' => '#1e1b4b',
            ],
        ],
        'slate' => [
            'name' => 'Slate',
            'description' => 'Minimal grey understated',
            'colors' => [
                'primary' => '#475569',
                'secondary' => '#94a3b8',
                'success' => '#10b981',
                'warning' => '#f59e0b',
                'danger' => '#ef4444',
                'info' => '#3b82f6',
                'navbar_bg' => '#ffffff',
                'footer_bg' => '#0f172a',
            ],
            'typography' => [
                'font_family' => 'IBM Plex Sans',
                'font_size' => '15',
                'heading_weight' => '600',
                'body_weight' => '400',
                'line_height' => '1.5',
            ],
            'buttons' => [
                'btn_radius' => '4',
                'btn_padding_y' => '10',
                'btn_padding_x' => '16',
                'btn_weight' => '500',
            ],
            'header' => [
                'navbar_height' => '60',
                'navbar_shadow' => '0',
                'navbar_border' => '1',
                'navbar_style' => 'light',
            ],
            'footer' => [
                'footer_text' => '#94a3b8',
                'footer_link' => '#cbd5e1',
                'footer_border' => '#1e293b',
            ],
        ],
    ];

    /**
     * Get all preset definitions.
     *
     * @return array
     */
    public static function get_all(): array {
        return self::PRESETS;
    }

    /**
     * Get a single preset by key.
     *
     * @param string $key Preset key.
     * @return array|null Preset data or null if not found.
     */
    public static function get(string $key): ?array {
        return self::PRESETS[$key] ?? null;
    }

    /**
     * Flatten a preset into a single key-value settings array.
     *
     * @param string $key Preset key.
     * @return array Flat settings map or empty array.
     */
    public static function flatten(string $key): array {
        $preset = self::get($key);
        if (!$preset) {
            return [];
        }
        $flat = [];
        foreach (['colors', 'typography', 'buttons', 'header', 'footer'] as $section) {
            if (isset($preset[$section])) {
                foreach ($preset[$section] as $k => $v) {
                    $flat[$k] = $v;
                }
            }
        }
        return $flat;
    }

    /**
     * Get preset list for template rendering (cards).
     *
     * @return array Array of preset card data.
     */
    public static function get_for_template(): array {
        $result = [];
        foreach (self::PRESETS as $key => $preset) {
            $result[] = [
                'key' => $key,
                'name' => $preset['name'],
                'description' => $preset['description'],
                'primary' => $preset['colors']['primary'],
                'secondary' => $preset['colors']['secondary'],
                'navbar_bg' => $preset['colors']['navbar_bg'],
                'footer_bg' => $preset['colors']['footer_bg'],
            ];
        }
        return $result;
    }
}
