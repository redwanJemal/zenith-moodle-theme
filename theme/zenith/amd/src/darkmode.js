/**
 * Zenith dark mode toggle module.
 *
 * Handles dark/light mode switching with:
 * - Toggle button in navbar (sun/moon icons)
 * - User preference persistence via Moodle user preferences API
 * - System prefers-color-scheme detection on first visit
 * - Smooth CSS transition on theme switch
 *
 * The dark mode is applied via [data-theme="dark"] on the <html> element,
 * which activates the token overrides in _dark-mode.scss.
 *
 * @module     theme_zenith/darkmode
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core_user/repository'], function(UserRepository) {
    'use strict';

    /** @type {string} Moodle user preference key. */
    var PREF_KEY = 'theme_zenith_darkmode';

    /** @type {string} data attribute on <html> element. */
    var DATA_ATTR = 'data-theme';

    /** @type {string} CSS selector for the toggle button. */
    var TOGGLE_SELECTOR = '[data-action="toggle-darkmode"]';

    /**
     * Check if dark mode is currently active.
     *
     * @return {boolean}
     */
    function isDark() {
        return document.documentElement.getAttribute(DATA_ATTR) === 'dark';
    }

    /**
     * Apply the theme to the document.
     *
     * @param {boolean} dark Whether to apply dark mode.
     */
    function applyTheme(dark) {
        document.documentElement.setAttribute(DATA_ATTR, dark ? 'dark' : 'light');
        // Store in localStorage for anti-flash script on next page load.
        try {
            localStorage.setItem(PREF_KEY, dark ? 'dark' : 'light');
        } catch (e) {
            // localStorage may be unavailable in some contexts.
        }
        updateToggleIcons(dark);
    }

    /**
     * Update toggle button icons (show sun in dark mode, moon in light mode).
     *
     * @param {boolean} dark Current dark mode state.
     */
    function updateToggleIcons(dark) {
        var toggles = document.querySelectorAll(TOGGLE_SELECTOR);
        toggles.forEach(function(toggle) {
            var sunIcon = toggle.querySelector('.z-darkmode__icon--sun');
            var moonIcon = toggle.querySelector('.z-darkmode__icon--moon');
            if (sunIcon && moonIcon) {
                sunIcon.style.display = dark ? 'block' : 'none';
                moonIcon.style.display = dark ? 'none' : 'block';
            }
            toggle.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
        });
    }

    /**
     * Toggle dark mode and save preference.
     */
    function toggle() {
        var newDark = !isDark();
        applyTheme(newDark);

        // Save preference to Moodle user preferences (persists across sessions).
        UserRepository.setUserPreference(PREF_KEY, newDark ? 'dark' : 'light');
    }

    /**
     * Initialize dark mode module.
     */
    function init() {
        // Enable smooth transitions after initial load (prevents flash).
        requestAnimationFrame(function() {
            document.documentElement.classList.add('z-theme-transitions');
        });

        // Update toggle icons to match current state.
        updateToggleIcons(isDark());

        // Attach click handlers to all toggle buttons.
        document.querySelectorAll(TOGGLE_SELECTOR).forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                toggle();
            });
        });

        // Listen for system preference changes.
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                // Only auto-switch if user hasn't explicitly set a preference.
                try {
                    var saved = localStorage.getItem(PREF_KEY);
                    if (!saved) {
                        applyTheme(e.matches);
                    }
                } catch (err) {
                    // Ignore localStorage errors.
                }
            });
        }
    }

    return {
        init: init
    };
});
