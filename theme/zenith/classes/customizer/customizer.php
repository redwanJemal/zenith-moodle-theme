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
 * Customizer controller — settings definitions, load/save/reset, CSS generation.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith\customizer;

defined('MOODLE_INTERNAL') || die();

/**
 * Central customizer controller.
 *
 * Settings are stored in theme_zenith plugin config with `cust_` prefix.
 */
class customizer {

    /** @var string Config key prefix for customizer settings. */
    private const PREFIX = 'cust_';

    /**
     * All 26 customizer settings with type, default, section, and CSS token mappings.
     *
     * Each setting maps to one or more --z-* CSS custom properties.
     * Settings with 'derived' generate additional tokens via color_utils.
     */
    public const SETTINGS = [
        // === Colors (8) ===
        'primary' => [
            'type' => 'color',
            'default' => '#6366f1',
            'section' => 'colors',
            'tokens' => ['--z-primary'],
            'derived' => ['hover', 'active', 'light', 'subtle'],
        ],
        'secondary' => [
            'type' => 'color',
            'default' => '#64748b',
            'section' => 'colors',
            'tokens' => ['--z-secondary'],
            'derived' => ['hover', 'active'],
        ],
        'success' => [
            'type' => 'color',
            'default' => '#10b981',
            'section' => 'colors',
            'tokens' => ['--z-success'],
            'derived' => ['hover', 'light'],
        ],
        'warning' => [
            'type' => 'color',
            'default' => '#f59e0b',
            'section' => 'colors',
            'tokens' => ['--z-warning'],
            'derived' => ['hover', 'light'],
        ],
        'danger' => [
            'type' => 'color',
            'default' => '#ef4444',
            'section' => 'colors',
            'tokens' => ['--z-danger'],
            'derived' => ['hover', 'light'],
        ],
        'info' => [
            'type' => 'color',
            'default' => '#3b82f6',
            'section' => 'colors',
            'tokens' => ['--z-info'],
            'derived' => ['hover', 'light'],
        ],
        'navbar_bg' => [
            'type' => 'color',
            'default' => '#ffffff',
            'section' => 'colors',
            'tokens' => ['--z-navbar-bg'],
        ],
        'footer_bg' => [
            'type' => 'color',
            'default' => '#0f172a',
            'section' => 'colors',
            'tokens' => ['--z-footer-bg'],
        ],

        // === Typography (5) ===
        'font_family' => [
            'type' => 'select',
            'default' => 'Inter',
            'section' => 'typography',
            'tokens' => ['--z-font-sans'],
            'options' => [
                'Inter', 'Roboto', 'Open Sans', 'Lato', 'Nunito',
                'Source Sans Pro', 'DM Sans', 'IBM Plex Sans', 'Poppins', 'Montserrat',
                'system-ui',
            ],
        ],
        'font_size' => [
            'type' => 'range',
            'default' => '15',
            'section' => 'typography',
            'tokens' => ['--z-text-base'],
            'min' => 13,
            'max' => 18,
            'step' => 1,
            'unit' => 'px',
        ],
        'heading_weight' => [
            'type' => 'select',
            'default' => '700',
            'section' => 'typography',
            'tokens' => [],
            'options' => ['400', '500', '600', '700', '800'],
        ],
        'body_weight' => [
            'type' => 'select',
            'default' => '400',
            'section' => 'typography',
            'tokens' => [],
            'options' => ['300', '400', '500'],
        ],
        'line_height' => [
            'type' => 'range',
            'default' => '1.5',
            'section' => 'typography',
            'tokens' => ['--z-leading-normal'],
            'min' => 1.2,
            'max' => 1.8,
            'step' => 0.1,
            'unit' => '',
        ],

        // === Buttons (4) ===
        'btn_radius' => [
            'type' => 'range',
            'default' => '8',
            'section' => 'buttons',
            'tokens' => ['--z-btn-radius'],
            'min' => 0,
            'max' => 24,
            'step' => 1,
            'unit' => 'px',
        ],
        'btn_padding_y' => [
            'type' => 'range',
            'default' => '10',
            'section' => 'buttons',
            'tokens' => ['--z-btn-padding-y'],
            'min' => 4,
            'max' => 16,
            'step' => 1,
            'unit' => 'px',
        ],
        'btn_padding_x' => [
            'type' => 'range',
            'default' => '16',
            'section' => 'buttons',
            'tokens' => ['--z-btn-padding-x'],
            'min' => 8,
            'max' => 32,
            'step' => 2,
            'unit' => 'px',
        ],
        'btn_weight' => [
            'type' => 'select',
            'default' => '600',
            'section' => 'buttons',
            'tokens' => ['--z-btn-font-weight'],
            'options' => ['400', '500', '600', '700'],
        ],

        // === Header (4) ===
        'navbar_height' => [
            'type' => 'range',
            'default' => '64',
            'section' => 'header',
            'tokens' => ['--z-navbar-height'],
            'min' => 48,
            'max' => 80,
            'step' => 2,
            'unit' => 'px',
        ],
        'navbar_shadow' => [
            'type' => 'checkbox',
            'default' => '1',
            'section' => 'header',
            'tokens' => [],
        ],
        'navbar_border' => [
            'type' => 'checkbox',
            'default' => '1',
            'section' => 'header',
            'tokens' => [],
        ],
        'navbar_style' => [
            'type' => 'select',
            'default' => 'light',
            'section' => 'header',
            'tokens' => [],
            'options' => ['light', 'dark'],
        ],

        // === Footer (4) ===
        'footer_text' => [
            'type' => 'color',
            'default' => '#94a3b8',
            'section' => 'footer',
            'tokens' => ['--z-footer-text'],
        ],
        'footer_link' => [
            'type' => 'color',
            'default' => '#cbd5e1',
            'section' => 'footer',
            'tokens' => ['--z-footer-link'],
        ],
        'footer_border' => [
            'type' => 'color',
            'default' => '#1e293b',
            'section' => 'footer',
            'tokens' => ['--z-footer-border'],
        ],
    ];

