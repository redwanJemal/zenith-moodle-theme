# Phase 2: Customizer & Settings

> **Timeline:** Weeks 5-6 · **Total Effort:** 38 hours · **Tasks:** 3

---

## P2-1: Settings Panel (Tabbed Admin UI)
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P1-6`
- **Effort:** 10 hours
- **Blocks:** P2-2, P2-3, P5-3

**Task:** Multi-tab admin settings interface for all theme configuration.

**Settings Tabs (6 tabs, 37 settings):**
- [x] **General** (6 settings) — Brand colour, secondary colour, logo, logo mini, favicon, Google Analytics ID
- [x] **Homepage** (8 settings) — Hero section (enable, title, subtitle, bg image, CTA button text/URL), about section (enable, content), featured courses (enable, IDs)
- [x] **Courses** (5 settings) — Archive layout (grid/list), courses per page, card animations, category badge, instructor names
- [x] **Footer** (8 settings) — Footer content (HTML), copyright text, show branding, social media URLs (Facebook, X, LinkedIn, YouTube, Instagram)
- [x] **Login** (4 settings) — Layout selection (center/left/right), background image, heading text, description HTML
- [x] **Advanced** (4 settings) — Raw SCSS pre, Raw SCSS post, custom JS, developer mode toggle

**Files Modified:**
- [x] `settings.php` — Full tabbed settings using `theme_boost_admin_settingspage_tabs`
- [x] `lang/en/theme_zenith.php` — Added 40+ new language strings for all settings
- [x] `lib.php` — Updated pluginfile to serve herobackgroundimage; added secondarycolor to pre-SCSS injection

**Not Needed:**
- `classes/admin_settingspage_tabs.php` — Boost's built-in `theme_boost_admin_settingspage_tabs` class works perfectly, no custom class required

**Acceptance Criteria:**
- [x] All settings save and persist correctly
- [x] File uploads work (logo, favicon, backgrounds) via `admin_setting_configstoredfile`
- [x] Color pickers work via `admin_setting_configcolourpicker`
- [x] HTML editors work for footer content and about section
- [x] Select dropdowns for layout choices
- [x] Settings organized logically in 6 tabs
- [x] Descriptive help text on each setting
- [x] Active tab remembered on save (via Boost's `activetab` URL parameter)

**Key Decisions:**
- Used Boost's `theme_boost_admin_settingspage_tabs` directly instead of creating a custom class — it handles tab rendering, active tab persistence, and settings merging already
- Boost's `admin_setting_tabs.mustache` template renders Bootstrap nav-tabs + tab-content
- Social media URLs validated with PARAM_URL
- Secondary colour setting added with SCSS variable injection via lib.php

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/settings.php`

---

## P2-2: Visual Customizer (Live Preview)
- **Status:** `[ ]`
- **Dependencies:** `P2-1`
- **Effort:** 20 hours
- **Blocks:** None

**Task:** Live preview visual customizer with AJAX save — the premium feature.

**Customizer Sections:**
- [ ] **Colors** — Primary, secondary, background, text, border, accent
- [ ] **Typography** — Font family (Google Fonts), sizes, weights, line heights
- [ ] **Buttons** — Colors, border radius, padding, hover effects
- [ ] **Header** — Background, height, logo size, layout
- [ ] **Footer** — Background, text color, font family
- [ ] **Quick Setup** — One-click presets (5+ color schemes)

**PHP Files:**
- [ ] `classes/customizer/customizer.php` — Singleton controller with traits
- [ ] `classes/customizer/elements/color.php` — Color picker element
- [ ] `classes/customizer/elements/fontselect.php` — Font selector element
- [ ] `classes/customizer/elements/range.php` — Range slider element
- [ ] `classes/customizer/elements/select.php` — Dropdown element
- [ ] `classes/customizer/process/colors.php` — Generate color CSS
- [ ] `classes/customizer/process/typography.php` — Generate typography CSS
- [ ] `classes/customizer/process/buttons.php` — Generate button CSS
- [ ] `classes/customizer/external/api.php` — AJAX save/load endpoint

**JS Files:**
- [ ] `amd/src/customizer/main.js` — Controller
- [ ] `amd/src/customizer/colors.js` — Color picker + live preview
- [ ] `amd/src/customizer/typography.js` — Font preview
- [ ] `amd/src/customizer/smartcolor.js` — Auto-generate palette from primary

**Templates:**
- [ ] `templates/customizer/main.mustache` — Customizer panel UI
- [ ] `templates/customizer/elements/*.mustache` — Control templates

**Services:**
- [ ] `db/services.php` — `theme_zenith_customizer_save_settings` AJAX endpoint

**Acceptance Criteria:**
- [ ] Customizer panel opens as sidebar overlay
- [ ] Color changes preview instantly (CSS variable injection)
- [ ] Font changes preview instantly (Google Fonts dynamic load)
- [ ] Save button persists all settings to database via AJAX
- [ ] Reset to defaults works (per-section and global)
- [ ] 5+ preset color schemes (one-click apply)
- [ ] Smart color: generate secondary, accent, hover from primary
- [ ] Generated CSS is valid, minimal, and properly scoped
- [ ] Mobile friendly (full-screen customizer on mobile)
- [ ] Keyboard accessible

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/classes/customizer/`

---

## P2-3: Setup Wizard
- **Status:** `[ ]`
- **Dependencies:** `P2-1`
- **Effort:** 8 hours
- **Blocks:** None

**Task:** Guided first-time setup wizard for new installations.

**Wizard Steps:**
1. [ ] **Welcome** — Theme name, version, what's included
2. [ ] **Branding** — Upload logo + favicon, enter site name
3. [ ] **Colors** — Choose from preset color schemes or pick custom
4. [ ] **Homepage** — Select homepage layout, configure hero section
5. [ ] **Complete** — Success screen with next steps (links to settings, customizer)

**Files:**
- [ ] `classes/setupwizard.php` — Wizard state management
- [ ] `amd/src/setupwizard.js` — Step navigation, AJAX save
- [ ] `scss/components/_setupwizard.scss` — Wizard UI styling
- [ ] `templates/setupwizard/main.mustache` — Wizard container
- [ ] `templates/setupwizard/step_*.mustache` — Per-step templates

**Acceptance Criteria:**
- [ ] Shows automatically on first theme activation (modal)
- [ ] Can be skipped at any step
- [ ] Can be re-launched from Settings → General tab
- [ ] Progress indicator shows current step
- [ ] Settings saved at each step (not lost on back navigation)
- [ ] Final step applies all chosen settings
- [ ] Mobile friendly (responsive wizard)
- [ ] Celebration animation on completion

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/setupwizard/`
