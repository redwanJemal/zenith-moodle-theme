/**
 * Setup wizard controller — 5-step guided first-time setup.
 *
 * @module     theme_zenith/setupwizard
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core/ajax', 'core/notification'], function(Ajax, Notification) {

    /** @type {HTMLElement} Root wizard element. */
    var wizard = null;

    /** @type {number} Current step index (0-4). */
    var currentStep = 0;

    /** @type {number} Total steps. */
    var TOTAL_STEPS = 5;

    /** @type {string[]} Step names matching PHP. */
    var STEP_NAMES = ['welcome', 'branding', 'colors', 'homepage', 'complete'];

    /** @type {string[]} Button labels per step. */
    var NEXT_LABELS = [];

    /** @type {string|null} Selected preset key. */
    var selectedPreset = null;

    /** @type {Object} Preset data from PHP. */
    var presetData = {};

    /**
     * Initialise the wizard.
     *
     * @param {Object} config Configuration from PHP.
     * @param {Object} config.strings Localised string map.
     * @param {Array}  config.presets Preset card data.
     */
    var init = function(config) {
        wizard = document.getElementById('z-wizard');
        if (!wizard) {
            return;
        }

        NEXT_LABELS = [
            config.strings.get_started || 'Get started',
            config.strings.next || 'Next',
            config.strings.next || 'Next',
            config.strings.finish || 'Finish',
            '', // Complete step — no next button.
        ];

        // Build preset lookup from config.
        if (config.presets) {
            config.presets.forEach(function(p) {
                presetData[p.key] = p;
            });
        }

        bindEvents();
        showStep(0);
    };

    /**
     * Bind all wizard event listeners.
     */
    var bindEvents = function() {
        // Next button.
        var btnNext = wizard.querySelector('[data-action="wizard-next"]');
        if (btnNext) {
            btnNext.addEventListener('click', handleNext);
        }

        // Back button.
        var btnBack = wizard.querySelector('[data-action="wizard-back"]');
        if (btnBack) {
            btnBack.addEventListener('click', handleBack);
        }

        // Skip/close button.
        var btnSkip = wizard.querySelector('[data-action="wizard-skip"]');
        if (btnSkip) {
            btnSkip.addEventListener('click', handleSkip);
        }

        // Preset card selection.
        var presetCards = wizard.querySelectorAll('[data-preset]');
        presetCards.forEach(function(card) {
            card.addEventListener('click', function() {
                selectPreset(card.getAttribute('data-preset'));
            });
        });

        // Keyboard: Escape to skip.
        wizard.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                handleSkip();
            }
        });
    };

    /**
     * Show a specific step by index.
     *
     * @param {number} stepIndex Step index (0-4).
     */
    var showStep = function(stepIndex) {
        var steps = wizard.querySelectorAll('[data-wizard-step]');
        var dots = wizard.querySelectorAll('.z-wizard__dot');

        steps.forEach(function(step, i) {
            if (i === stepIndex) {
                step.classList.add('z-wizard__step--active');
            } else {
                step.classList.remove('z-wizard__step--active');
            }
        });

        // Update progress dots.
        dots.forEach(function(dot, i) {
            dot.classList.toggle('z-wizard__dot--active', i === stepIndex);
            dot.classList.toggle('z-wizard__dot--completed', i < stepIndex);
        });

        // Update progressbar.
        var progressbar = wizard.querySelector('[role="progressbar"]');
        if (progressbar) {
            progressbar.setAttribute('aria-valuenow', stepIndex + 1);
        }

        // Update button visibility and labels.
        var btnBack = wizard.querySelector('[data-action="wizard-back"]');
        var btnNext = wizard.querySelector('[data-action="wizard-next"]');
        var btnSkip = wizard.querySelector('[data-action="wizard-skip"]');

        if (btnBack) {
            btnBack.style.visibility = stepIndex > 0 && stepIndex < TOTAL_STEPS - 1 ? 'visible' : 'hidden';
        }

        if (btnNext) {
            if (stepIndex === TOTAL_STEPS - 1) {
                // Complete step — hide next button, links handle navigation.
                btnNext.style.display = 'none';
            } else {
                btnNext.style.display = '';
                btnNext.textContent = NEXT_LABELS[stepIndex] || 'Next';
            }
        }

        // Hide skip on complete step.
        if (btnSkip) {
            btnSkip.style.display = stepIndex === TOTAL_STEPS - 1 ? 'none' : '';
        }

        // Hide actions row on complete step.
        var actions = wizard.querySelector('.z-wizard__actions');
        if (actions) {
            actions.style.display = stepIndex === TOTAL_STEPS - 1 ? 'none' : '';
        }

        currentStep = stepIndex;

        // Trigger celebration on complete step.
        if (stepIndex === TOTAL_STEPS - 1) {
            showCelebration();
        }
    };

    /**
     * Handle next button click.
     */
    var handleNext = function() {
        if (currentStep >= TOTAL_STEPS - 1) {
            return;
        }

        // Save step data before advancing.
        saveCurrentStep(function() {
            showStep(currentStep + 1);
        });
    };

    /**
     * Handle back button click.
     */
    var handleBack = function() {
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    };

    /**
     * Handle skip/dismiss.
     */
    var handleSkip = function() {
        completeWizard(function() {
            closeWizard();
        });
    };

    /**
     * Save the current step's settings via AJAX.
     *
     * @param {Function} callback Called on success.
     */
    var saveCurrentStep = function(callback) {
        var stepName = STEP_NAMES[currentStep];

        if (stepName === 'colors' && selectedPreset) {
            saveColorStep(callback);
        } else if (stepName === 'homepage') {
            saveHomepageStep(callback);
        } else if (stepName === 'complete' || stepName === 'welcome' || stepName === 'branding') {
            // Complete step: mark wizard done.
            if (stepName === 'complete') {
                completeWizard(callback);
            } else {
                // No data to save for welcome/branding.
                callback();
            }
        } else {
            callback();
        }
    };

    /**
     * Save selected preset colors.
     *
     * @param {Function} callback Called on success.
     */
    var saveColorStep = function(callback) {
        // We need the full flat preset data. The presets in config only have 4 colors (display).
        // We send the preset key and let the server fetch the full preset.
        // Actually, the plan says to use customizer::save(), so we need the full flat values.
        // We'll use the preset key and send all values from the JS presets module.
        requirePresetValues(selectedPreset, function(values) {
            var settings = [];
            Object.keys(values).forEach(function(key) {
                settings.push({key: key, value: values[key]});
            });

            Ajax.call([{
                methodname: 'theme_zenith_wizard_save_step',
                args: {step: 'colors', settings: settings},
            }])[0].then(function() {
                callback();
                return;
            }).catch(Notification.exception);
        });
    };

    /**
     * Get full preset values. Loads the presets module to get all values.
     *
     * @param {string} presetKey Preset key.
     * @param {Function} callback Called with values object.
     */
    var requirePresetValues = function(presetKey, callback) {
        require(['theme_zenith/customizer/presets'], function(Presets) {
            var values = Presets.getPreset(presetKey);
            if (values) {
                callback(values);
            } else {
                callback({});
            }
        });
    };

    /**
     * Save homepage step settings.
     *
     * @param {Function} callback Called on success.
     */
    var saveHomepageStep = function(callback) {
        var settings = [];
        var heroEnabled = wizard.querySelector('[data-setting="heroenabled"]');
        var heroTitle = wizard.querySelector('[data-setting="herotitle"]');
        var heroSubtitle = wizard.querySelector('[data-setting="herosubtitle"]');

        if (heroEnabled) {
            settings.push({key: 'heroenabled', value: heroEnabled.checked ? '1' : '0'});
        }
        if (heroTitle) {
            settings.push({key: 'herotitle', value: heroTitle.value});
        }
        if (heroSubtitle) {
            settings.push({key: 'herosubtitle', value: heroSubtitle.value});
        }

        Ajax.call([{
            methodname: 'theme_zenith_wizard_save_step',
            args: {step: 'homepage', settings: settings},
        }])[0].then(function() {
            buildSummary();
            callback();
            return;
        }).catch(Notification.exception);
    };

    /**
     * Mark wizard as complete via AJAX.
     *
     * @param {Function} callback Called on success.
     */
    var completeWizard = function(callback) {
        Ajax.call([{
            methodname: 'theme_zenith_wizard_complete',
            args: {},
        }])[0].then(function() {
            if (callback) {
                callback();
            }
            return;
        }).catch(Notification.exception);
    };

    /**
     * Select a preset card.
     *
     * @param {string} presetKey Preset key.
     */
    var selectPreset = function(presetKey) {
        selectedPreset = presetKey;

        var cards = wizard.querySelectorAll('[data-preset]');
        cards.forEach(function(card) {
            var isSelected = card.getAttribute('data-preset') === presetKey;
            card.classList.toggle('z-wizard__preset-card--selected', isSelected);
            card.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
        });
    };

    /**
     * Build a summary of choices for the complete step.
     */
    var buildSummary = function() {
        var summary = wizard.querySelector('#z-wizard-summary');
        if (!summary) {
            return;
        }

        var items = [];
        if (selectedPreset && presetData[selectedPreset]) {
            items.push('<div class="z-wizard__summary-item">' +
                '<span class="z-wizard__summary-label">Colour preset:</span> ' +
                '<span class="z-wizard__summary-value">' + presetData[selectedPreset].name + '</span>' +
                '</div>');
        }

        var heroTitle = wizard.querySelector('[data-setting="herotitle"]');
        if (heroTitle && heroTitle.value) {
            items.push('<div class="z-wizard__summary-item">' +
                '<span class="z-wizard__summary-label">Hero title:</span> ' +
                '<span class="z-wizard__summary-value">' + escapeHtml(heroTitle.value) + '</span>' +
                '</div>');
        }

        summary.innerHTML = items.join('');
    };

    /**
     * Escape HTML entities.
     *
     * @param {string} text Raw text.
     * @return {string} Escaped text.
     */
    var escapeHtml = function(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    };

    /**
     * Show celebration animation on the complete step.
     */
    var showCelebration = function() {
        var celebration = wizard.querySelector('.z-wizard__celebration');
        if (celebration) {
            celebration.classList.add('z-wizard__celebration--animate');
        }
    };

    /**
     * Close and remove the wizard overlay.
     */
    var closeWizard = function() {
        if (wizard) {
            wizard.classList.add('z-wizard--closing');
            setTimeout(function() {
                wizard.remove();
            }, 300);
        }
    };

    return {
        init: init,
    };
});
