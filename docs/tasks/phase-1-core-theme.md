# Phase 1: Core Theme Design

> **Timeline:** Weeks 2-4 · **Total Effort:** 74 hours · **Tasks:** 10

---

## P1-1: Design Token System (CSS Custom Properties)
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P0-3`
- **Effort:** 4 hours
- **Blocks:** P1-2, P1-7, P1-8, P3-4

**Task:** Create the `--z-` prefixed design token system covering colors, spacing, typography, shadows, and radius.

**Files Created:**
- [x] `scss/_variables.scss` — ~200 design tokens in :root (colors, spacing, typography, shadows, radius, transitions, z-index, component tokens)
- [x] `scss/_dark-mode.scss` — Dark mode token overrides via `[data-theme="dark"]` (semantic tokens only)
- [x] `scss/preset/default.scss` — Main SCSS entry point with Bootstrap variable overrides + token imports

**Acceptance Criteria:**
- [x] All colors defined as `--z-primary`, `--z-secondary`, `--z-bg-page`, etc.
- [x] Spacing scale: `--z-space-1` (4px) through `--z-space-24` (96px)
- [x] Typography: `--z-font-sans`, `--z-text-xs` through `--z-text-5xl`, weights, line heights, letter spacing
- [x] Shadows: `--z-shadow-xs` through `--z-shadow-2xl` + inner + none
- [x] Radius: `--z-radius-none` through `--z-radius-full`
- [x] Dark mode flips all semantic tokens via `[data-theme="dark"]`
- [x] All tokens documented with inline comments
- [x] Tokens compile into CSS and appear in rendered pages (verified via Moodle SCSS compiler)

**Issues Encountered:**
1. SCSS `@import` directives in `preset/default.scss` not resolved by Moodle's SCSS compiler — it doesn't have the theme's scss directory in its import paths. Fixed by updating `lib.php` to use `preg_replace_callback()` to inline `@import` directives by reading and concatenating the referenced files.

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/preset/remui.scss` (lines 11-250)

---

## P1-2: Selective Bootstrap Import
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P1-1`
- **Effort:** 6 hours
- **Blocks:** P1-3, P1-4, P1-7

**Task:** Import Bootstrap + Moodle core SCSS into the Zenith preset (child theme replaces parent SCSS entirely).

**Files Modified:**
- [x] `scss/preset/default.scss` — Complete preset with Bootstrap variable overrides, FontAwesome, selective Bootstrap 4.6.2 imports, Moodle core SCSS, and Zenith design tokens

**Acceptance Criteria:**
- [x] Core: functions, variables, mixins, root, reboot, type, images, code, grid
- [x] Components: buttons, dropdown, button-group, input-group, custom-forms, nav, navbar, card, breadcrumb, pagination, badge, alert, progress, list-group, close, toasts, modal, tooltip, popover, carousel, spinners, tables
- [x] Utilities: utilities + print
- [x] SKIP: jumbotron, media (confirmed not used by Moodle core)
- [x] All Moodle core pages render correctly (verified: frontpage, login, dashboard, course list, course categories)
- [x] CSS output: 989 KB full (vs 986 KB Boost baseline — further reduction via PurgeCSS in P4-1)
- [x] Grid system works at all breakpoints (verified via screenshots)
- [x] FontAwesome icons included
- [x] Moodle core SCSS included (drawers, navbar, forms, blocks, etc.)
- [x] SCSS linter passes with zero errors

**Key Discovery:**
- Moodle uses Bootstrap **4.6.2** (not Bootstrap 5 as originally assumed)
- Child theme SCSS **completely replaces** parent theme SCSS (not additive) — the preset must import everything
- Moodle's SCSS compiler provides import paths for both child and parent theme scss directories
- Aggressive module pruning is impractical because Moodle's ~50 SCSS files depend on nearly all Bootstrap modules
- Real CSS size reduction will come from PurgeCSS (P4-1) stripping unused selectors from compiled output

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/bootstrap/`

---

## P1-3: Navbar Design
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P1-1`, `P1-2`
- **Effort:** 8 hours
- **Blocks:** P1-5, P1-8, P3-5

**Task:** Design and implement the top navigation bar.

**Files Created:**
- [x] `templates/theme_boost/navbar.mustache` — Custom navbar template override (BEM classes: z-navbar__*)
- [x] `scss/components/_navbar.scss` — Navbar styling with --z-navbar-* design tokens
- [x] `scss/preset/default.scss` — Updated to import navbar component

**Not Needed:**
- `layout/common.php` — Boost's drawers.php already passes all required data (primarymoremenu, usermenu, langmenu, mobileprimarynav)
- `amd/src/navbar.js` — Mobile toggle handled by Boost's drawer.js, search by Moodle core JS

**Acceptance Criteria:**
- [x] Logo + site name (configurable via settings — shows logo if set, site name otherwise)
- [x] Primary navigation links (Moodle primary nav via core/moremenu)
- [x] User menu (avatar + dropdown with profile, logout via core/user_menu)
- [x] Language menu (rendered if multiple languages installed via theme_boost/language_menu)
- [x] Search toggle (rendered via output.search_box when global search enabled)
- [x] Notifications indicator (bell icon via output.navbar_plugin_output)
- [x] Messages indicator (chat icon via output.navbar_plugin_output)
- [x] Responsive: hamburger menu on mobile (≤768px) with SVG icon
- [x] Smooth open/close animations (CSS transitions via --z-transition-fast)
- [x] ARIA labels on nav element and toggle button
- [x] Keyboard navigable (inherits from Moodle core moremenu + user_menu JS)
- [x] Dark mode compatible (uses --z-navbar-* tokens overridden in _dark-mode.scss)
- [x] SCSS linter passes with zero errors

**Key Decisions:**
- Override placed at `templates/theme_boost/navbar.mustache` — Moodle's template resolver checks child theme first for parent-prefixed templates
- BEM naming convention: `.z-navbar`, `.z-navbar__brand`, `.z-navbar__primary-nav`, etc.
- Mobile breakpoint at 768px (md) — hamburger toggle below, full nav above
- Navbar height: 64px desktop (--z-navbar-height), 56px mobile
- Notification badge styling with --z-danger color and --z-radius-full

**Screenshots:** Desktop, tablet, mobile viewports all captured and verified

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/navbar.mustache`

