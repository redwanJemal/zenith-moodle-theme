# Phase 1: Core Theme Design

> **Timeline:** Weeks 2-4 · **Total Effort:** 74 hours · **Tasks:** 10

---

## P1-1: Design Token System (CSS Custom Properties)
- **Status:** `[ ]`
- **Dependencies:** `P0-3`
- **Effort:** 4 hours
- **Blocks:** P1-2, P1-7, P1-8, P3-4

**Task:** Create the `--z-` prefixed design token system covering colors, spacing, typography, shadows, and radius.

**Files:**
- [ ] `scss/_variables.scss` — All tokens
- [ ] `scss/_dark-mode.scss` — Dark mode token overrides via `[data-theme="dark"]`

**Acceptance Criteria:**
- [ ] All colors defined as `--z-primary`, `--z-secondary`, `--z-bg-page`, etc.
- [ ] Spacing scale: `--z-space-1` (4px) through `--z-space-12` (48px)
- [ ] Typography: `--z-font-sans`, `--z-text-xs` through `--z-text-3xl`
- [ ] Shadows: `--z-shadow-sm` through `--z-shadow-lg`
- [ ] Radius: `--z-radius-sm` through `--z-radius-full`
- [ ] Dark mode flips all semantic tokens via `[data-theme="dark"]`
- [ ] All tokens documented with inline comments

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/preset/remui.scss` (lines 11-250)

---

## P1-2: Selective Bootstrap Import
- **Status:** `[ ]`
- **Dependencies:** `P1-1`
- **Effort:** 6 hours
- **Blocks:** P1-3, P1-4, P1-7

**Task:** Import only the Bootstrap 5 modules Moodle actually needs (~60% reduction).

**Files:**
- [ ] `scss/_bootstrap-selective.scss`

**Acceptance Criteria:**
- [ ] Core: functions, variables, mixins, root, reboot, containers, grid
- [ ] Components: buttons, dropdown, nav, navbar, card, modal, tooltip, popover, badge, alert, close, forms, transitions, collapse, offcanvas, breadcrumb, pagination, list-group, progress, spinners, tables
- [ ] Utilities: utilities API
- [ ] SKIP: accordion, carousel, toasts, placeholders, scrollspy, ratios
- [ ] All Moodle core pages render correctly with selective imports
- [ ] CSS output < 150KB for Bootstrap portion (vs ~230KB full)
- [ ] Grid system works at all breakpoints

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/bootstrap/`

---

## P1-3: Navbar Design
- **Status:** `[ ]`
- **Dependencies:** `P1-1`, `P1-2`
- **Effort:** 8 hours
- **Blocks:** P1-5, P1-8, P3-5

**Task:** Design and implement the top navigation bar.

**Files:**
- [ ] `layout/common.php` — Navbar template context preparation
- [ ] `templates/navbar.mustache` — Navbar HTML structure
- [ ] `scss/components/_navbar.scss` — Navbar styling
- [ ] `amd/src/navbar.js` — Mobile toggle, search interaction

**Acceptance Criteria:**
- [ ] Logo + site name (configurable via settings)
- [ ] Primary navigation links (Moodle primary nav)
- [ ] User menu (avatar + dropdown with profile, logout)
- [ ] Language menu (if multiple languages installed)
- [ ] Search toggle (expandable search input)
- [ ] Notifications indicator
- [ ] Messages indicator
- [ ] Responsive: hamburger menu on mobile (≤768px)
- [ ] Smooth open/close animations
- [ ] ARIA labels on all interactive elements
- [ ] Keyboard navigable (Tab, Enter, Escape, Arrow keys)
- [ ] Dark mode compatible

**Screenshots:** Capture navbar in desktop, tablet, mobile viewports

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/navbar.mustache`

---

## P1-4: Footer Design
- **Status:** `[ ]`
- **Dependencies:** `P1-1`, `P1-2`
- **Effort:** 6 hours
- **Blocks:** P1-6, P1-8

**Task:** Configurable multi-column footer with social media and legal links.

**Files:**
- [ ] `templates/footer.mustache`
- [ ] `scss/components/_footer.scss`
- [ ] Footer settings added to `settings.php`

**Acceptance Criteria:**
- [ ] 1-4 configurable columns (admin selects count)
- [ ] Column content: HTML editor per column
- [ ] Social media icons: Facebook, Twitter/X, LinkedIn, YouTube, Instagram, Pinterest, Quora
- [ ] Copyright text (configurable)
- [ ] Back-to-top floating button
- [ ] Legal links: Terms, Privacy Policy (configurable URLs)
- [ ] Responsive: columns stack vertically on mobile
- [ ] Dark mode compatible
- [ ] ARIA landmarks (`<footer role="contentinfo">`)

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/footer.mustache`

