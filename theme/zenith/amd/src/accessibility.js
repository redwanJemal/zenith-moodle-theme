/**
 * Zenith accessibility toolkit module.
 *
 * Floating panel with 6 features: font size, high contrast, dyslexia font,
 * reading ruler, link highlighting, pause animations.
 *
 * Preferences persist via localStorage (instant) + Moodle UserRepository (cross-session).
 *
 * @module     theme_zenith/accessibility
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core_user/repository'], function(UserRepository) {
    'use strict';

    var PREF_PREFIX = 'theme_zenith_a11y_';

    var FEATURES = ['contrast', 'dyslexia', 'ruler', 'links', 'animations'];

    var panel = null;
    var trigger = null;
    var rulerEl = null;
    var focusTrapElements = [];

    /**
     * Get a localStorage preference value.
     *
     * @param {string} key Preference key (without prefix).
     * @return {string|null}
     */
    function getPref(key) {
        try {
            return localStorage.getItem(PREF_PREFIX + key);
        } catch (e) {
            return null;
        }
    }

    /**
     * Save preference to both localStorage and Moodle UserRepository.
     *
     * @param {string} key Preference key (without prefix).
     * @param {string} value Preference value.
     */
    function savePref(key, value) {
        try {
            localStorage.setItem(PREF_PREFIX + key, value);
        } catch (e) {
            // Ignore.
        }
        UserRepository.setUserPreference(PREF_PREFIX + key, value);
    }

    /**
     * Remove a preference from both stores.
     *
     * @param {string} key Preference key (without prefix).
     */
    function removePref(key) {
        try {
            localStorage.removeItem(PREF_PREFIX + key);
        } catch (e) {
            // Ignore.
        }
        UserRepository.setUserPreference(PREF_PREFIX + key, '');
    }

    /**
     * Set a data attribute on the html element.
     *
     * @param {string} attr Attribute name (e.g., 'a11y-fontsize').
     * @param {string} value Attribute value.
     */
    function setAttr(attr, value) {
        document.documentElement.setAttribute('data-' + attr, value);
    }

    /**
     * Remove a data attribute from the html element.
     *
     * @param {string} attr Attribute name.
     */
    function removeAttr(attr) {
        document.documentElement.removeAttribute('data-' + attr);
    }

    /**
     * Update a toggle button's visual state.
     *
     * @param {string} feature Feature name.
     * @param {boolean} active Whether the feature is active.
     */
    function updateToggleUI(feature, active) {
        var btn = panel.querySelector('[data-feature="' + feature + '"]');
        if (btn) {
            btn.setAttribute('aria-checked', active ? 'true' : 'false');
            if (active) {
                btn.classList.add('z-a11y__toggle--active');
            } else {
                btn.classList.remove('z-a11y__toggle--active');
            }
        }
    }

    /**
     * Update the font size radio group UI.
     *
     * @param {string} size The active size ('100', '125', '150').
     */
    function updateFontsizeUI(size) {
        var buttons = panel.querySelectorAll('[data-action="set-fontsize"]');
        buttons.forEach(function(btn) {
            var active = btn.getAttribute('data-value') === size;
            btn.setAttribute('aria-checked', active ? 'true' : 'false');
            if (active) {
                btn.classList.add('z-a11y__fontsize-btn--active');
            } else {
                btn.classList.remove('z-a11y__fontsize-btn--active');
            }
        });
    }

    /**
     * Toggle a boolean accessibility feature.
     *
     * @param {string} feature Feature name.
     */
    function toggleFeature(feature) {
        var attr = 'a11y-' + feature;
        var current = document.documentElement.hasAttribute('data-' + attr);

        if (current) {
            removeAttr(attr);
            removePref(feature);
            updateToggleUI(feature, false);

            if (feature === 'ruler') {
                disableRuler();
            }
        } else {
            setAttr(attr, 'true');
            savePref(feature, 'true');
            updateToggleUI(feature, true);

            if (feature === 'ruler') {
                enableRuler();
            }
        }
    }

    /**
     * Set font size preference.
     *
     * @param {string} size Size value ('100', '125', '150').
     */
    function setFontsize(size) {
        if (size === '100') {
            removeAttr('a11y-fontsize');
            removePref('fontsize');
        } else {
            setAttr('a11y-fontsize', size);
            savePref('fontsize', size);
        }
        updateFontsizeUI(size);
    }

    /**
     * Enable the reading ruler (follows mouse).
     */
    function enableRuler() {
        if (!rulerEl) {
            return;
        }
        rulerEl.style.display = 'block';
        document.addEventListener('mousemove', moveRuler);
    }

    /**
     * Disable the reading ruler.
     */
    function disableRuler() {
        if (!rulerEl) {
            return;
        }
        rulerEl.style.display = 'none';
        document.removeEventListener('mousemove', moveRuler);
    }

    /**
     * Move the reading ruler to follow the mouse Y position.
     *
     * @param {MouseEvent} e Mouse event.
     */
    function moveRuler(e) {
        if (rulerEl) {
            rulerEl.style.transform = 'translateY(' + e.clientY + 'px)';
        }
    }

    /**
     * Open the accessibility panel.
     */
    function openPanel() {
        if (!panel) {
            return;
        }
        panel.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');

        // Focus the close button.
        var closeBtn = panel.querySelector('[data-action="close-a11y"]');
        if (closeBtn) {
            closeBtn.focus();
        }

        // Collect focusable elements for focus trap.
        focusTrapElements = Array.from(
            panel.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')
        );
    }

    /**
     * Close the accessibility panel.
     */
    function closePanel() {
        if (!panel) {
            return;
        }
        panel.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
        trigger.focus();
    }

    /**
     * Toggle the panel open/closed.
     */
    function togglePanel() {
        if (panel && panel.hidden) {
            openPanel();
        } else {
            closePanel();
        }
    }

    /**
     * Handle keyboard events for focus trap and Escape to close.
     *
     * @param {KeyboardEvent} e Keyboard event.
     */
    function handleKeydown(e) {
        if (!panel || panel.hidden) {
            return;
        }

        if (e.key === 'Escape') {
            e.preventDefault();
            closePanel();
            return;
        }

        if (e.key === 'Tab' && focusTrapElements.length > 0) {
            var firstEl = focusTrapElements[0];
            var lastEl = focusTrapElements[focusTrapElements.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === firstEl) {
                    e.preventDefault();
                    lastEl.focus();
                }
            } else {
                if (document.activeElement === lastEl) {
                    e.preventDefault();
                    firstEl.focus();
                }
            }
        }
    }

    /**
     * Reset all accessibility preferences.
     */
    function resetAll() {
        // Remove font size.
        removeAttr('a11y-fontsize');
        removePref('fontsize');
        updateFontsizeUI('100');

        // Remove all boolean features.
        FEATURES.forEach(function(feature) {
            removeAttr('a11y-' + feature);
            removePref(feature);
            updateToggleUI(feature, false);
        });

        disableRuler();
    }

    /**
     * Restore preferences from localStorage on page load.
     */
    function restorePrefs() {
        // Font size.
        var fontSize = getPref('fontsize');
        if (fontSize && (fontSize === '125' || fontSize === '150')) {
            setAttr('a11y-fontsize', fontSize);
            updateFontsizeUI(fontSize);
        }

        // Boolean features.
        FEATURES.forEach(function(feature) {
            var val = getPref(feature);
            if (val === 'true') {
                setAttr('a11y-' + feature, 'true');
                updateToggleUI(feature, true);
                if (feature === 'ruler') {
                    enableRuler();
                }
            }
        });
    }

    /**
     * Initialize the accessibility toolkit.
     *
     * @param {Object} config Configuration with strings.
     */
    function init(config) {
        void config; // Strings are loaded via Mustache {{#str}}.

        var container = document.querySelector('[data-region="accessibility"]');
        if (!container) {
            return;
        }

        panel = container.querySelector('.z-a11y__panel');
        trigger = container.querySelector('.z-a11y__trigger');
        rulerEl = container.querySelector('.z-a11y__ruler');

        // Restore saved preferences.
        restorePrefs();

        // Toggle panel.
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            togglePanel();
        });

        // Close button.
        var closeBtn = panel.querySelector('[data-action="close-a11y"]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closePanel();
            });
        }

        // Font size buttons.
        panel.querySelectorAll('[data-action="set-fontsize"]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                setFontsize(btn.getAttribute('data-value'));
            });
        });

        // Feature toggle buttons.
        panel.querySelectorAll('[data-action="toggle-a11y-feature"]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleFeature(btn.getAttribute('data-feature'));
            });
        });

        // Reset button.
        var resetBtn = panel.querySelector('[data-action="reset-a11y"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                resetAll();
            });
        }

        // Keyboard handling.
        document.addEventListener('keydown', handleKeydown);

        // Close panel on click outside.
        document.addEventListener('click', function(e) {
            if (!panel.hidden && !container.contains(e.target)) {
                closePanel();
            }
        });
    }

    return {
        init: init
    };
});
