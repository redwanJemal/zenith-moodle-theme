/**
 * Zenith enhanced dashboard module.
 *
 * Loads dashboard statistics via AJAX, renders widgets with animated
 * counters, recent courses, and upcoming deadlines.
 *
 * @module     theme_zenith/dashboard
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core/ajax', 'core/notification'], function(Ajax, Notification) {
    'use strict';

    /** @type {Object} Cached string values passed from Mustache. */
    var strings = {};

    /**
     * Animate a number counter using requestAnimationFrame.
     *
     * @param {HTMLElement} el The element to animate.
     * @param {number} target The target number.
     * @param {number} duration Animation duration in ms.
     */
    function animateCounter(el, target, duration) {
        var start = 0;
        var startTime = null;
        var suffix = el.querySelector('[data-stat-suffix]');
        var displayEl = suffix || el;

        function easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function step(timestamp) {
            if (!startTime) {
                startTime = timestamp;
            }
            var elapsed = timestamp - startTime;
            var progress = Math.min(elapsed / duration, 1);
            var current = Math.round(easeOutCubic(progress) * (target - start) + start);

            displayEl.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }

        if (target === 0) {
            displayEl.textContent = '0';
            return;
        }

        requestAnimationFrame(step);
    }

    /**
     * Remove skeleton class from stat elements and animate to values.
     *
     * @param {Object} stats Stats object {enrolled, completed, inprogress, averageprogress}.
     */
    function populateStats(stats) {
        var statElements = document.querySelectorAll('[data-stat]');
        statElements.forEach(function(el) {
            var key = el.getAttribute('data-stat');
            var value = stats[key] || 0;
            el.classList.remove('z-dash__skeleton');
            animateCounter(el, value, 800);
        });
    }

    /**
     * Render recent courses into the courses region.
     *
     * @param {Array} courses Array of course objects.
     */
    function renderRecentCourses(courses) {
        var container = document.querySelector('[data-region="recent-courses"]');
        if (!container) {
            return;
        }

        if (courses.length === 0) {
            container.innerHTML = '<p class="z-dash__no-data">' +
                (strings.no_recent || 'No recent courses') + '</p>';
            return;
        }

        var html = '';
        courses.forEach(function(course) {
            var imageHtml = course.courseimage
                ? '<img class="z-dash__course-img" src="' + course.courseimage +
                  '" alt="" loading="lazy">'
                : '<div class="z-dash__course-img z-dash__course-img--placeholder">' +
                  '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
                  'stroke-width="2" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/>' +
                  '<path d="M6 12v5c0 1.1 2.7 2 6 2s6-.9 6-2v-5"/></svg></div>';

            html += '<a href="' + course.viewurl + '" class="z-dash__course-card">' +
                imageHtml +
                '<div class="z-dash__course-info">' +
                '<h4 class="z-dash__course-name">' + course.fullname + '</h4>' +
                '<div class="z-dash__course-progress">' +
                '<div class="z-dash__progress-bar">' +
                '<div class="z-dash__progress-fill" style="width: ' + course.progress + '%"></div>' +
                '</div>' +
                '<span class="z-dash__progress-text">' + course.progress + '%</span>' +
                '</div>' +
                '</div>' +
                '</a>';
        });

        container.innerHTML = html;
    }

    /**
     * Render upcoming deadlines into the deadlines region.
     *
     * @param {Array} deadlines Array of deadline objects.
     */
    function renderDeadlines(deadlines) {
        var container = document.querySelector('[data-region="deadlines"]');
        if (!container) {
            return;
        }

        if (deadlines.length === 0) {
            container.innerHTML = '<p class="z-dash__no-data">' +
                (strings.no_deadlines || 'No upcoming deadlines') + '</p>';
            return;
        }

        var html = '';
        deadlines.forEach(function(dl) {
            var overdueClass = dl.overdue ? ' z-dash__deadline-item--overdue' : '';
            html += '<div class="z-dash__deadline-item' + overdueClass + '">' +
                '<div class="z-dash__deadline-icon" aria-hidden="true">' +
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
                'stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>' +
                '</svg></div>' +
                '<div class="z-dash__deadline-info">' +
                '<a href="' + dl.activityurl + '" class="z-dash__deadline-name">' + dl.name + '</a>' +
                '<div class="z-dash__deadline-meta">' +
                '<span class="z-dash__deadline-course">' + dl.coursename + '</span>' +
                '<span class="z-dash__deadline-date">' + dl.duedateformatted + '</span>' +
                '</div>' +
                '</div>' +
                '</div>';
        });

        container.innerHTML = html;
    }

    /**
     * Show empty state and hide all widget sections.
     */
    function showEmptyState() {
        var emptyState = document.querySelector('[data-region="empty-state"]');
        if (emptyState) {
            emptyState.style.display = '';
        }
        // Hide stats, recent courses, and deadlines sections.
        var stats = document.querySelector('.z-dash__stats');
        if (stats) {
            stats.style.display = 'none';
        }
        var sections = document.querySelectorAll('.z-dash__section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });
    }

    /**
     * Initialize the dashboard module.
     *
     * @param {Object} config Configuration object with strings.
     */
    function init(config) {
        strings = (config && config.strings) || {};

        // Fire all 3 AJAX calls in parallel.
        var statsCall = Ajax.call([{
            methodname: 'theme_zenith_dashboard_stats',
            args: {},
        }])[0];

        var recentCall = Ajax.call([{
            methodname: 'theme_zenith_dashboard_recent_courses',
            args: {limit: 5},
        }])[0];

        var deadlinesCall = Ajax.call([{
            methodname: 'theme_zenith_dashboard_deadlines',
            args: {days: 7},
        }])[0];

        // Handle stats.
        statsCall.then(function(data) {
            if (data.enrolled === 0) {
                showEmptyState();
            } else {
                populateStats(data);
            }
            return data;
        }).catch(Notification.exception);

        // Handle recent courses.
        recentCall.then(function(data) {
            renderRecentCourses(data.courses || []);
            return data;
        }).catch(Notification.exception);

        // Handle deadlines.
        deadlinesCall.then(function(data) {
            renderDeadlines(data.deadlines || []);
            return data;
        }).catch(Notification.exception);
    }

    return {
        init: init
    };
});
