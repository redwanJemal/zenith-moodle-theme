/**
 * Live preview engine for the Zenith visual customizer.
 *
 * Maps settings to CSS custom properties and applies them to :root in real time.
 *
 * @module     theme_zenith/customizer/preview
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['theme_zenith/customizer/smartcolor'], function(SmartColor) {

    /**
     * Map of settings to their CSS custom property names and value formatters.
     * @type {Object}
     */
    var TOKEN_MAP = {
        // Colors — direct mapping.
        primary: {tokens: ['--z-primary'], derived: ['hover', 'active', 'light', 'subtle']},
        secondary: {tokens: ['--z-secondary'], derived: ['hover', 'active']},
        success: {tokens: ['--z-success'], derived: ['hover', 'light']},
        warning: {tokens: ['--z-warning'], derived: ['hover', 'light']},
        danger: {tokens: ['--z-danger'], derived: ['hover', 'light']},
        info: {tokens: ['--z-info'], derived: ['hover', 'light']},
        navbar_bg: {tokens: ['--z-navbar-bg']},
        footer_bg: {tokens: ['--z-footer-bg']},

        // Typography.
        font_size: {tokens: ['--z-text-base'], unit: 'px'},
        line_height: {tokens: ['--z-leading-normal']},

        // Buttons.
        btn_radius: {tokens: ['--z-btn-radius'], unit: 'px'},
        btn_padding_y: {tokens: ['--z-btn-padding-y'], unit: 'px'},
        btn_padding_x: {tokens: ['--z-btn-padding-x'], unit: 'px'},
        btn_weight: {tokens: ['--z-btn-font-weight']},

        // Header.
        navbar_height: {tokens: ['--z-navbar-height'], unit: 'px'},

        // Footer colors.
        footer_text: {tokens: ['--z-footer-text']},
        footer_link: {tokens: ['--z-footer-link']},
        footer_border: {tokens: ['--z-footer-border']},
    };

    /** @type {Object|null} Snapshot of CSS vars before customizer opened. */
    var snapshot = null;

    /** @type {HTMLLinkElement|null} Dynamically loaded Google Fonts link. */
    var fontLink = null;

    /**
     * Take a snapshot of current CSS custom property values.
     */
    var takeSnapshot = function() {
        var style = getComputedStyle(document.documentElement);
        snapshot = {};
        Object.keys(TOKEN_MAP).forEach(function(key) {
            var mapping = TOKEN_MAP[key];
            mapping.tokens.forEach(function(token) {
                snapshot[token] = style.getPropertyValue(token).trim();
            });
        });
        // Also snapshot derived tokens.
        var derivedTokens = [
            '--z-primary-hover', '--z-primary-active', '--z-primary-light', '--z-primary-subtle',
            '--z-secondary-hover', '--z-secondary-active',
            '--z-success-hover', '--z-success-light',
            '--z-warning-hover', '--z-warning-light',
            '--z-danger-hover', '--z-danger-light',
            '--z-info-hover', '--z-info-light',
            '--z-navbar-text', '--z-navbar-text-active', '--z-navbar-border',
            '--z-font-sans', '--z-font-bold', '--z-font-regular',
            '--z-footer-link-hover', '--z-shadow-sm',
        ];
        derivedTokens.forEach(function(token) {
            snapshot[token] = style.getPropertyValue(token).trim();
        });
    };

    /**
     * Revert all CSS custom properties to snapshot values.
     */
    var revert = function() {
        if (!snapshot) {
            return;
        }
        var root = document.documentElement;
        Object.keys(snapshot).forEach(function(token) {
            var value = snapshot[token];
            if (value) {
                root.style.setProperty(token, value);
            } else {
                root.style.removeProperty(token);
            }
        });
        // Remove dynamic font link.
        if (fontLink) {
            fontLink.remove();
            fontLink = null;
        }
        snapshot = null;
    };

    /**
     * Apply a font family with dynamic Google Fonts loading.
     *
     * @param {string} fontName Font family name.
     */
    var applyFontFamily = function(fontName) {
        var root = document.documentElement;

        if (fontName === 'system-ui') {
            root.style.setProperty('--z-font-sans',
                "system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif");
            if (fontLink) {
                fontLink.remove();
                fontLink = null;
            }
            return;
        }

        // Set the font family immediately.
        root.style.setProperty('--z-font-sans',
            "'" + fontName + "', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, " +
            "'Helvetica Neue', Arial, sans-serif");

        // Load from Google Fonts if not already loaded.
        var family = fontName.replace(/\s+/g, '+');
        var url = 'https://fonts.googleapis.com/css2?family=' + family +
            ':wght@300;400;500;600;700;800&display=swap';

        if (fontLink) {
            fontLink.href = url;
        } else {
            fontLink = document.createElement('link');
            fontLink.rel = 'stylesheet';
            fontLink.href = url;
            document.head.appendChild(fontLink);
        }
    };

    /**
     * Apply a single setting value to the live page.
     *
     * @param {string} key Setting key.
     * @param {string} value Setting value.
     */
    var applySetting = function(key, value) {
        var root = document.documentElement;
        var mapping = TOKEN_MAP[key];

        if (mapping) {
            var unit = mapping.unit || '';
            mapping.tokens.forEach(function(token) {
                root.style.setProperty(token, value + unit);
            });

            // Generate derived color variants.
            if (mapping.derived) {
                var palette = SmartColor.generatePalette(value);
                var baseName = mapping.tokens[0].replace('--z-', '');
                mapping.derived.forEach(function(variant) {
                    if (palette[variant]) {
                        root.style.setProperty('--z-' + baseName + '-' + variant, palette[variant]);
                    }
                });
            }
        }

        // Special handling for non-token settings.
        switch (key) {
            case 'font_family':
                applyFontFamily(value);
                break;
            case 'heading_weight':
                root.style.setProperty('--z-font-bold', value);
                break;
            case 'body_weight':
                root.style.setProperty('--z-font-regular', value);
                break;
            case 'navbar_shadow':
                if (value === '0' || value === false) {
                    root.style.setProperty('--z-shadow-sm', 'none');
                } else {
                    root.style.setProperty('--z-shadow-sm',
                        '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1)');
                }
                break;
            case 'navbar_border':
                if (value === '0' || value === false) {
                    root.style.setProperty('--z-navbar-border', 'transparent');
                } else {
                    root.style.setProperty('--z-navbar-border', 'var(--z-border-color)');
                }
                break;
            case 'navbar_style':
                if (value === 'dark') {
                    root.style.setProperty('--z-navbar-text', 'rgba(255,255,255,0.85)');
                    root.style.setProperty('--z-navbar-text-active', '#ffffff');
                } else {
                    root.style.setProperty('--z-navbar-text', 'var(--z-text-secondary)');
                    root.style.setProperty('--z-navbar-text-active', 'var(--z-primary)');
                }
                break;
            case 'navbar_bg':
                // Also update navbar text if dark bg.
                var textColor = SmartColor.contrastText(value);
                if (textColor === '#ffffff') {
                    root.style.setProperty('--z-navbar-text', 'rgba(255,255,255,0.85)');
                    root.style.setProperty('--z-navbar-text-active', '#ffffff');
                }
                break;
        }
    };

    /**
     * Apply all settings at once (e.g. from a preset).
     *
     * @param {Object} settings Key-value map.
     */
    var applyAll = function(settings) {
        Object.keys(settings).forEach(function(key) {
            applySetting(key, settings[key]);
        });
    };

    return {
        takeSnapshot: takeSnapshot,
        revert: revert,
        applySetting: applySetting,
        applyAll: applyAll,
    };
});
