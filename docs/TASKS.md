# Zenith Moodle Theme — Task Breakdown

> Each task has: ID, title, status, phase, dependencies, acceptance criteria, and estimated effort.
> Statuses: `[ ]` pending, `[~]` in-progress, `[x]` done, `[!]` blocked

---

## Phase 0: Infrastructure & Scaffolding (Week 1)

### P0-1: Docker Development Environment
- **Status:** `[ ]`
- **Dependencies:** None
- **Task:** Set up local Moodle 4.5 LTS with MariaDB via Docker
- **Acceptance Criteria:**
  - [ ] `docker compose up -d` starts Moodle on port 8081
  - [ ] MariaDB running on port 3306
  - [ ] Admin login works at http://localhost:8081
  - [ ] Theme source bind-mounted for live dev
  - [ ] Health checks passing for both containers
- **Effort:** 2 hours

### P0-2: Production Deployment (Coolify)
- **Status:** `[ ]`
- **Dependencies:** P0-1
- **Task:** Deploy to lms.endlessmaker.com via Coolify
- **Acceptance Criteria:**
  - [ ] `docker-compose.coolify.yml` deploys successfully
  - [ ] Containers on `coolify` external network
  - [ ] Traefik routes `lms.endlessmaker.com` → zenith-moodle:8081
  - [ ] HTTPS working (Let's Encrypt via Coolify)
  - [ ] Health checks passing
- **Effort:** 3 hours

### P0-3: Theme Scaffold (Boost Child)
- **Status:** `[ ]`
- **Dependencies:** P0-1
- **Task:** Create minimal Boost child theme that installs and activates
- **Files to Create:**
  - [ ] `theme/zenith/version.php`
  - [ ] `theme/zenith/config.php` (layouts, regions, SCSS callbacks)
  - [ ] `theme/zenith/lib.php` (pluginfile, SCSS functions)
  - [ ] `theme/zenith/settings.php` (minimal settings tab)
  - [ ] `theme/zenith/lang/en/theme_zenith.php`
  - [ ] `theme/zenith/scss/preset/default.scss`
  - [ ] `theme/zenith/pix/screenshot.png` (placeholder)
- **Acceptance Criteria:**
  - [ ] Theme appears in Moodle theme selector
  - [ ] Activating theme doesn't break any page
  - [ ] All Moodle core pages render correctly
  - [ ] No PHP errors or warnings
- **Effort:** 4 hours

### P0-4: Build Tooling
- **Status:** `[ ]`
- **Dependencies:** P0-3
- **Task:** Set up Grunt, npm scripts, SCSS compilation, linting
- **Files to Create:**
  - [ ] `theme/zenith/package.json`
  - [ ] `theme/zenith/Gruntfile.js`
  - [ ] `theme/zenith/.stylelintrc`
  - [ ] `theme/zenith/.eslintrc`
- **Acceptance Criteria:**
  - [ ] `npm run amd` compiles JS modules
  - [ ] `npx grunt css` compiles SCSS
  - [ ] `npx grunt watch` watches for changes
  - [ ] `npm run lint` checks SCSS + JS
- **Effort:** 3 hours

### P0-5: E2E Screenshot Setup
- **Status:** `[ ]`
- **Dependencies:** P0-1
- **Task:** Set up Playwright screenshot testing for all viewports
- **Acceptance Criteria:**
  - [ ] `npm run screenshots` captures 12 pages × 3 viewports = 36 screenshots
  - [ ] Screenshots saved to `test-screenshots/{desktop,tablet,mobile}/`
  - [ ] Login automation works
  - [ ] Mobile viewport (375px) captures responsive layout
- **Effort:** 2 hours

### P0-6: CI/CD Pipeline
- **Status:** `[ ]`
- **Dependencies:** P0-4, P0-5
- **Task:** GitHub Actions for lint, build, test, screenshot on PR
- **Files to Create:**
  - [ ] `.github/workflows/ci.yml`
- **Acceptance Criteria:**
  - [ ] PR triggers: lint → build → test
  - [ ] Screenshots uploaded as artifacts
  - [ ] Build fails on lint errors
- **Effort:** 3 hours

---

## Phase 1: Core Theme Design (Weeks 2-4)

### P1-1: Design Token System (CSS Custom Properties)
- **Status:** `[ ]`
- **Dependencies:** P0-3
- **Task:** Create the `--z-` prefixed design token system
- **Files:**
  - [ ] `scss/_variables.scss` — All tokens (colors, spacing, typography, shadows, radius)
  - [ ] `scss/_dark-mode.scss` — Dark mode token overrides
- **Acceptance Criteria:**
  - [ ] All colors, spacing, shadows, radius defined as `--z-*` tokens
  - [ ] Dark mode toggles all tokens via `[data-theme="dark"]`
  - [ ] Tokens documented in code comments
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/preset/remui.scss` (lines 11-250)
- **Effort:** 4 hours

### P1-2: Selective Bootstrap Import
- **Status:** `[ ]`
- **Dependencies:** P1-1
- **Task:** Import only needed Bootstrap 5 modules (~60% reduction)
- **Files:**
  - [ ] `scss/_bootstrap-selective.scss`
- **Acceptance Criteria:**
  - [ ] All Moodle core pages render correctly
  - [ ] No missing Bootstrap components on any standard page
  - [ ] CSS output <150KB for Bootstrap portion (vs ~230KB full)
  - [ ] Grid, forms, modals, dropdowns, nav, cards, buttons all work
- **Reference:** Bootstrap 5 source in RemUI: `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/bootstrap/`
- **Effort:** 6 hours

### P1-3: Navbar Design
- **Status:** `[ ]`
- **Dependencies:** P1-1, P1-2
- **Task:** Design and implement the top navigation bar
- **Files:**
  - [ ] `layout/common.php` (navbar context)
  - [ ] `templates/navbar.mustache`
  - [ ] `scss/components/_navbar.scss`
  - [ ] `amd/src/navbar.js` (mobile toggle, search)
- **Acceptance Criteria:**
  - [ ] Logo + site name (configurable)
  - [ ] Primary navigation links
  - [ ] User menu (avatar + dropdown)
  - [ ] Language menu
  - [ ] Search toggle
  - [ ] Responsive: hamburger menu on mobile (≤768px)
  - [ ] Smooth animations
  - [ ] ARIA labels on all interactive elements
  - [ ] Keyboard navigable (Tab, Enter, Escape)
- **Screenshots:** Desktop, tablet, mobile navbar
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/navbar.mustache`
- **Effort:** 8 hours

### P1-4: Footer Design
- **Status:** `[ ]`
- **Dependencies:** P1-1, P1-2
- **Task:** Configurable multi-column footer
- **Files:**
  - [ ] `templates/footer.mustache`
  - [ ] `scss/components/_footer.scss`
  - [ ] Settings in `settings.php` (columns, social links, copyright)
- **Acceptance Criteria:**
  - [ ] 1-4 configurable columns
  - [ ] Social media icons (7 platforms)
  - [ ] Copyright text
  - [ ] Back-to-top button
  - [ ] Responsive stacking on mobile
  - [ ] Dark mode compatible
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/footer.mustache`
- **Effort:** 6 hours

### P1-5: Drawer System (Left + Right)
- **Status:** `[ ]`
- **Dependencies:** P1-3
- **Task:** Course index drawer (left) + block drawer (right)
- **Files:**
  - [ ] `templates/drawer.mustache` (reusable block partial)
  - [ ] `templates/common_start.mustache`
  - [ ] `templates/common_end.mustache`
  - [ ] `scss/components/_drawer.scss`
  - [ ] `amd/src/drawer.js`
- **Acceptance Criteria:**
  - [ ] Left drawer: course index navigation
  - [ ] Right drawer: page blocks
  - [ ] Toggle buttons with animations
  - [ ] User preference persistence (open/closed state)
  - [ ] Auto-close on mobile when navigating
  - [ ] Keyboard accessible
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/amd/src/drawer.js`
- **Effort:** 8 hours

### P1-6: Layout Files (All 12)
- **Status:** `[ ]`
- **Dependencies:** P1-3, P1-4, P1-5
- **Task:** Create all layout PHP files with common sandwich pattern
- **Files:**
  - [ ] `layout/common.php` + `layout/common_end.php`
  - [ ] `layout/drawers.php` (dashboard, admin, standard)
  - [ ] `layout/course.php`
  - [ ] `layout/incourse.php`
  - [ ] `layout/frontpage.php`
  - [ ] `layout/category.php`
  - [ ] `layout/login.php`
  - [ ] `layout/profile.php`
  - [ ] `layout/columns1.php`
  - [ ] `layout/embedded.php`
  - [ ] `layout/maintenance.php`
  - [ ] `layout/secure.php`
- **Matching templates for each layout**
- **Acceptance Criteria:**
  - [ ] Every Moodle page type renders without errors
  - [ ] Block regions work in all layouts
  - [ ] Editing mode toggle works
  - [ ] Mobile responsive on all layouts
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/layout/`
- **Effort:** 12 hours

### P1-7: Login Page (3 Layouts)
- **Status:** `[ ]`
- **Dependencies:** P1-1, P1-2
- **Task:** Beautiful login page with 3 configurable layout options
- **Files:**
  - [ ] `layout/login.php`
  - [ ] `templates/login.mustache`
  - [ ] `scss/components/_login.scss`
- **Acceptance Criteria:**
  - [ ] Layout 1: Centered form + background image
  - [ ] Layout 2: Left panel form + right image
  - [ ] Layout 3: Right panel form + left image
  - [ ] Custom background image upload in settings
  - [ ] Brand logo display
  - [ ] Signup link (configurable)
  - [ ] Responsive: full-width form on mobile
  - [ ] Accessible: form labels, focus indicators
- **Screenshots:** All 3 layouts × 3 viewports
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/login.mustache`
- **Effort:** 6 hours

### P1-8: Dark Mode
- **Status:** `[ ]`
- **Dependencies:** P1-1, P1-3, P1-4, P1-5
- **Task:** Dark mode with CSS custom property toggle
- **Files:**
  - [ ] `scss/components/_dark-mode.scss`
  - [ ] `amd/src/darkmode.js`
- **Acceptance Criteria:**
  - [ ] Toggle button in navbar
  - [ ] All pages fully styled in dark mode
  - [ ] User preference persisted (survives page reload)
  - [ ] Respects `prefers-color-scheme` media query
  - [ ] Smooth transition animation
  - [ ] No flash of wrong theme on page load
  - [ ] All text readable (WCAG contrast ratios)
- **Screenshots:** Key pages in dark mode
- **Effort:** 6 hours

### P1-9: Moodle Core Template Overrides
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Override key Moodle core templates for consistent design
- **Templates to Override:**
  - [ ] `core/notification_error.mustache` (+ info, success, warning)
  - [ ] `core_course/coursecard.mustache`
  - [ ] `core_course/coursecards.mustache`
  - [ ] `core_form/element-*.mustache` (key form elements)
  - [ ] `core/modal.mustache`
  - [ ] `core/paging_bar.mustache`
  - [ ] `core/user_menu.mustache`
  - [ ] `block_myoverview/main.mustache`
  - [ ] `block_myoverview/view-cards.mustache`
- **Acceptance Criteria:**
  - [ ] All overridden templates use Zenith design tokens
  - [ ] No broken functionality
  - [ ] Consistent look across all pages
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/core/`
- **Effort:** 10 hours

### P1-10: Course Cards & Archive Page
- **Status:** `[ ]`
- **Dependencies:** P1-9
- **Task:** Beautiful course card design + archive/catalog page
- **Files:**
  - [ ] `layout/category.php`
  - [ ] `templates/coursearchive.mustache`
  - [ ] `templates/core_course/coursecard.mustache`
  - [ ] `scss/components/_cards.scss`
  - [ ] `scss/components/_coursearchive.scss`
  - [ ] `amd/src/coursecategory.js` (filters, search, pagination)
- **Acceptance Criteria:**
  - [ ] Grid view (3 columns desktop, 2 tablet, 1 mobile)
  - [ ] List view toggle
  - [ ] Course image, title, category, progress bar
  - [ ] Search/filter by category
  - [ ] Smooth card animations (hover effects)
  - [ ] Pagination
  - [ ] Empty state ("No courses found")
- **Screenshots:** Grid + List views × 3 viewports
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/scss/remui/_coursearchive.scss`
- **Effort:** 8 hours

---

## Phase 2: Customizer & Settings (Weeks 5-6)

### P2-1: Settings Panel (Tabbed Admin UI)
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Multi-tab admin settings interface
- **Tabs:**
  - [ ] General (logo, site name, favicon)
  - [ ] Homepage (hero, sections)
  - [ ] Course (archive settings)
  - [ ] Footer (columns, social, copyright)
  - [ ] Login (layout, background)
  - [ ] Advanced (custom CSS)
- **Acceptance Criteria:**
  - [ ] All settings save and apply correctly
  - [ ] File uploads work (logo, favicon, backgrounds)
  - [ ] Color pickers work
  - [ ] Settings organized logically
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/settings.php`
- **Effort:** 10 hours

### P2-2: Visual Customizer (Live Preview)
- **Status:** `[ ]`
- **Dependencies:** P2-1
- **Task:** Live preview customizer with AJAX save
- **Components:**
  - [ ] Color picker (primary, secondary, backgrounds)
  - [ ] Font selector (Google Fonts)
  - [ ] Spacing controls (range sliders)
  - [ ] Typography controls (font size, weight, line height)
  - [ ] Button styling (radius, padding, colors)
- **Files:**
  - [ ] `classes/customizer/customizer.php` (singleton)
  - [ ] `classes/customizer/elements/*.php` (UI controls)
  - [ ] `classes/customizer/process/*.php` (CSS generation)
  - [ ] `classes/customizer/external/api.php` (AJAX save)
  - [ ] `amd/src/customizer/*.js` (live preview modules)
  - [ ] `templates/customizer/main.mustache`
  - [ ] `db/services.php` (web service definitions)
- **Acceptance Criteria:**
  - [ ] Changes preview instantly (no page reload)
  - [ ] Save persists to database
  - [ ] Reset to defaults works
  - [ ] Multiple presets (5+ color schemes)
  - [ ] Generated CSS is valid and minimal
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/classes/customizer/`
- **Effort:** 20 hours

### P2-3: Setup Wizard
- **Status:** `[ ]`
- **Dependencies:** P2-1
- **Task:** Guided first-time setup wizard
- **Steps:**
  - [ ] Welcome screen
  - [ ] Upload logo + favicon
  - [ ] Choose color preset
  - [ ] Configure homepage
  - [ ] Done / success screen
- **Acceptance Criteria:**
  - [ ] Shows automatically on first activation
  - [ ] Can be skipped
  - [ ] Can be re-launched from settings
  - [ ] Mobile friendly
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/setupwizard/`
- **Effort:** 8 hours

---

## Phase 3: Differentiating Features (Weeks 7-10)

### P3-1: Focus Mode
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Distraction-free course viewing mode
- **Files:**
  - [ ] `amd/src/focusmode.js`
  - [ ] `scss/components/_focusmode.scss`
  - [ ] `templates/navbar_fm.mustache` (focus mode navbar)
- **Acceptance Criteria:**
  - [ ] Toggle button in course pages
  - [ ] Hides navbar, drawers, footer — shows only course content
  - [ ] Previous/Next navigation between sections
  - [ ] Progress indicator
  - [ ] Escape key exits focus mode
  - [ ] User preference persisted
  - [ ] Mobile friendly
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/amd/src/focusmode.js`
- **Effort:** 8 hours

### P3-2: Enhanced Dashboard
- **Status:** `[ ]`
- **Dependencies:** P1-6, P1-9
- **Task:** Beautiful dashboard with stats widgets
- **Widgets:**
  - [ ] Courses enrolled / completed / in-progress
  - [ ] Activities due this week
  - [ ] Recent courses (quick access)
  - [ ] Calendar upcoming events
  - [ ] Course progress bars
- **Files:**
  - [ ] `classes/external/get_dashboard_stats.php`
  - [ ] `templates/dashboard_stats.mustache`
  - [ ] `scss/components/_dashboard.scss`
  - [ ] `amd/src/dashboard.js`
- **Acceptance Criteria:**
  - [ ] Stats load via AJAX (fast page load)
  - [ ] Animated counters
  - [ ] Mobile responsive (stack cards)
  - [ ] Empty states handled
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/dashboard_stats.mustache`
- **Effort:** 10 hours

### P3-3: Enhanced Enrollment Page
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Redesigned course enrollment page
- **Sections:**
  - [ ] Course hero banner (image + title + rating)
  - [ ] Course description
  - [ ] Instructor bio
  - [ ] Course content outline (sections/topics)
  - [ ] Enrol button (prominent)
- **Acceptance Criteria:**
  - [ ] Looks like a modern course landing page (Udemy-style)
  - [ ] Mobile responsive
  - [ ] Handles free and paid courses
  - [ ] Loading state while fetching data
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/classes/EnrolmentPageHandler.php`
- **Effort:** 10 hours

### P3-4: Accessibility Toolkit
- **Status:** `[ ]`
- **Dependencies:** P1-1
- **Task:** Built-in accessibility tools for users
- **Features:**
  - [ ] Font size adjuster (100%, 125%, 150%)
  - [ ] High contrast mode toggle
  - [ ] Dyslexia-friendly font (OpenDyslexic)
  - [ ] Reading ruler / focus line
  - [ ] Link highlighting
- **Files:**
  - [ ] `classes/accessibility/toolkit.php`
  - [ ] `amd/src/accessibility.js`
  - [ ] `scss/components/_accessibility.scss`
  - [ ] `templates/accessibility_panel.mustache`
- **Acceptance Criteria:**
  - [ ] Floating accessibility button on all pages
  - [ ] Settings persist per-user
  - [ ] Works with dark mode
  - [ ] Keyboard operable
  - [ ] WCAG 2.2 AA compliant itself
- **Effort:** 12 hours

### P3-5: Gamification UI
- **Status:** `[ ]`
- **Dependencies:** P1-3, P1-6
- **Task:** Native gamification elements in theme chrome
- **Features:**
  - [ ] XP progress bar in navbar
  - [ ] Level badge next to user avatar
  - [ ] Daily streak counter
  - [ ] Mini leaderboard widget (dashboard)
  - [ ] Achievement notifications (toast)
- **Files:**
  - [ ] `classes/gamification/engine.php`
  - [ ] `amd/src/gamification.js`
  - [ ] `scss/components/_gamification.scss`
  - [ ] `templates/gamification/*.mustache`
  - [ ] `db/install.xml` (custom tables for XP, streaks)
- **Acceptance Criteria:**
  - [ ] Works standalone (no plugin required)
  - [ ] Optional integration with Level Up XP plugin
  - [ ] Can be enabled/disabled per-site
  - [ ] Mobile responsive
  - [ ] Animated XP gain notification
- **Effort:** 16 hours

### P3-6: AI Assistant Integration
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** AI-powered features using Moodle 4.5+ AI subsystem
- **Features:**
  - [ ] Course content summarizer (sidebar widget)
  - [ ] Smart course search / recommendations
  - [ ] "Ask about this course" chat (per-course)
- **Files:**
  - [ ] `classes/ai/assistant.php`
  - [ ] `amd/src/ai/assistant.js`
  - [ ] `scss/components/_ai-assistant.scss`
  - [ ] `templates/ai/chat.mustache`
  - [ ] `templates/ai/summary.mustache`
- **Acceptance Criteria:**
  - [ ] Works with Moodle AI providers (OpenAI, Ollama)
  - [ ] Graceful fallback if AI not configured
  - [ ] Can be enabled/disabled per-site
  - [ ] Rate limiting to prevent abuse
  - [ ] Mobile friendly (slide-up panel)
- **Effort:** 20 hours

### P3-7: Learning Path Visualization
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Visual learning journey / roadmap
- **Files:**
  - [ ] `amd/src/learningpath.js` (SVG/Canvas visualization)
  - [ ] `scss/components/_learning-path.scss`
  - [ ] `templates/learningpath/roadmap.mustache`
- **Acceptance Criteria:**
  - [ ] Visual roadmap with course milestones
  - [ ] Shows completed, current, locked courses
  - [ ] Branching paths (if prerequisites exist)
  - [ ] Click to navigate to course
  - [ ] Responsive (horizontal scroll on mobile)
  - [ ] Animated progress
- **Effort:** 16 hours

---

## Phase 4: Polish & Performance (Weeks 11-12)

### P4-1: Performance Optimization
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3
- **Task:** Optimize CSS size, JS loading, page speed
- **Actions:**
  - [ ] Run PurgeCSS on final compiled CSS
  - [ ] Lazy-load heavy JS modules (AI, gamification, page builder)
  - [ ] Optimize images (SVG preferred, WebP fallback)
  - [ ] Verify precompiled CSS callback works
  - [ ] Test with Moodle caching enabled
  - [ ] Profile with Lighthouse
- **Acceptance Criteria:**
  - [ ] CSS < 200KB (uncompressed), < 40KB gzipped
  - [ ] Lighthouse Performance > 85
  - [ ] First Contentful Paint < 2s
  - [ ] No render-blocking JS
- **Effort:** 8 hours

### P4-2: RTL Support
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Right-to-left language support (Arabic, Hebrew)
- **Files:**
  - [ ] `scss/components/_rtl.scss`
  - [ ] `lang/ar/theme_zenith.php`
- **Acceptance Criteria:**
  - [ ] All layouts mirror correctly in RTL
  - [ ] Navbar, drawers, footer all RTL-aware
  - [ ] Text alignment correct
  - [ ] Icons don't flip incorrectly
- **Screenshots:** Key pages in RTL mode
- **Effort:** 8 hours

### P4-3: Multi-Language Support (8 Languages)
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** Create language files for 8 languages
- **Languages:**
  - [ ] English (en) — primary
  - [ ] Arabic (ar)
  - [ ] Spanish (es)
  - [ ] French (fr)
  - [ ] German (de)
  - [ ] Portuguese Brazil (pt_br)
  - [ ] Chinese Simplified (zh_cn)
  - [ ] Japanese (ja)
- **Acceptance Criteria:**
  - [ ] All theme-specific strings translated
  - [ ] Language switcher works
  - [ ] No hardcoded strings in templates
- **Effort:** 6 hours

### P4-4: Mobile-First Responsive Audit
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3
- **Task:** Comprehensive mobile responsiveness testing and fixes
- **Viewports to Test:**
  - [ ] iPhone SE (375px)
  - [ ] iPhone 14 (390px)
  - [ ] iPad (768px)
  - [ ] iPad Pro (1024px)
  - [ ] Laptop (1440px)
  - [ ] 4K (2560px)
- **Pages to Verify:**
  - [ ] Login page
  - [ ] Dashboard
  - [ ] Course list / archive
  - [ ] Course view
  - [ ] Activity pages
  - [ ] Profile
  - [ ] Messages
  - [ ] Admin pages
  - [ ] Footer
- **Acceptance Criteria:**
  - [ ] No horizontal scroll on any viewport
  - [ ] Touch targets ≥ 44px × 44px
  - [ ] Text readable without zoom
  - [ ] All screenshots captured for all viewports
  - [ ] Drawers collapse properly
  - [ ] Forms usable on mobile
- **Effort:** 10 hours

### P4-5: Accessibility Audit (WCAG 2.2 AA)
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3
- **Task:** Full WCAG 2.2 AA compliance audit and fixes
- **Tools:**
  - [ ] axe DevTools scan on all page types
  - [ ] WAVE evaluation
  - [ ] Lighthouse Accessibility audit
  - [ ] Manual keyboard navigation test
  - [ ] Screen reader test (NVDA or VoiceOver)
- **Acceptance Criteria:**
  - [ ] axe: 0 critical/serious violations
  - [ ] Lighthouse Accessibility > 95
  - [ ] All interactive elements keyboard accessible
  - [ ] All images have alt text
  - [ ] Color contrast ≥ 4.5:1 (normal text), ≥ 3:1 (large text)
  - [ ] Focus indicators visible on all elements
  - [ ] Heading hierarchy correct (no skipped levels)
  - [ ] Form labels associated correctly
- **Effort:** 10 hours

### P4-6: Browser Compatibility Testing
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3
- **Task:** Test across major browsers
- **Browsers:**
  - [ ] Chrome (latest)
  - [ ] Firefox (latest)
  - [ ] Safari (latest)
  - [ ] Edge (latest)
  - [ ] Chrome Android
  - [ ] Safari iOS
- **Acceptance Criteria:**
  - [ ] No layout breaks
  - [ ] All JS features work
  - [ ] CSS animations smooth
  - [ ] Dark mode works
- **Effort:** 6 hours

---

## Phase 5: Ecosystem Plugins (Weeks 13-16)

### P5-1: Course Format Plugin
- **Status:** `[ ]`
- **Dependencies:** P1-10
- **Task:** Custom course format (card/grid/tab views)
- **Location:** `plugins/format_zenith/`
- **Acceptance Criteria:**
  - [ ] Card view for course sections
  - [ ] Tab view option
  - [ ] Collapse/expand sections
  - [ ] Consistent with theme design tokens
- **Reference:** `/home/redman/Edwiser-RemUI/Plugins/format_remuiformat/`
- **Effort:** 16 hours

### P5-2: Demo Content Importer
- **Status:** `[ ]`
- **Dependencies:** P1-6
- **Task:** One-click demo site import
- **Location:** `plugins/local_zenithimporter/`
- **Acceptance Criteria:**
  - [ ] Creates demo courses, categories, users
  - [ ] Imports sample course content
  - [ ] Sets up theme settings with preset
  - [ ] Progress indicator during import
- **Reference:** `/home/redman/Edwiser-RemUI/Plugins/local_edwisersiteimporter/`
- **Effort:** 12 hours

### P5-3: License System
- **Status:** `[ ]`
- **Dependencies:** P2-1
- **Task:** Key-based license activation/deactivation
- **Files:**
  - [ ] `classes/controller/LicenseController.php`
  - [ ] License validation API endpoint
  - [ ] Admin settings for license key input
- **Acceptance Criteria:**
  - [ ] License key activation via admin panel
  - [ ] Validates against external API
  - [ ] Displays license status (active/expired/invalid)
  - [ ] Feature gating for unlicensed installs
  - [ ] Grace period after expiry
- **Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/classes/controller/LicenseController.php`
- **Effort:** 12 hours

---

## Phase 6: Launch Preparation (Weeks 17-18)

### P6-1: Documentation
- **Status:** `[ ]`
- **Dependencies:** All phases
- **Task:** User and developer documentation
- **Deliverables:**
  - [ ] Installation guide (PDF)
  - [ ] Admin settings walkthrough
  - [ ] Customizer tutorial
  - [ ] Developer API docs
  - [ ] Changelog
  - [ ] FAQ
- **Effort:** 8 hours

### P6-2: Demo Site
- **Status:** `[ ]`
- **Dependencies:** P5-2
- **Task:** Production demo site with sample content
- **Acceptance Criteria:**
  - [ ] Live at lms.endlessmaker.com
  - [ ] Multiple demo courses with content
  - [ ] All features showcased
  - [ ] Fast loading
- **Effort:** 4 hours

### P6-3: Marketing Assets
- **Status:** `[ ]`
- **Dependencies:** P4-4 (screenshots)
- **Task:** Screenshots, feature videos, comparison page
- **Deliverables:**
  - [ ] 12 product screenshots (from e2e)
  - [ ] Feature comparison table
  - [ ] Landing page copy
- **Effort:** 6 hours

### P6-4: Final QA & Security Audit
- **Status:** `[ ]`
- **Dependencies:** All phases
- **Task:** Complete testing pass
- **Actions:**
  - [ ] Full screenshot comparison (before/after)
  - [ ] XSS testing on all settings inputs
  - [ ] SQL injection testing on all web services
  - [ ] CSRF validation on all write operations
  - [ ] File upload validation (type, size limits)
  - [ ] Capability checks on all admin operations
  - [ ] PHP CodeSniffer (Moodle standard)
  - [ ] Performance profiling under load
- **Acceptance Criteria:**
  - [ ] 0 security vulnerabilities
  - [ ] 0 PHP errors/warnings
  - [ ] All 36 screenshots match expected design
  - [ ] Lighthouse scores: Performance > 85, Accessibility > 95
- **Effort:** 10 hours

---

## Summary

| Phase | Tasks | Est. Hours | Weeks |
|-------|-------|-----------|-------|
| Phase 0: Infrastructure | 6 tasks | 17 hours | Week 1 |
| Phase 1: Core Theme | 10 tasks | 74 hours | Weeks 2-4 |
| Phase 2: Customizer | 3 tasks | 38 hours | Weeks 5-6 |
| Phase 3: Differentiators | 7 tasks | 92 hours | Weeks 7-10 |
| Phase 4: Polish | 6 tasks | 48 hours | Weeks 11-12 |
| Phase 5: Ecosystem | 3 tasks | 40 hours | Weeks 13-16 |
| Phase 6: Launch | 4 tasks | 28 hours | Weeks 17-18 |
| **TOTAL** | **39 tasks** | **337 hours** | **18 weeks** |

---

## Progress Tracking

### How to Track
- Update task status in this file: `[ ]` → `[~]` → `[x]`
- Take screenshots after each Phase 1+ task: `npm run screenshots`
- Compare screenshots against previous run
- Run `npm run lint` before marking any task done
- Run `npm run test` for e2e validation

### Blockers Log
| Date | Task | Blocker | Resolution |
|------|------|---------|------------|
| - | - | - | - |

### Performance Log
| Date | CSS Size | Lighthouse Perf | Lighthouse A11y | Notes |
|------|---------|----------------|-----------------|-------|
| - | - | - | - | Baseline |
