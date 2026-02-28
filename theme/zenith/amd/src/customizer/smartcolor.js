/**
 * HSL color math for the Zenith visual customizer.
 *
 * Mirrors the PHP color_utils class for client-side live preview.
 *
 * @module     theme_zenith/customizer/smartcolor
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {

    /**
     * Convert hex to HSL.
     * @param {string} hex
     * @returns {number[]} [h, s, l]
     */
    var hexToHsl = function(hex) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        var r = parseInt(hex.substring(0, 2), 16) / 255;
        var g = parseInt(hex.substring(2, 4), 16) / 255;
        var b = parseInt(hex.substring(4, 6), 16) / 255;

        var max = Math.max(r, g, b);
        var min = Math.min(r, g, b);
        var l = (max + min) / 2;
        var d = max - min;

        var h = 0;
        var s = 0;

        if (d !== 0) {
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            if (max === r) {
                h = ((g - b) / d + (g < b ? 6 : 0)) / 6;
            } else if (max === g) {
                h = ((b - r) / d + 2) / 6;
            } else {
                h = ((r - g) / d + 4) / 6;
            }
        }

        return [Math.round(h * 360 * 10) / 10, Math.round(s * 1000) / 10, Math.round(l * 1000) / 10];
    };

    /**
     * Helper for HSL to RGB.
     * @param {number} p
     * @param {number} q
     * @param {number} t
     * @returns {number}
     */
    var hueToRgb = function(p, q, t) {
        if (t < 0) {
            t += 1;
        }
        if (t > 1) {
            t -= 1;
        }
        if (t < 1 / 6) {
            return p + (q - p) * 6 * t;
        }
        if (t < 1 / 2) {
            return q;
        }
        if (t < 2 / 3) {
            return p + (q - p) * (2 / 3 - t) * 6;
        }
        return p;
    };

    /**
     * Convert HSL to hex.
     * @param {number} h Hue 0-360
     * @param {number} s Saturation 0-100
     * @param {number} l Lightness 0-100
     * @returns {string}
     */
    var hslToHex = function(h, s, l) {
        h /= 360;
        s /= 100;
        l /= 100;

        var r, g, b;
        if (s === 0) {
            r = g = b = l;
        } else {
            var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            var p = 2 * l - q;
            r = hueToRgb(p, q, h + 1 / 3);
            g = hueToRgb(p, q, h);
            b = hueToRgb(p, q, h - 1 / 3);
        }

        var toHex = function(v) {
            var hex = Math.round(v * 255).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        };

        return '#' + toHex(r) + toHex(g) + toHex(b);
    };

    /**
     * Darken a color.
     * @param {string} hex
     * @param {number} amount 0-100
     * @returns {string}
     */
    var shade = function(hex, amount) {
        var hsl = hexToHsl(hex);
        return hslToHex(hsl[0], hsl[1], Math.max(0, hsl[2] - amount));
    };

    /**
     * Lighten a color.
     * @param {string} hex
     * @param {number} amount 0-100
     * @returns {string}
     */
    var tint = function(hex, amount) {
        var hsl = hexToHsl(hex);
        return hslToHex(hsl[0], hsl[1], Math.min(100, hsl[2] + amount));
    };

    /**
     * Generate a full palette from a base color.
     * @param {string} hex
     * @returns {Object}
     */
    var generatePalette = function(hex) {
        return {
            base: hex,
            hover: shade(hex, 8),
            active: shade(hex, 15),
            light: tint(hex, 35),
            subtle: tint(hex, 42),
        };
    };

    /**
     * Determine contrasting text color.
     * @param {string} hex
     * @returns {string}
     */
    var contrastText = function(hex) {
        var cleaned = hex.replace('#', '');
        var r = parseInt(cleaned.substring(0, 2), 16);
        var g = parseInt(cleaned.substring(2, 4), 16);
        var b = parseInt(cleaned.substring(4, 6), 16);
        var luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance > 0.5 ? '#000000' : '#ffffff';
    };

    /**
     * Validate a hex color.
     * @param {string} hex
     * @returns {boolean}
     */
    var isValidHex = function(hex) {
        return /^#?([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(hex);
    };

    /**
     * Normalize hex to 6-digit lowercase with #.
     * @param {string} hex
     * @returns {string}
     */
    var normalizeHex = function(hex) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        return '#' + hex.toLowerCase();
    };

    return {
        hexToHsl: hexToHsl,
        hslToHex: hslToHex,
        shade: shade,
        tint: tint,
        generatePalette: generatePalette,
        contrastText: contrastText,
        isValidHex: isValidHex,
        normalizeHex: normalizeHex,
    };
});
