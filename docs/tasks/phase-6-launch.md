# Phase 6: Launch Preparation

> **Timeline:** Weeks 17-18 · **Total Effort:** 28 hours · **Tasks:** 4

---

## P6-1: Documentation
- **Status:** `[ ]`
- **Dependencies:** All phases
- **Effort:** 8 hours
- **Blocks:** None

**Task:** Comprehensive user and developer documentation for theme buyers.

**Deliverables:**
- [ ] **Installation Guide** (PDF) — Step-by-step install on Moodle 4.5+
- [ ] **Admin Settings Walkthrough** — Every settings tab explained with screenshots
- [ ] **Customizer Tutorial** — Visual customizer usage guide (colors, fonts, presets)
- [ ] **Developer API Docs** — Hooks, CSS tokens, extending templates, child theme guide
- [ ] **Changelog** — All features, categorized by phase
- [ ] **FAQ** — Common questions (compatibility, performance, licensing, updates)
- [ ] **Upgrade Guide** — How to update theme without losing customizations

**Files:**
- [ ] `docs/user-guide/` — User-facing documentation (Markdown → PDF)
- [ ] `docs/developer/` — Developer API reference
- [ ] `CHANGELOG.md` — Version history
- [ ] `README.md` — Theme overview, quick start, links to docs

**Acceptance Criteria:**
- [ ] Installation guide covers: requirements, upload, activation, first-time wizard
- [ ] All settings documented with expected behavior
- [ ] Developer docs include: design token list, template override guide, hook reference
- [ ] FAQ covers at least 15 common questions
- [ ] All documentation reviewed for accuracy
- [ ] PDF export available for user guide

---

## P6-2: Demo Site
- **Status:** `[ ]`
- **Dependencies:** `P5-2`
- **Effort:** 4 hours
- **Blocks:** None

**Task:** Production-ready demo site showcasing all Zenith features.

**Actions:**
- [ ] Deploy to `lms.endlessmaker.com` with full demo content
- [ ] Import demo courses, categories, users via P5-2 importer
- [ ] Configure all theme features (customizer, gamification, AI, focus mode)
- [ ] Set up demo accounts: admin (view-only), teacher, student
- [ ] Apply best-looking preset color scheme
- [ ] Optimize images and content for fast loading

**Acceptance Criteria:**
- [ ] Live at `lms.endlessmaker.com`
- [ ] 3-5 demo courses with realistic content (sections, activities, images)
- [ ] All differentiating features visible and functional
- [ ] Guest access enabled for browsing (no login required)
- [ ] Demo login credentials displayed on login page
- [ ] Page load < 3 seconds on first visit
- [ ] Mobile responsive — looks great on all devices
- [ ] Daily automated backup configured

---

## P6-3: Marketing Assets
- **Status:** `[ ]`
- **Dependencies:** `P4-4`
- **Effort:** 6 hours
- **Blocks:** None

**Task:** Create marketing materials for marketplace listing and landing page.

**Deliverables:**
- [ ] **Product Screenshots** — 12 high-quality screenshots from E2E (desktop viewport)
- [ ] **Feature Comparison Table** — Zenith vs Boost vs RemUI vs Moove vs Flavours
- [ ] **Landing Page Copy** — Hero text, feature sections, testimonials placeholder
- [ ] **Marketplace Listing** — Description for Moodle Plugins Directory / ThemeForest
- [ ] **Feature Highlight Cards** — One card per major feature (6-8 cards)
- [ ] **Mobile Screenshots** — 6 mobile viewport screenshots for responsive showcase

**Screenshot List:**
- [ ] Login page (Layout 2 — split panel)
- [ ] Dashboard with stats widgets
- [ ] Course archive (grid view)
- [ ] Course view with focus mode
- [ ] Visual customizer panel open
- [ ] Dark mode dashboard
- [ ] Gamification XP bar and leaderboard
- [ ] AI assistant chat panel
- [ ] Learning path visualization
- [ ] Accessibility toolkit panel
- [ ] Setup wizard
- [ ] Enrollment page (Udemy-style)

**Acceptance Criteria:**
- [ ] All screenshots are 1920×1080 resolution minimum
- [ ] Screenshots show theme at its best (realistic demo content)
- [ ] Feature comparison is accurate and fair
- [ ] Landing page copy highlights unique selling points
- [ ] All assets in `marketing/` directory

---

## P6-4: Final QA & Security Audit
- **Status:** `[ ]`
- **Dependencies:** All phases
- **Effort:** 10 hours
- **Blocks:** None

**Task:** Complete end-to-end testing and security audit before release.

**QA Testing:**
- [ ] Full screenshot comparison (36 screenshots — all pages × all viewports)
- [ ] All features tested manually (checklist from each phase's acceptance criteria)
- [ ] Fresh Moodle 4.5 install → activate theme → verify no errors
- [ ] Upgrade scenario: install v1.0 → simulate upgrade → verify no data loss
- [ ] Multi-user test: admin, teacher, student — all role-specific views work

**Security Audit:**
- [ ] XSS testing on all settings inputs (HTML editors, text fields)
- [ ] SQL injection testing on all web service endpoints
- [ ] CSRF validation on all write operations (sesskey checks)
- [ ] File upload validation (type whitelist, size limits, virus scan path)
- [ ] Capability checks on all admin-only operations
- [ ] License validation endpoint hardened against brute force
- [ ] No secrets in client-side code (JS, templates)

**Code Quality:**
- [ ] PHP CodeSniffer — Moodle coding standard (0 errors)
- [ ] ESLint — 0 errors on all JS modules
- [ ] Stylelint — 0 errors on all SCSS
- [ ] No `console.log` or debug output in production code
- [ ] All strings use language files (grep for hardcoded strings)

**Performance:**
- [ ] Lighthouse Performance > 85 on 5 key pages
- [ ] Lighthouse Accessibility > 95 on 5 key pages
- [ ] CSS < 200KB uncompressed
- [ ] No render-blocking JavaScript
- [ ] Page load < 3 seconds under simulated load (10 concurrent users)

**Acceptance Criteria:**
- [ ] 0 security vulnerabilities found
- [ ] 0 PHP errors, warnings, or notices
- [ ] 0 JavaScript console errors
- [ ] All 36+ screenshots match expected design
- [ ] All lint tools pass with 0 errors
- [ ] Performance targets met
- [ ] Theme ready for marketplace submission
