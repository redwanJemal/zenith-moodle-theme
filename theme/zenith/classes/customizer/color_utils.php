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
 * Color utility functions for the Zenith customizer.
 *
 * @package    theme_zenith
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_zenith\customizer;

/**
 * Color math utilities: hex/HSL conversion, shade, tint, palette generation.
 */
class color_utils {

    /**
     * Convert hex color to HSL array.
     *
     * @param string $hex Hex color string (e.g. '#6366f1' or '6366f1').
     * @return array [h, s, l] where h is 0-360, s and l are 0-100.
     */
    public static function hex_to_hsl(string $hex): array {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        $d = $max - $min;

        if ($d == 0) {
            $h = 0;
            $s = 0;
        } else {
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            if ($max === $r) {
                $h = (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6;
            } else if ($max === $g) {
                $h = (($b - $r) / $d + 2) / 6;
            } else {
                $h = (($r - $g) / $d + 4) / 6;
            }
        }

        return [round($h * 360, 1), round($s * 100, 1), round($l * 100, 1)];
    }

    /**
     * Convert HSL to hex color string.
     *
     * @param float $h Hue (0-360).
     * @param float $s Saturation (0-100).
     * @param float $l Lightness (0-100).
     * @return string Hex color with '#' prefix.
     */
    public static function hsl_to_hex(float $h, float $s, float $l): string {
        $h = $h / 360;
        $s = $s / 100;
        $l = $l / 100;

        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = self::hue_to_rgb($p, $q, $h + 1 / 3);
            $g = self::hue_to_rgb($p, $q, $h);
            $b = self::hue_to_rgb($p, $q, $h - 1 / 3);
        }

        return '#' . sprintf('%02x%02x%02x',
            (int) round($r * 255),
            (int) round($g * 255),
            (int) round($b * 255)
        );
    }

    /**
     * Helper for HSL to RGB conversion.
     *
     * @param float $p
     * @param float $q
     * @param float $t
     * @return float
     */
    private static function hue_to_rgb(float $p, float $q, float $t): float {
        if ($t < 0) {
            $t += 1;
        }
        if ($t > 1) {
            $t -= 1;
        }
        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }
        if ($t < 1 / 2) {
            return $q;
        }
        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }
        return $p;
    }

    /**
     * Darken a hex color by a percentage.
     *
     * @param string $hex Base hex color.
     * @param float $amount Amount to darken (0-100).
     * @return string Darkened hex color.
     */
    public static function shade(string $hex, float $amount): string {
        [$h, $s, $l] = self::hex_to_hsl($hex);
        $l = max(0, $l - $amount);
        return self::hsl_to_hex($h, $s, $l);
    }

    /**
     * Lighten a hex color by a percentage.
     *
     * @param string $hex Base hex color.
     * @param float $amount Amount to lighten (0-100).
     * @return string Lightened hex color.
     */
    public static function tint(string $hex, float $amount): string {
        [$h, $s, $l] = self::hex_to_hsl($hex);
        $l = min(100, $l + $amount);
        return self::hsl_to_hex($h, $s, $l);
    }

    /**
     * Generate a full color palette from a single primary color.
     *
     * @param string $hex Base hex color.
     * @return array Associative array with hover, active, light, subtle variants.
     */
    public static function generate_palette(string $hex): array {
        return [
            'base' => $hex,
            'hover' => self::shade($hex, 8),
            'active' => self::shade($hex, 15),
            'light' => self::tint($hex, 35),
            'subtle' => self::tint($hex, 42),
        ];
    }

    /**
     * Determine contrasting text color (black or white) for a background.
     *
     * @param string $hex Background hex color.
     * @return string '#ffffff' or '#000000'.
     */
    public static function contrast_text(string $hex): string {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        // W3C relative luminance formula.
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Validate a hex color string.
     *
     * @param string $hex Color to validate.
     * @return bool True if valid hex color.
     */
    public static function is_valid_hex(string $hex): bool {
        return (bool) preg_match('/^#?([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $hex);
    }

    /**
     * Normalize hex color to 6-digit lowercase with # prefix.
     *
     * @param string $hex Input hex color.
     * @return string Normalized hex color.
     */
    public static function normalize_hex(string $hex): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        return '#' . strtolower($hex);
    }
}
