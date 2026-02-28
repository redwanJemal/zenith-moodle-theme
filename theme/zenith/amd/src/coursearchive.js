/**
 * Zenith course archive module.
 *
 * Adds search, category filter, sort, and grid/list view toggle
 * to the course catalog pages. Uses Moodle's core_course_search_courses
 * web service for AJAX search.
 *
 * @module     theme_zenith/coursearchive
 * @copyright  2026 Zenith Theme
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core/ajax', 'core/templates', 'core/notification'], function(Ajax, Templates, Notification) {
    'use strict';

    /** @type {string} localStorage key for view preference. */
    var VIEW_PREF_KEY = 'theme_zenith_archive_view';

    /** @type {number} Search debounce delay in ms. */
    var DEBOUNCE_DELAY = 300;

    /** @type {number|null} Debounce timer ID. */
    var debounceTimer = null;

    /** @type {string} Current search query. */
    var currentSearch = '';

    /** @type {number} Current category filter (0 = all). */
    var currentCategory = 0;

    /** @type {string} Current sort mode. */
    var currentSort = 'default';

    /** @type {boolean} Whether an AJAX request is in progress. */
    var isLoading = false;

    /** @type {Element|null} The course grid container. */
    var gridEl = null;

    /** @type {Element|null} The archive wrapper. */
    var wrapperEl = null;

    /**
     * Debounce a function call.
     *
     * @param {Function} fn Function to call.
     * @param {number} delay Delay in ms.
     */
    function debounce(fn, delay) {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }
        debounceTimer = setTimeout(fn, delay);
    }

    /**
     * Show the loading spinner.
     */
    function showLoading() {
        if (!gridEl) {
            return;
        }
        isLoading = true;
        gridEl.innerHTML = '<div class="z-archive-loading"><div class="z-archive-loading__spinner"></div></div>';
        gridEl.classList.remove('z-coursecards--archive');
    }

    /**
     * Restore grid classes after loading.
     */
    function restoreGridClasses() {
        if (!gridEl) {
            return;
        }
        gridEl.classList.add('z-coursecards--archive');
        // Re-apply view preference.
        var view = getViewPreference();
        if (view === 'list') {
            gridEl.classList.add('z-coursecards--list');
        } else {
            gridEl.classList.remove('z-coursecards--list');
        }
    }

    /**
     * Get the saved view preference.
     *
     * @return {string} 'grid' or 'list'
     */
    function getViewPreference() {
        try {
            return localStorage.getItem(VIEW_PREF_KEY) || 'grid';
        } catch (e) {
            return 'grid';
        }
    }

    /**
     * Save the view preference.
     *
     * @param {string} view 'grid' or 'list'
     */
    function setViewPreference(view) {
        try {
            localStorage.setItem(VIEW_PREF_KEY, view);
        } catch (e) {
            // Ignore.
        }
    }

    /**
     * Perform an AJAX course search.
     */
    function doSearch() {
        if (isLoading) {
            return;
        }

        var query = currentSearch.trim();
        if (!query && currentCategory === 0 && currentSort === 'default') {
            // No filters active — restore original page content by reloading.
            window.location.reload();
            return;
        }

        showLoading();

        // Build search criteria.
        var criteriakey = 'search';
        var criteriavalue = query || '';

        // If filtering by category without search, search with empty string.
        if (!query) {
            criteriavalue = '';
        }

        var requests = [{
            methodname: 'core_course_search_courses',
            args: {
                criterianame: criteriakey,
                criteriavalue: criteriavalue,
                page: 0,
                perpage: 100,
                requiredcapabilities: [],
                limittoenrolled: 0,
                onlywithcompletion: 0
            }
        }];

        Ajax.call(requests)[0].then(function(result) {
            isLoading = false;
            var courses = result.courses || [];

            // Client-side category filter.
            if (currentCategory > 0) {
                courses = courses.filter(function(course) {
                    return course.categoryid === currentCategory;
                });
            }

            // Client-side sort.
            courses = sortCourses(courses, currentSort);

            // Update count.
            updateCount(courses.length);

            if (courses.length === 0) {
                return renderEmpty();
            }

            return renderCards(courses);
        }).catch(function(err) {
            isLoading = false;
            Notification.exception(err);
            restoreGridClasses();
        });
    }

    /**
     * Sort courses array.
     *
     * @param {Array} courses
     * @param {string} mode Sort mode
     * @return {Array} Sorted courses
     */
    function sortCourses(courses, mode) {
        switch (mode) {
            case 'az':
                return courses.sort(function(a, b) {
                    return a.fullname.localeCompare(b.fullname);
                });
            case 'za':
                return courses.sort(function(a, b) {
                    return b.fullname.localeCompare(a.fullname);
                });
            case 'newest':
                return courses.sort(function(a, b) {
                    return (b.timecreated || 0) - (a.timecreated || 0);
                });
            default:
                return courses;
        }
    }

    /**
     * Render course cards from AJAX results.
     *
     * @param {Array} courses
     * @return {Promise}
     */
    function renderCards(courses) {
        var promises = courses.map(function(course) {
            var context = {
                id: course.id,
                fullname: course.fullname,
                viewurl: M.cfg.wwwroot + '/course/view.php?id=' + course.id,
                courseimage: getImageFromCourse(course),
                categoryname: course.categoryname || '',
                instructors: getContactsFromCourse(course),
                visible: course.visible !== 0
            };
            return Templates.render('theme_zenith/coursearchive_card', context);
        });

        return Promise.all(promises).then(function(htmlFragments) {
            if (gridEl) {
                gridEl.innerHTML = htmlFragments.join('');
                restoreGridClasses();
            }
        });
    }

    /**
     * Render the empty state.
     *
     * @return {Promise}
     */
    function renderEmpty() {
        return Templates.render('theme_zenith/coursearchive_empty', {}).then(function(html) {
            if (gridEl) {
                gridEl.innerHTML = html;
                gridEl.classList.remove('z-coursecards--archive');
            }
        });
    }

    /**
     * Extract image URL from course API response.
     *
     * @param {Object} course
     * @return {string} Image URL
     */
    function getImageFromCourse(course) {
        if (course.courseimage) {
            return course.courseimage;
        }
        if (course.overviewfiles && course.overviewfiles.length > 0) {
            for (var i = 0; i < course.overviewfiles.length; i++) {
                var file = course.overviewfiles[i];
                if (file.mimetype && file.mimetype.indexOf('image') === 0) {
                    return file.fileurl;
                }
            }
        }
        // Fallback placeholder.
        return 'data:image/svg+xml,' + encodeURIComponent(
            '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>'
        );
    }

    /**
     * Extract instructor names from course API response.
     *
     * @param {Object} course
     * @return {string} Instructor names
     */
    function getContactsFromCourse(course) {
        if (course.contacts && course.contacts.length > 0) {
            return course.contacts.map(function(c) {
                return c.fullname;
            }).join(', ');
        }
        return '';
    }

    /**
     * Update the course count badge.
     *
     * @param {number} count
     */
    function updateCount(count) {
        var countEl = document.querySelector('[data-region="archive-count"]');
        if (countEl) {
            countEl.textContent = count + ' courses';
        }
    }

    /**
     * Set up search input handler.
     */
    function initSearch() {
        var input = document.querySelector('[data-action="archive-search"]');
        if (!input) {
            return;
        }

        input.addEventListener('input', function() {
            currentSearch = input.value;
            debounce(doSearch, DEBOUNCE_DELAY);
        });
    }

    /**
     * Set up category filter dropdown.
     */
    function initFilter() {
        var menu = document.querySelector('[data-region="archive-filter-menu"]');
        if (!menu) {
            return;
        }

        menu.addEventListener('click', function(e) {
            var item = e.target.closest('[data-categoryid]');
            if (!item) {
                return;
            }
            e.preventDefault();

            // Update active state.
            menu.querySelectorAll('.dropdown-item').forEach(function(el) {
                el.classList.remove('active');
            });
            item.classList.add('active');

            // Update button text.
            var btn = document.querySelector('[data-action="archive-filter-btn"]');
            if (btn) {
                var icon = btn.querySelector('i');
                var iconHtml = icon ? icon.outerHTML + ' ' : '';
                btn.innerHTML = iconHtml + item.textContent.trim();
            }

            currentCategory = parseInt(item.getAttribute('data-categoryid'), 10);
            doSearch();
        });
    }

    /**
     * Set up sort dropdown.
     */
    function initSort() {
        var menu = document.querySelector('[data-region="archive-sort-menu"]');
        if (!menu) {
            return;
        }

        menu.addEventListener('click', function(e) {
            var item = e.target.closest('[data-sort]');
            if (!item) {
                return;
            }
            e.preventDefault();

            // Update active state.
            menu.querySelectorAll('.dropdown-item').forEach(function(el) {
                el.classList.remove('active');
            });
            item.classList.add('active');

            // Update button text.
            var btn = document.querySelector('[data-action="archive-sort-btn"]');
            if (btn) {
                var icon = btn.querySelector('i');
                var iconHtml = icon ? icon.outerHTML + ' ' : '';
                btn.innerHTML = iconHtml + item.textContent.trim();
            }

            currentSort = item.getAttribute('data-sort');
            doSearch();
        });
    }

    /**
     * Set up grid/list view toggle.
     */
    function initViewToggle() {
        var toggleBtns = document.querySelectorAll('[data-action="archive-view"]');
        if (!toggleBtns.length) {
            return;
        }

        // Apply saved preference.
        var savedView = getViewPreference();
        applyView(savedView, toggleBtns);

        toggleBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var view = btn.getAttribute('data-view');
                setViewPreference(view);
                applyView(view, toggleBtns);
            });
        });
    }

    /**
     * Apply view mode to grid and toggle buttons.
     *
     * @param {string} view 'grid' or 'list'
     * @param {NodeList} toggleBtns
     */
    function applyView(view, toggleBtns) {
        if (gridEl) {
            if (view === 'list') {
                gridEl.classList.add('z-coursecards--list');
            } else {
                gridEl.classList.remove('z-coursecards--list');
            }
        }

        toggleBtns.forEach(function(btn) {
            if (btn.getAttribute('data-view') === view) {
                btn.classList.add('z-archive-view-toggle__btn--active');
                btn.setAttribute('aria-pressed', 'true');
            } else {
                btn.classList.remove('z-archive-view-toggle__btn--active');
                btn.setAttribute('aria-pressed', 'false');
            }
        });
    }

    /**
     * Initialize the course archive module.
     */
    function init() {
        wrapperEl = document.querySelector('[data-region="course-archive"]');
        gridEl = document.querySelector('[data-region="course-archive-grid"]');

        if (!wrapperEl || !gridEl) {
            return;
        }

        initSearch();
        initFilter();
        initSort();
        initViewToggle();
    }

    return {
        init: init
    };
});