---

## P1-4: Footer Design
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P1-1`, `P1-2`
- **Effort:** 6 hours
- **Blocks:** P1-6, P1-8

**Task:** Configurable footer with social media, copyright, and branding.

**Files Created:**
- [x] `templates/theme_boost/footer.mustache` — Custom footer template override (replaces Boost's popover footer)
- [x] `scss/components/_footer.scss` — Footer styling with --z-footer-* design tokens + sticky footer
- [x] `classes/output/core_renderer.php` — Custom renderer with footer helper methods
- [x] `settings.php` — Added footer settings (content, copyright, branding, social links)
- [x] `lang/en/theme_zenith.php` — Added 12 new language strings for footer/social settings

**Acceptance Criteria:**
- [x] Footer content: configurable HTML editor (admin setting)
- [x] Social media icons: Facebook, X/Twitter, LinkedIn, YouTube, Instagram (circular buttons with hover animation)
- [x] Copyright text (configurable with {year} placeholder)
- [x] "Powered by Zenith" branding (toggleable via setting)
- [x] Back-to-top floating button (appears after 300px scroll, smooth scroll)
- [x] Moodle standard links (doc link, support email, login info, data retention)
- [x] Responsive: 3-column grid → 2-column tablet → 1-column mobile
- [x] Dark mode compatible (uses --z-footer-* tokens overridden in _dark-mode.scss)
- [x] ARIA landmarks (`<footer role="contentinfo">`)
- [x] Sticky footer (pushes to viewport bottom on short pages via flexbox)
- [x] Boost popover footer hidden via CSS override
- [x] SCSS linter passes with zero errors

**Key Decisions:**
- Used renderer methods (`zenith_footer_copyright`, `zenith_social_links`, etc.) rather than layout file data passing
- Replaced Boost's popover-style footer entirely with a proper full-width footer
- Social icons use FontAwesome 6 brands (`fa-brands fa-facebook-f`) with noopener/noreferrer
- Sticky footer via `#page-wrapper { display: flex; min-height: 100vh }`

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/footer.mustache`

---

## P1-5: Drawer System (Left + Right)
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P1-3`
- **Effort:** 8 hours
- **Blocks:** P1-6, P1-8

**Task:** Course index drawer (left) and block sidebar drawer (right).

**Files Created:**
- [x] `scss/components/_drawer.scss` — Zenith-styled drawer overrides with --z-drawer-* design tokens (~320 lines)
- [x] `templates/theme_boost/drawers.mustache` — Layout template override (inherits Boost's drawer partial + JS)
- [x] `scss/preset/default.scss` — Updated to import drawer component

**Not Needed (inherited from Boost):**
- `templates/drawer.mustache` — Boost's drawer partial works perfectly, no override needed
- `templates/common_start.mustache` / `common_end.mustache` — Deferred to P1-6 (Layout Files)
- `amd/src/drawer.js` — Boost's drawers.js handles all toggle, animation, preference persistence, focus traps, and backdrop; no custom JS needed

**Acceptance Criteria:**
- [x] Left drawer: course index navigation (when on course pages) — verified on Demo Course 101
- [x] Right drawer: page block sidebar — verified on dashboard with "Recently accessed items" block
- [x] Toggle buttons visible in navbar/page area — styled with --z-* tokens (40px rounded buttons with border + shadow)
- [x] Smooth slide-in/out animations (CSS transitions) — inherited from Boost's drawers.js (0.2s ease transitions)
- [x] Open/closed state persisted via Moodle user preferences — inherited from Boost (drawer-open-index, drawer-open-block)
- [x] Auto-close on mobile when user navigates — inherited from Boost (drawercloseonresize attribute)
- [x] Overlay background on mobile (click to dismiss) — inherited from Boost (ModalBackdrop with z-index management)
- [x] Keyboard accessible (Escape to close, focus trap while open) — inherited from Boost (FocusLock.trapFocus on mobile)
- [x] Data attributes: Boost uses `data-preference`, `data-state`, `data-forceopen`, `data-close-on-resize` — compatible

**Key Decisions:**
- Leveraged Boost's complete drawer infrastructure (drawers.js, drawer.mustache partial, ModalBackdrop) rather than rebuilding from scratch
- Only custom SCSS needed — all JS behavior (toggle, animations, preference persistence, focus trapping, backdrop) inherited from parent theme
- Styled course index items with active state highlighting (--z-primary-subtle), block cards with rounded corners and hover shadows
- Mobile primary navigation drawer restyled with list-group items using --z-* tokens
- Custom scrollbar styling (6px thin, rounded thumb) for both Webkit and Firefox
- Dark mode adjustments complement the tokens already defined in _dark-mode.scss
- Created Demo Course 101 (id=2) for testing course index drawer

**Screenshots:** Desktop (dashboard, course, both drawers), tablet, mobile — all verified across 3 viewports

**Reference:** Boost's `amd/src/drawers.js`, `templates/drawer.mustache`, `scss/moodle/drawer.scss`

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
