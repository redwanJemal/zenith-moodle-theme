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
- **Status:** `[x]` Completed 2026-02-28
- **Dependencies:** `P2-1`
- **Effort:** 20 hours
- **Blocks:** None

**Task:** Live preview visual customizer with AJAX save — the premium feature.

**Customizer Sections (6 sections, 24 settings):**
- [x] **Presets** — 7 one-click color schemes (Zenith, Ocean, Forest, Sunset, Rose, Midnight, Slate)
- [x] **Colors** (8) — Primary, secondary, success, warning, danger, info, navbar bg, footer bg
- [x] **Typography** (5) — Font family (10 Google Fonts + system-ui), size, heading weight, body weight, line height
- [x] **Buttons** (4) — Border radius, padding Y, padding X, font weight
- [x] **Header** (4) — Height, shadow toggle, border toggle, style (light/dark)
- [x] **Footer** (3) — Text color, link color, border color

**PHP Files:**
- [x] `classes/customizer/customizer.php` — Static controller with 24 settings, sanitization, CSS generation, settings for JS
- [x] `classes/customizer/color_utils.php` — HSL color math: hex↔HSL conversion, shade/tint, palette generation
- [x] `classes/customizer/presets.php` — 7 preset definitions with flatten() and get_for_template()
- [x] `classes/external/customizer_api.php` — 3 AJAX endpoints (save, get, reset) with capability checks

**JS Files (AMD modules):**
- [x] `amd/src/customizer/main.js` — Panel controller: open/close, tabs, input binding, AJAX save/reset, focus trap
- [x] `amd/src/customizer/preview.js` — Live preview: CSS custom property injection, snapshot/revert, Google Fonts
- [x] `amd/src/customizer/presets.js` — 7 preset definitions mirroring PHP
- [x] `amd/src/customizer/smartcolor.js` — HSL math mirroring PHP color_utils

**Templates:**
- [x] `templates/customizer/panel.mustache` — Main panel with header, 6 tabs, content, footer actions
- [x] `templates/customizer/section_presets.mustache` — 7 preset cards with color swatches
- [x] `templates/customizer/section_colors.mustache` — 8 color pickers with hex inputs
- [x] `templates/customizer/section_typography.mustache` — Font family select, size/weight/height controls
- [x] `templates/customizer/section_buttons.mustache` — Radius/padding/weight controls with live button preview
- [x] `templates/customizer/section_header.mustache` — Height, shadow, border, style controls
- [x] `templates/customizer/section_footer.mustache` — 3 footer color controls

**Services:**
- [x] `db/services.php` — 3 AJAX endpoints: save, get, reset

**Styling:**
- [x] `scss/components/_customizer.scss` — 533 lines BEM-structured panel styling

**Integration:**
- [x] `layout/drawers.php` — Passes customizer context (capability check, presets, settings JSON, Google Font URL)
- [x] `templates/theme_boost/drawers.mustache` — Renders panel, initializes JS with settings data
- [x] `templates/theme_boost/navbar.mustache` — Customizer launch button (pen icon) for admins
- [x] `lib.php` — generate_css() injects CSS custom property overrides via extra SCSS

**Acceptance Criteria:**
- [x] Customizer panel opens as sidebar overlay (360px desktop, full-screen mobile)
- [x] Color changes preview instantly (CSS variable injection on :root)
- [x] Font changes preview instantly (Google Fonts dynamic load)
- [x] Save button persists all settings to database via AJAX
- [x] Reset to defaults works (global reset with confirmation)
- [x] 7 preset color schemes (one-click apply with visual card selection)
- [x] Smart color: generate hover, active, light, subtle variants from base colors
- [x] Generated CSS is valid, minimal, and properly scoped (:root { --z-*: val; })
- [x] Mobile friendly (full-screen customizer on mobile via CSS media query)
- [x] Keyboard accessible (Escape to close, Tab focus trap, ARIA labels)

**Key Decisions:**
- Used static methods instead of singleton pattern — simpler, no state to manage
- Settings stored with `cust_` prefix in theme_zenith plugin config
- AMD `define()` syntax for JS modules (Moodle 4.5 uses RequireJS, not native ES modules)
- `M.str.theme_zenith` for string pre-loading instead of deprecated `M.util.set_string`
- Used `closebuttontitle` instead of `close` for Moodle 4.5+ string compatibility

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