---

## P1-5: Drawer System (Left + Right)
- **Status:** `[ ]`
- **Dependencies:** `P1-3`
- **Effort:** 8 hours
- **Blocks:** P1-6, P1-8

**Task:** Course index drawer (left) and block sidebar drawer (right).

**Files:**
- [ ] `templates/drawer.mustache` — Reusable drawer block partial with configurable slots
- [ ] `templates/common_start.mustache` — Page opening: head, navbar, drawers
- [ ] `templates/common_end.mustache` — Page closing: footer, scripts
- [ ] `scss/components/_drawer.scss`
- [ ] `amd/src/drawer.js` — Toggle, animation, preference persistence

**Acceptance Criteria:**
- [ ] Left drawer: course index navigation (when on course pages)
- [ ] Right drawer: page block sidebar
- [ ] Toggle buttons visible in navbar/page area
- [ ] Smooth slide-in/out animations (CSS transitions)
- [ ] Open/closed state persisted via Moodle user preferences
- [ ] Auto-close on mobile when user navigates
- [ ] Overlay background on mobile (click to dismiss)
- [ ] Keyboard accessible (Escape to close, focus trap while open)
- [ ] Data attributes: `data-z-drawer-state`, `data-z-drawer-preference`

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/amd/src/drawer.js`

---

## P1-6: Layout Files (All 12)
- **Status:** `[ ]`
- **Dependencies:** `P1-3`, `P1-4`, `P1-5`
- **Effort:** 12 hours
- **Blocks:** P1-9, P2-1, P3-1, P3-2, P3-3, P3-5, P3-6, P3-7, P4-2, P4-3, P5-2

**Task:** Create all layout PHP files with the common sandwich pattern + matching templates.

**Layout Files:**
- [ ] `layout/common.php` — Shared context: navbar, drawers, fonts, footer, announcements
- [ ] `layout/common_end.php` — Bottom blocks, quick menu, dark mode init, scripts
- [ ] `layout/drawers.php` — Dashboard, admin, standard, mycourses pages
- [ ] `layout/course.php` — Course view (+ focus mode context)
- [ ] `layout/incourse.php` — In-course activity/resource pages
- [ ] `layout/frontpage.php` — Site homepage
- [ ] `layout/category.php` — Course categories/archive
- [ ] `layout/login.php` — Login page (standalone, no common includes)
- [ ] `layout/profile.php` — User profile page
- [ ] `layout/columns1.php` — Minimal single-column (popup, print, frametop)
- [ ] `layout/embedded.php` — Embedded/iframe content
- [ ] `layout/maintenance.php` — Maintenance mode
- [ ] `layout/secure.php` — Safe exam browser

**Matching Templates:**
- [ ] `templates/drawers.mustache`
- [ ] `templates/course.mustache`
- [ ] `templates/incourse.mustache`
- [ ] `templates/frontpage.mustache`
- [ ] `templates/coursearchive.mustache`
- [ ] `templates/columns1.mustache`
- [ ] `templates/embedded.mustache`
- [ ] `templates/maintenance.mustache`
- [ ] `templates/mypublic.mustache`

**Acceptance Criteria:**
- [ ] Every Moodle page type renders without PHP errors
- [ ] Block regions (side-pre, side-top, side-bottom, full-width-top, full-bottom) work
- [ ] Editing mode toggle works on all editable pages
- [ ] Activity header renders in course layouts
- [ ] Mobile responsive on all layouts
- [ ] Common sandwich: `common.php` → page-specific → `common_end.php` → render

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/layout/`

---

## P1-7: Login Page (3 Layouts)
- **Status:** `[ ]`
- **Dependencies:** `P1-1`, `P1-2`
- **Effort:** 6 hours
- **Blocks:** None

**Task:** Beautiful login page with 3 configurable layout options.

**Files:**
- [ ] `layout/login.php`
- [ ] `templates/login.mustache`
- [ ] `scss/components/_login.scss`
- [ ] Login layout setting in `settings.php`