    /** @var string[] List of Google Fonts that need loading. */
    public const GOOGLE_FONTS = [
        'Inter', 'Roboto', 'Open Sans', 'Lato', 'Nunito',
        'Source Sans Pro', 'DM Sans', 'IBM Plex Sans', 'Poppins', 'Montserrat',
    ];

    /**
     * Get all current settings values.
     *
     * @return array Key-value map of all settings with current or default values.
     */
    public static function get_settings(): array {
        $result = [];
        foreach (self::SETTINGS as $key => $def) {
            $stored = get_config('theme_zenith', self::PREFIX . $key);
            $result[$key] = ($stored !== false) ? $stored : $def['default'];
        }
        return $result;
    }

    /**
     * Save settings (partial update).
     *
     * @param array $values Key-value map of settings to save.
     * @return bool True on success.
     */
    public static function save(array $values): bool {
        foreach ($values as $key => $value) {
            if (!isset(self::SETTINGS[$key])) {
                continue;
            }
            $def = self::SETTINGS[$key];
            $value = self::sanitize($key, $value, $def);
            set_config(self::PREFIX . $key, $value, 'theme_zenith');
        }
        // Purge theme cache to recompile SCSS.
        theme_reset_all_caches();
        return true;
    }

    /**
     * Reset all customizer settings to defaults.
     *
     * @return bool True on success.
     */
    public static function reset(): bool {
        foreach (self::SETTINGS as $key => $def) {
            unset_config(self::PREFIX . $key, 'theme_zenith');
        }
        theme_reset_all_caches();
        return true;
    }

    /**
     * Sanitize a setting value based on its type.
     *
     * @param string $key Setting key.
     * @param string $value Raw value.
     * @param array $def Setting definition.
     * @return string Sanitized value.
     */
    private static function sanitize(string $key, string $value, array $def): string {
        switch ($def['type']) {
            case 'color':
                if (color_utils::is_valid_hex($value)) {
                    return color_utils::normalize_hex($value);
                }
                return $def['default'];

            case 'range':
                $num = floatval($value);
                $min = $def['min'] ?? 0;
                $max = $def['max'] ?? 100;
                return (string) max($min, min($max, $num));

            case 'select':
                if (isset($def['options']) && in_array($value, $def['options'])) {
                    return $value;
                }
                return $def['default'];

            case 'checkbox':
                return ($value === '1' || $value === 'true' || $value === true) ? '1' : '0';

            default:
                return clean_param($value, PARAM_TEXT);
        }
    }

