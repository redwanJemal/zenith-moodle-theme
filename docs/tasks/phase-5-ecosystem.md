# Phase 5: Ecosystem Plugins

> **Timeline:** Weeks 13-16 · **Total Effort:** 40 hours · **Tasks:** 3

---

## P5-1: Course Format Plugin
- **Status:** `[ ]`
- **Dependencies:** `P1-10`
- **Effort:** 16 hours
- **Blocks:** None

**Task:** Custom course format plugin with card/grid/tab views for course sections.

**Location:** `plugins/format_zenith/`

**Plugin Structure:**
- [ ] `version.php` — Component: format_zenith
- [ ] `lib.php` — Format callbacks (sections, features)
- [ ] `renderer.php` — Custom section rendering
- [ ] `classes/output/` — Renderer overrides
- [ ] `templates/` — Section card/tab templates
- [ ] `scss/styles.scss` — Format-specific styling (uses `--z-*` tokens)
- [ ] `lang/en/format_zenith.php` — Language strings
- [ ] `db/install.php`, `db/upgrade.php`
- [ ] `amd/src/` — Section toggle, tab switching

**Acceptance Criteria:**
- [ ] Card view: each section displayed as a card in a grid
- [ ] Tab view: sections as horizontal tabs
- [ ] List view: traditional collapsible list (default Moodle feel)
- [ ] Collapse/expand sections with smooth animation
- [ ] Section completion indicators
- [ ] Consistent with Zenith design tokens (`--z-*`)
- [ ] Mobile responsive (tabs become accordion on mobile)
- [ ] Works with completion tracking
- [ ] Teacher editing mode works (add/move/delete sections)

**Reference:** `/home/redman/Edwiser-RemUI/Plugins/format_remuiformat/`

---

## P5-2: Demo Content Importer
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 12 hours
- **Blocks:** P6-2

**Task:** One-click demo site import for new installations.

**Location:** `plugins/local_zenithimporter/`

**Plugin Structure:**
- [ ] `version.php` — Component: local_zenithimporter
- [ ] `index.php` — Import UI page
- [ ] `classes/importer.php` — Core import logic
- [ ] `classes/external/import.php` — AJAX import endpoint
- [ ] `templates/import_page.mustache` — Import UI
- [ ] `amd/src/importer.js` — Progress tracking
- [ ] `demo_data/` — JSON/XML demo content definitions

**Demo Content:**
- [ ] 3-5 demo courses with sections and activities
- [ ] 2-3 course categories
- [ ] Demo users (teacher, student)
- [ ] Course images (placeholder stock photos)
- [ ] Theme settings preset applied

**Acceptance Criteria:**
- [ ] Single "Import Demo Content" button in admin
- [ ] Progress bar during import
- [ ] Creates: categories, courses, users, enrollments
- [ ] Applies Zenith theme settings preset
- [ ] Idempotent: running twice doesn't duplicate content
- [ ] Can be uninstalled cleanly (removes demo content option)
- [ ] Mobile friendly import UI

**Reference:** `/home/redman/Edwiser-RemUI/Plugins/local_edwisersiteimporter/`

---

## P5-3: License System
- **Status:** `[ ]`
- **Dependencies:** `P2-1`
- **Effort:** 12 hours
- **Blocks:** None

**Task:** Key-based license activation and validation system.

**Files:**
- [ ] `classes/controller/LicenseController.php` — License logic
- [ ] `classes/license/validator.php` — External API validation
- [ ] License key input field in `settings.php` → General tab
- [ ] `templates/license_status.mustache` — Status display widget
- [ ] `amd/src/license.js` — Activation AJAX

**License Tiers:**
- Starter (1 site), Professional (3 sites), Business (10 sites), Enterprise (unlimited)

**Acceptance Criteria:**
- [ ] License key input field in admin settings
- [ ] Activate button sends key to external validation API
- [ ] Status display: Active (green), Expired (yellow), Invalid (red), Not activated (gray)
- [ ] Shows: license tier, expiry date, sites used/allowed
- [ ] Deactivate button releases license for current site
- [ ] Feature gating: customizer locked without valid license (with "Activate to unlock" message)
- [ ] Grace period: 14 days after expiry before features lock
- [ ] Periodic re-validation (daily cron task)
- [ ] No license = theme still works, just premium features locked

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/classes/controller/LicenseController.php`