**Acceptance Criteria:**
- [ ] Layout 1: Centered form with background image
- [ ] Layout 2: Left panel form + right side hero image
- [ ] Layout 3: Right panel form + left side hero image
- [ ] Custom background image upload in settings
- [ ] Brand logo display on login form
- [ ] Signup link toggle (configurable show/hide)
- [ ] "Forgot password" link styled consistently
- [ ] Responsive: full-width form on mobile (all layouts)
- [ ] Accessible: proper form labels, focus indicators, error messages
- [ ] Dark mode compatible

**Screenshots:** All 3 layouts × 3 viewports = 9 screenshots

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/login.mustache`

---

## P1-8: Dark Mode
- **Status:** `[ ]`
- **Dependencies:** `P1-1`, `P1-3`, `P1-4`, `P1-5`
- **Effort:** 6 hours
- **Blocks:** None

**Task:** System-wide dark mode with CSS custom property toggle.

**Files:**
- [ ] `scss/components/_dark-mode.scss` — Component-level dark overrides
- [ ] `amd/src/darkmode.js` — Toggle logic, preference, system detection

**Acceptance Criteria:**
- [ ] Toggle button in navbar (sun/moon icon)
- [ ] All pages fully styled in dark mode (no bright flashes)
- [ ] User preference persisted via Moodle user preferences API
- [ ] Respects `prefers-color-scheme: dark` media query on first visit
- [ ] Smooth transition animation (200ms CSS transition on background/color)
- [ ] No flash of wrong theme on page load (preference read before render)
- [ ] All text meets WCAG contrast ratios in dark mode
- [ ] Images/logos have appropriate dark mode variants (or transparent backgrounds)
- [ ] Third-party content (iframes, embedded) not broken

**Screenshots:** Dashboard, course, login in dark mode × 3 viewports

---

## P1-9: Moodle Core Template Overrides
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 10 hours
- **Blocks:** P1-10, P3-2

**Task:** Override key Moodle core templates for consistent Zenith design.

**Templates to Override:**
- [ ] `core/notification_error.mustache` (+ info, success, warning)
- [ ] `core_course/coursecard.mustache`
- [ ] `core_course/coursecards.mustache`
- [ ] `core_form/element-advcheckbox.mustache`
- [ ] `core_form/element-checkbox.mustache`
- [ ] `core_form/element-text.mustache`
- [ ] `core_form/element-select.mustache`
- [ ] `core/modal.mustache`
- [ ] `core/paging_bar.mustache`
- [ ] `core/user_menu.mustache`
- [ ] `block_myoverview/main.mustache`
- [ ] `block_myoverview/view-cards.mustache`
- [ ] `block_myoverview/view-list.mustache`

**Acceptance Criteria:**
- [ ] All overridden templates use `--z-*` design tokens
- [ ] No broken functionality (all forms submit, modals open/close, etc.)
- [ ] Consistent visual language across all overridden components
- [ ] ARIA attributes preserved from originals
- [ ] Mobile responsive

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/core/`

---

## P1-10: Course Cards & Archive Page
- **Status:** `[ ]`
- **Dependencies:** `P1-9`
- **Effort:** 8 hours
- **Blocks:** P5-1

**Task:** Beautiful course card design + course archive/catalog page.

**Files:**
- [ ] `layout/category.php` — Category page context
- [ ] `templates/coursearchive.mustache` — Archive page layout
- [ ] `templates/core_course/coursecard.mustache` — Individual course card
- [ ] `scss/components/_cards.scss` — Card styling
- [ ] `scss/components/_coursearchive.scss` — Archive layout styling
- [ ] `amd/src/coursecategory.js` — Filters, search, AJAX pagination

**Acceptance Criteria:**
- [ ] Grid view: 3 columns desktop, 2 tablet, 1 mobile
- [ ] List view: toggle between grid and list
- [ ] Card shows: course image, title, category badge, progress bar, instructor
- [ ] Category filter dropdown
- [ ] Search input (filters by name)
- [ ] Smooth card hover animations (scale, shadow)
- [ ] AJAX pagination (no full page reload)
- [ ] Empty state: "No courses found" with illustration
- [ ] Course count badge

**Screenshots:** Grid view + list view × 3 viewports

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/remui/_coursearchive.scss`
