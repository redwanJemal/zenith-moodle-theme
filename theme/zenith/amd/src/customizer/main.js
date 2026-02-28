/**
 * Main customizer panel controller.
 *
 * Handles panel open/close, section tabs, input binding, AJAX save/cancel/reset,
 * and focus trap.
 *
 * @module     theme_zenith/customizer/main
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([
    'theme_zenith/customizer/preview',
    'theme_zenith/customizer/presets',
    'theme_zenith/customizer/smartcolor',
    'core/ajax',
    'core/notification'
], function(Preview, Presets, SmartColor, Ajax, Notification) {

    /** @type {HTMLElement} The customizer panel element. */
    var panel = null;

    /** @type {Object} Current working values (not yet saved). */
    var currentValues = {};

    /** @type {Object} Saved values (from server on load). */
    var savedValues = {};

    /** @type {boolean} Whether the panel is open. */
    var isOpen = false;

    /**
     * Update the value display label for a range setting.
     *
     * @param {string} key Setting key.
     * @param {string} value Current value.
     */
    var updateValueDisplay = function(key, value) {
        var display = panel.querySelector('[data-display="' + key + '"]');
        if (!display) {
            return;
        }
        var unitMap = {
            font_size: 'px', btn_radius: 'px', btn_padding_y: 'px',
            btn_padding_x: 'px', navbar_height: 'px',
        };
        var unit = unitMap[key] || '';
        display.textContent = value + unit;
    };

    /**
     * Populate all controls with values.
     *
     * @param {Object} values Settings key-value map.
     */
    var populateControls = function(values) {
        Object.keys(values).forEach(function(key) {
            var value = values[key];

            // Color pickers.
            var colorPicker = panel.querySelector('input[type="color"][data-setting="' + key + '"]');
            if (colorPicker) {
                colorPicker.value = value;
            }

            // Hex text inputs.
            var hexInput = panel.querySelector('input[data-setting="' + key + '"][data-type="hex"]');
            if (hexInput) {
                hexInput.value = value;
            }

            // Select dropdowns.
            var select = panel.querySelector('select[data-setting="' + key + '"]');
            if (select) {
                select.value = value;
            }

            // Range sliders.
            var range = panel.querySelector('input[type="range"][data-setting="' + key + '"]');
            if (range) {
                range.value = value;
            }

            // Checkboxes.
            var checkbox = panel.querySelector('input[type="checkbox"][data-setting="' + key + '"]');
            if (checkbox) {
                checkbox.checked = value === '1' || value === true;
            }

            // Value displays.
            updateValueDisplay(key, value);
        });
    };

    /**
     * Open the customizer panel.
     */
    var open = function() {
        if (isOpen) {
            return;
        }
        isOpen = true;
        Preview.takeSnapshot();
        currentValues = JSON.parse(JSON.stringify(savedValues));
        populateControls(currentValues);
        panel.classList.add('z-customizer--open');
        panel.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        // Focus first tab.
        var firstTab = panel.querySelector('.z-customizer__tab');
        if (firstTab) {
            firstTab.focus();
        }
    };

    /**
     * Close the customizer panel.
     *
     * @param {boolean} revertChanges Whether to revert preview changes.
     */
    var close = function(revertChanges) {
        if (typeof revertChanges === 'undefined') {
            revertChanges = true;
        }
        if (!isOpen) {
            return;
        }
        isOpen = false;
        if (revertChanges) {
            Preview.revert();
        }
        panel.classList.remove('z-customizer--open');
        panel.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        // Return focus to launch button.
        var launchBtn = document.querySelector('[data-action="customizer-open"]');
        if (launchBtn) {
            launchBtn.focus();
        }
    };

    /**
     * Show a toast notification.
     *
     * @param {string} message Toast message.
     */
    var showToast = function(message) {
        var toast = document.querySelector('.z-customizer__toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'z-customizer__toast';
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        // Force reflow.
        void toast.offsetWidth;
        toast.classList.add('z-customizer__toast--show');

        setTimeout(function() {
            toast.classList.remove('z-customizer__toast--show');
        }, 2500);
    };

    /**
     * Save settings via AJAX.
     */
    var saveSettings = function() {
        var settings = Object.keys(currentValues).map(function(key) {
            return {key: key, value: currentValues[key]};
        });

        var saveBtn = panel.querySelector('[data-action="customizer-save"]');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.textContent = '...';
        }

        var resetBtn = function() {
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.textContent = M.util.get_string('customizer_save', 'theme_zenith');
            }
        };

        Ajax.call([{
            methodname: 'theme_zenith_customizer_save',
            args: {settings: settings},
        }])[0]
            .then(function(result) {
                if (result.success) {
                    savedValues = JSON.parse(JSON.stringify(currentValues));
                    showToast(M.util.get_string('customizer_saved', 'theme_zenith'));
                    close(false); // Don't revert — values are now saved.
                }
                resetBtn();
                return result;
            })
            .catch(function(err) {
                resetBtn();
                Notification.exception(err);
            });
    };

    /**
     * Reset settings to defaults via AJAX.
     */
    var resetSettings = function() {
        if (!window.confirm(M.util.get_string('customizer_reset_confirm', 'theme_zenith'))) {
            return;
        }

        Ajax.call([{
            methodname: 'theme_zenith_customizer_reset',
            args: {},
        }])[0]
            .then(function(result) {
                if (result.success) {
                    showToast(M.util.get_string('customizer_reset_done', 'theme_zenith'));
                    window.location.reload();
                }
                return result;
            })
            .catch(Notification.exception);
    };

    /**
     * Bind the launch button click.
     */
    var bindLaunchButton = function() {
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-action="customizer-open"]');
            if (btn) {
                e.preventDefault();
                open();
            }
        });
    };

    /**
     * Bind close buttons and backdrop click.
     */
    var bindCloseButtons = function() {
        panel.addEventListener('click', function(e) {
            var closeBtn = e.target.closest('[data-action="customizer-close"]');
            if (closeBtn) {
                e.preventDefault();
                close(true);
            }
        });
    };

    /**
     * Bind section tab switching.
     */
    var bindTabs = function() {
        var tabContainer = panel.querySelector('.z-customizer__tabs');
        if (!tabContainer) {
            return;
        }
        tabContainer.addEventListener('click', function(e) {
            var tab = e.target.closest('.z-customizer__tab');
            if (!tab) {
                return;
            }

            var section = tab.dataset.section;

            // Update tab states.
            tabContainer.querySelectorAll('.z-customizer__tab').forEach(function(t) {
                t.classList.remove('z-customizer__tab--active');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('z-customizer__tab--active');
            tab.setAttribute('aria-selected', 'true');

            // Show matching section.
            panel.querySelectorAll('.z-customizer__section').forEach(function(s) {
                s.classList.remove('z-customizer__section--active');
                s.hidden = true;
            });
            var targetSection = panel.querySelector('#z-cust-section-' + section);
            if (targetSection) {
                targetSection.classList.add('z-customizer__section--active');
                targetSection.hidden = false;
            }
        });
    };

    /**
     * Bind all input controls for live preview.
     */
    var bindInputs = function() {
        // Color pickers and range sliders.
        panel.addEventListener('input', function(e) {
            var el = e.target;

            if (el.matches('input[type="color"][data-setting]')) {
                var key = el.dataset.setting;
                var value = el.value;
                currentValues[key] = value;
                Preview.applySetting(key, value);

                // Sync hex input.
                var hexInput = panel.querySelector('input[data-setting="' + key + '"][data-type="hex"]');
                if (hexInput) {
                    hexInput.value = value;
                }
            }

            // Range sliders.
            if (el.matches('input[type="range"][data-setting]')) {
                var rKey = el.dataset.setting;
                var rValue = el.value;
                currentValues[rKey] = rValue;
                Preview.applySetting(rKey, rValue);
                updateValueDisplay(rKey, rValue);
            }
        });

        // Hex text inputs, selects, checkboxes — apply on change.
        panel.addEventListener('change', function(e) {
            var el = e.target;

            if (el.matches('input[data-type="hex"][data-setting]')) {
                var key = el.dataset.setting;
                var value = el.value;
                if (value.charAt(0) !== '#') {
                    value = '#' + value;
                }
                if (SmartColor.isValidHex(value)) {
                    value = SmartColor.normalizeHex(value);
                    el.value = value;
                    currentValues[key] = value;
                    Preview.applySetting(key, value);

                    // Sync color picker.
                    var colorPicker = panel.querySelector('input[type="color"][data-setting="' + key + '"]');
                    if (colorPicker) {
                        colorPicker.value = value;
                    }
                }
            }

            // Select dropdowns.
            if (el.matches('select[data-setting]')) {
                var sKey = el.dataset.setting;
                var sValue = el.value;
                currentValues[sKey] = sValue;
                Preview.applySetting(sKey, sValue);
            }

            // Checkboxes.
            if (el.matches('input[type="checkbox"][data-setting]')) {
                var cKey = el.dataset.setting;
                var cValue = el.checked ? '1' : '0';
                currentValues[cKey] = cValue;
                Preview.applySetting(cKey, cValue);
            }
        });
    };

    /**
     * Bind preset card clicks.
     */
    var bindPresets = function() {
        panel.addEventListener('click', function(e) {
            var card = e.target.closest('[data-action="customizer-preset"]');
            if (!card) {
                return;
            }

            var presetKey = card.dataset.preset;
            var presetValues = Presets.getPreset(presetKey);
            if (!presetValues) {
                return;
            }

            // Update active state.
            panel.querySelectorAll('.z-customizer__preset-card').forEach(function(c) {
                c.classList.remove('z-customizer__preset-card--active');
            });
            card.classList.add('z-customizer__preset-card--active');

            // Apply preset values.
            Object.keys(presetValues).forEach(function(k) {
                currentValues[k] = presetValues[k];
            });
            populateControls(currentValues);
            Preview.applyAll(presetValues);
        });
    };

    /**
     * Bind save, reset, and cancel buttons.
     */
    var bindSaveResetCancel = function() {
        panel.addEventListener('click', function(e) {
            var action = e.target.closest('[data-action]');
            if (!action) {
                return;
            }

            switch (action.dataset.action) {
                case 'customizer-save':
                    saveSettings();
                    break;
                case 'customizer-reset':
                    resetSettings();
                    break;
                case 'customizer-cancel':
                    close(true);
                    break;
            }
        });
    };

    /**
     * Bind keyboard events (Escape to close, focus trap).
     */
    var bindKeyboard = function() {
        document.addEventListener('keydown', function(e) {
            if (!isOpen) {
                return;
            }

            if (e.key === 'Escape') {
                e.preventDefault();
                close(true);
                return;
            }

            // Focus trap.
            if (e.key === 'Tab') {
                var focusable = panel.querySelectorAll(
                    'button:not([disabled]), input:not([disabled]), select:not([disabled]), ' +
                    '[tabindex]:not([tabindex="-1"])'
                );
                if (focusable.length === 0) {
                    return;
                }
                var first = focusable[0];
                var last = focusable[focusable.length - 1];

                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });
    };

    /**
     * Initialize the customizer.
     *
     * @param {Object} config Initial settings from PHP.
     * @param {Object} config.settings Current setting values.
     */
    var init = function(config) {
        panel = document.getElementById('z-customizer');
        if (!panel) {
            return;
        }

        savedValues = JSON.parse(JSON.stringify(config.settings));
        currentValues = JSON.parse(JSON.stringify(config.settings));

        // Set initial values on all controls.
        populateControls(currentValues);

        // Bind event listeners.
        bindLaunchButton();
        bindCloseButtons();
        bindTabs();
        bindInputs();
        bindPresets();
        bindSaveResetCancel();
        bindKeyboard();
    };

    return {
        init: init,
    };
});
