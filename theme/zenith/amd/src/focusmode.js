/**
 * Focus mode — distraction-free course viewing.
 *
 * Hides navbar, drawers, footer, and breadcrumbs. Shows a minimal top bar
 * with exit button, course name, and section navigation.
 *
 * @module     theme_zenith/focusmode
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core_user/repository'], function(UserRepository) {
    'use strict';

    /** @type {string} User preference key. */
    var PREF_KEY = 'theme_zenith_focusmode';

    /** @type {string} Data attribute on <html>. */
    var DATA_ATTR = 'data-focusmode';

    /** @type {string} Toggle button selector. */
    var TOGGLE_SELECTOR = '[data-action="toggle-focusmode"]';

    /** @type {HTMLElement|null} The minimal focus bar element. */
    var focusBar = null;

    /** @type {Object} Strings passed from init config. */
    var strings = {};

    /**
     * Check if focus mode is active.
     *
     * @return {boolean}
     */
    function isActive() {
        return document.documentElement.getAttribute(DATA_ATTR) === 'true';
    }

    /**
     * Enter focus mode.
     */
    function enter() {
        document.documentElement.setAttribute(DATA_ATTR, 'true');
        savePref(true);
        updateToggleState(true);
        showFocusBar();
        // Close drawers.
        closeDrawers();
    }

    /**
     * Exit focus mode.
     */
    function exit() {
        document.documentElement.setAttribute(DATA_ATTR, 'false');
        savePref(false);
        updateToggleState(false);
        hideFocusBar();
    }

    /**
     * Toggle focus mode.
     */
    function toggle() {
        if (isActive()) {
            exit();
        } else {
            enter();
        }
    }

    /**
     * Save preference to Moodle and localStorage.
     *
     * @param {boolean} active Whether focus mode is on.
     */
    function savePref(active) {
        try {
            localStorage.setItem(PREF_KEY, active ? '1' : '0');
        } catch (e) {
            // Ignore.
        }
        UserRepository.setUserPreference(PREF_KEY, active ? '1' : '0');
    }

    /**
     * Update all toggle button states.
     *
     * @param {boolean} active Whether focus mode is on.
     */
    function updateToggleState(active) {
        document.querySelectorAll(TOGGLE_SELECTOR).forEach(function(btn) {
            var iconExpand = btn.querySelector('.z-focusmode__icon--enter');
            var iconContract = btn.querySelector('.z-focusmode__icon--exit');
            if (iconExpand && iconContract) {
                iconExpand.style.display = active ? 'none' : 'block';
                iconContract.style.display = active ? 'block' : 'none';
            }
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }

    /**
     * Close any open drawers (course index, blocks).
     */
    function closeDrawers() {
        var page = document.getElementById('page');
        if (page) {
            page.classList.remove('show-drawer-left', 'show-drawer-right');
        }
        document.querySelectorAll('.drawer.show').forEach(function(drawer) {
            drawer.classList.remove('show');
        });
    }

    /**
     * Build and show the minimal focus mode top bar.
     */
    function showFocusBar() {
        if (focusBar) {
            focusBar.style.display = '';
            return;
        }

        focusBar = document.createElement('div');
        focusBar.className = 'z-focusbar';
        focusBar.setAttribute('role', 'navigation');
        focusBar.setAttribute('aria-label', strings.focusmode || 'Focus mode');

        // Exit button.
        var exitBtn = document.createElement('button');
        exitBtn.className = 'z-focusbar__exit btn btn-sm';
        exitBtn.type = 'button';
        exitBtn.setAttribute('aria-label', strings.exit || 'Exit focus mode');
        exitBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
            'stroke-width="2" stroke-linecap="round" aria-hidden="true">' +
            '<path d="M18 6L6 18M6 6l12 12"/></svg>' +
            '<span>' + (strings.exit || 'Exit') + '</span>';
        exitBtn.addEventListener('click', exit);

        // Course name.
        var courseTitle = document.createElement('div');
        courseTitle.className = 'z-focusbar__title';
        var pageHeader = document.querySelector('.page-header-headings h1, #page-header h1');
        courseTitle.textContent = pageHeader ? pageHeader.textContent.trim() : '';

        // Section progress.
        var progress = buildSectionNav();

        focusBar.appendChild(exitBtn);
        focusBar.appendChild(courseTitle);
        if (progress) {
            focusBar.appendChild(progress);
        }

        document.body.insertBefore(focusBar, document.body.firstChild);
    }

    /**
     * Hide the focus bar.
     */
    function hideFocusBar() {
        if (focusBar) {
            focusBar.style.display = 'none';
        }
    }

    /**
     * Build section navigation (Previous / X of Y / Next).
     *
     * @return {HTMLElement|null} Section nav element or null if not on a course page.
     */
    function buildSectionNav() {
        var sections = document.querySelectorAll('[data-region="section"]');
        if (sections.length < 2) {
            // Also try activity_navigation (incourse pages).
            var activityNav = document.querySelector('.activity-navigation');
            if (activityNav) {
                return buildActivityNav(activityNav);
            }
            return null;
        }

        var nav = document.createElement('div');
        nav.className = 'z-focusbar__nav';

        var prevBtn = document.createElement('button');
        prevBtn.className = 'z-focusbar__nav-btn btn btn-sm';
        prevBtn.type = 'button';
        prevBtn.textContent = strings.prev || 'Previous';
        prevBtn.addEventListener('click', function() {
            scrollToSection(-1, sections);
        });

        var nextBtn = document.createElement('button');
        nextBtn.className = 'z-focusbar__nav-btn btn btn-sm';
        nextBtn.type = 'button';
        nextBtn.textContent = strings.next || 'Next';
        nextBtn.addEventListener('click', function() {
            scrollToSection(1, sections);
        });

        var counter = document.createElement('span');
        counter.className = 'z-focusbar__counter';
        counter.textContent = '1 / ' + sections.length;
        counter.setAttribute('data-focusbar-counter', '');

        nav.appendChild(prevBtn);
        nav.appendChild(counter);
        nav.appendChild(nextBtn);
        return nav;
    }

    /**
     * Build navigation from existing activity navigation (incourse pages).
     *
     * @param {HTMLElement} activityNav The existing .activity-navigation element.
     * @return {HTMLElement} Nav element.
     */
    function buildActivityNav(activityNav) {
        var nav = document.createElement('div');
        nav.className = 'z-focusbar__nav';

        var prevLink = activityNav.querySelector('.prevactivity a');
        var nextLink = activityNav.querySelector('.nextactivity a');

        if (prevLink) {
            var prevBtn = document.createElement('a');
            prevBtn.className = 'z-focusbar__nav-btn btn btn-sm';
            prevBtn.href = prevLink.href;
            prevBtn.textContent = strings.prev || 'Previous';
            nav.appendChild(prevBtn);
        }

        if (nextLink) {
            var nextBtn = document.createElement('a');
            nextBtn.className = 'z-focusbar__nav-btn btn btn-sm';
            nextBtn.href = nextLink.href;
            nextBtn.textContent = strings.next || 'Next';
            nav.appendChild(nextBtn);
        }

        return nav;
    }

    /**
     * Scroll to adjacent section.
     *
     * @param {number} direction -1 for prev, 1 for next.
     * @param {NodeList} sections Section elements.
     */
    function scrollToSection(direction, sections) {
        var current = findCurrentSection(sections);
        var target = current + direction;

        if (target < 0 || target >= sections.length) {
            return;
        }

        sections[target].scrollIntoView({behavior: 'smooth', block: 'start'});

        // Update counter.
        var counter = document.querySelector('[data-focusbar-counter]');
        if (counter) {
            counter.textContent = (target + 1) + ' / ' + sections.length;
        }
    }

    /**
     * Find which section is currently most visible in the viewport.
     *
     * @param {NodeList} sections Section elements.
     * @return {number} Index of the current section.
     */
    function findCurrentSection(sections) {
        var viewportMiddle = window.innerHeight / 2;
        var closest = 0;
        var closestDistance = Infinity;

        sections.forEach(function(section, i) {
            var rect = section.getBoundingClientRect();
            var distance = Math.abs(rect.top - viewportMiddle);
            if (distance < closestDistance) {
                closestDistance = distance;
                closest = i;
            }
        });

        return closest;
    }

    /**
     * Initialise focus mode.
     *
     * @param {Object} config Configuration.
     * @param {Object} config.strings Localised strings.
     */
    function init(config) {
        strings = (config && config.strings) || {};

        // Attach toggle handlers.
        document.querySelectorAll(TOGGLE_SELECTOR).forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                toggle();
            });
        });

        // Escape key exits focus mode.
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isActive()) {
                exit();
            }
        });

        // Restore state from localStorage (immediate, before pref loads).
        try {
            var saved = localStorage.getItem(PREF_KEY);
            if (saved === '1') {
                enter();
            }
        } catch (e) {
            // Ignore.
        }

        // Sync icons.
        updateToggleState(isActive());
    }

    return {
        init: init
    };
});