    /**
     * Generate CSS custom property overrides from saved settings.
     *
     * @return string CSS block with :root { --z-*: val; } declarations.
     */
    public static function generate_css(): string {
        $settings = self::get_settings();
        $defaults = [];
        foreach (self::SETTINGS as $key => $def) {
            $defaults[$key] = $def['default'];
        }

        // Only generate if at least one setting differs from default.
        if ($settings === $defaults) {
            return '';
        }

        $css = ":root {\n";

        foreach (self::SETTINGS as $key => $def) {
            $value = $settings[$key];

            // Direct token mappings.
            foreach ($def['tokens'] as $token) {
                $cssvalue = self::format_css_value($key, $value, $def);
                $css .= "    {$token}: {$cssvalue};\n";
            }

            // Derived color variants.
            if ($def['type'] === 'color' && !empty($def['derived'])) {
                $palette = color_utils::generate_palette($value);
                $basename = str_replace('--z-', '', $def['tokens'][0] ?? '');
                if ($basename) {
                    foreach ($def['derived'] as $variant) {
                        if (isset($palette[$variant])) {
                            $css .= "    --z-{$basename}-{$variant}: {$palette[$variant]};\n";
                        }
                    }
                }
            }
        }

        // Navbar style overrides.
        if ($settings['navbar_style'] === 'dark') {
            $navbg = $settings['navbar_bg'];
            $css .= "    --z-navbar-text: rgba(255,255,255,0.85);\n";
            $css .= "    --z-navbar-text-active: #ffffff;\n";
        }

        // Navbar shadow toggle.
        if ($settings['navbar_shadow'] === '0') {
            $css .= "    --z-shadow-sm: none;\n";
        }

        // Navbar border toggle.
        if ($settings['navbar_border'] === '0') {
            $css .= "    --z-navbar-border: transparent;\n";
        }

        // Font family needs fallback stack.
        $fontfamily = $settings['font_family'];
        if ($fontfamily !== 'system-ui') {
            $css .= "    --z-font-sans: '{$fontfamily}', -apple-system, BlinkMacSystemFont, ";
            $css .= "'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;\n";
        } else {
            $css .= "    --z-font-sans: system-ui, -apple-system, BlinkMacSystemFont, ";
            $css .= "'Segoe UI', Roboto, sans-serif;\n";
        }

        // Heading and body font weights (not direct token mapped).
        $css .= "    --z-font-bold: {$settings['heading_weight']};\n";
        $css .= "    --z-font-regular: {$settings['body_weight']};\n";

        // Footer link hover.
        $css .= "    --z-footer-link-hover: #ffffff;\n";

        $css .= "}\n";

        return $css;
    }

    /**
     * Format a setting value for CSS output.
     *
     * @param string $key Setting key.
     * @param string $value Raw value.
     * @param array $def Setting definition.
     * @return string CSS-ready value.
     */
    private static function format_css_value(string $key, string $value, array $def): string {
        if ($def['type'] === 'color') {
            return $value;
        }
        if ($def['type'] === 'range') {
            $unit = $def['unit'] ?? '';
            return $value . $unit;
        }
        return $value;
    }

    /**
     * Get settings formatted for JavaScript (includes metadata for controls).
     *
     * @return array Settings data for JS consumption.
     */
    public static function get_settings_for_js(): array {
        $values = self::get_settings();
        $settings = [];

        foreach (self::SETTINGS as $key => $def) {
            $setting = [
                'key' => $key,
                'type' => $def['type'],
                'value' => $values[$key],
                'default' => $def['default'],
                'section' => $def['section'],
                'tokens' => $def['tokens'],
            ];

            if (isset($def['derived'])) {
                $setting['derived'] = $def['derived'];
            }
            if (isset($def['min'])) {
                $setting['min'] = $def['min'];
            }
            if (isset($def['max'])) {
                $setting['max'] = $def['max'];
            }
            if (isset($def['step'])) {
                $setting['step'] = $def['step'];
            }
            if (isset($def['unit'])) {
                $setting['unit'] = $def['unit'];
            }
            if (isset($def['options'])) {
                $setting['options'] = $def['options'];
            }

            $settings[$key] = $setting;
        }

        return $settings;
    }

    /**
     * Check if a Google Font is needed and return link URL.
     *
     * @return string|null Google Fonts CSS URL or null if system font.
     */
    public static function get_google_font_url(): ?string {
        $settings = self::get_settings();
        $font = $settings['font_family'];
        if ($font === 'system-ui' || !in_array($font, self::GOOGLE_FONTS)) {
            return null;
        }
        $weights = '300;400;500;600;700;800';
        $family = str_replace(' ', '+', $font);
        return "https://fonts.googleapis.com/css2?family={$family}:wght@{$weights}&display=swap";
    }

    /**
     * Check if the current user can use the customizer.
     *
     * @return bool True if user has capability.
     */
    public static function can_use(): bool {
        $context = \context_system::instance();
        return has_capability('moodle/site:config', $context);
    }
}
