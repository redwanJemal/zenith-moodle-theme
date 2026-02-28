# Phase 4: Polish & Performance

> **Timeline:** Weeks 11-12 · **Total Effort:** 48 hours · **Tasks:** 6

---

## P4-1: Performance Optimization
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3 tasks
- **Effort:** 8 hours
- **Blocks:** None

**Task:** Optimize CSS size, JS loading, and overall page speed.

**Actions:**
- [ ] Run PurgeCSS on compiled CSS (scan .mustache, .php, .js for used classes)
- [ ] Create safelist for Moodle dynamic classes (`mod-*`, `path-*`, `block-*`, etc.)
- [ ] Lazy-load heavy JS modules: AI assistant, gamification, page builder
- [ ] Convert images to SVG where possible, WebP fallback for rasters
- [ ] Verify `theme_zenith_get_precompiled_css()` returns optimized CSS
- [ ] Test with Moodle caching fully enabled (`$CFG->themedesignermode = false`)
- [ ] Profile with Chrome DevTools and Lighthouse
- [ ] Eliminate unused SCSS imports

**Acceptance Criteria:**
- [ ] CSS < 200KB uncompressed, < 40KB gzipped
- [ ] Lighthouse Performance score > 85
- [ ] First Contentful Paint < 2 seconds
- [ ] No render-blocking JavaScript
- [ ] Time to Interactive < 4 seconds
- [ ] All images optimized (no images > 100KB)

---

## P4-2: RTL Support
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 8 hours
- **Blocks:** None

**Task:** Full right-to-left language support for Arabic, Hebrew, Persian, Urdu.

**Files:**
- [ ] `scss/components/_rtl.scss` — RTL overrides
- [ ] `lang/ar/theme_zenith.php` — Arabic translations

**Acceptance Criteria:**
- [ ] All layouts mirror correctly in RTL mode
- [ ] Navbar: logo right, menu items RTL order, user menu left
- [ ] Drawers: course index on right, blocks on left
- [ ] Footer columns reverse order
- [ ] Text alignment correct (right-aligned)
- [ ] Icons that indicate direction flip correctly (arrows, chevrons)
- [ ] Icons that don't indicate direction stay (search, settings, etc.)
- [ ] Bootstrap 5 RTL utilities work (`ms-*`/`me-*` auto-flip)
- [ ] No horizontal scrollbar in RTL mode

**Screenshots:** Dashboard, course, login in RTL × 3 viewports

---

## P4-3: Multi-Language Support (8 Languages)
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 6 hours
- **Blocks:** None

**Task:** Create language string files for 8 languages.

**Languages:**
- [ ] `lang/en/theme_zenith.php` — English (primary, already exists)
- [ ] `lang/ar/theme_zenith.php` — Arabic
- [ ] `lang/es/theme_zenith.php` — Spanish
- [ ] `lang/fr/theme_zenith.php` — French
- [ ] `lang/de/theme_zenith.php` — German
- [ ] `lang/pt_br/theme_zenith.php` — Portuguese (Brazil)
- [ ] `lang/zh_cn/theme_zenith.php` — Chinese (Simplified)
- [ ] `lang/ja/theme_zenith.php` — Japanese

**Acceptance Criteria:**
- [ ] All theme-specific strings translated (not Moodle core strings)
- [ ] Language switcher in navbar works correctly
- [ ] No hardcoded strings in any template (all use `{{#str}}`)
- [ ] No hardcoded strings in any PHP file (all use `get_string()`)
- [ ] RTL languages (Arabic) trigger RTL layout

---

## P4-4: Mobile-First Responsive Audit
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3 tasks
- **Effort:** 10 hours
- **Blocks:** P6-3

**Task:** Comprehensive mobile responsiveness testing across 6 viewports.

**Viewports:**
- [ ] iPhone SE — 375px
- [ ] iPhone 14 — 390px
- [ ] iPad — 768px
- [ ] iPad Pro — 1024px
- [ ] Laptop — 1440px
- [ ] 4K — 2560px

**Pages to Verify (12 pages × 6 viewports = 72 checks):**
- [ ] Login page (all 3 layouts)
- [ ] Dashboard
- [ ] Course list / archive (grid + list views)
- [ ] Course view (with drawers)
- [ ] Activity page (assignment, quiz, forum)
- [ ] User profile
- [ ] Messages
- [ ] Calendar
- [ ] Admin settings
- [ ] Footer
- [ ] Focus mode
- [ ] Enrollment page

**Acceptance Criteria:**
- [ ] No horizontal scroll on any viewport
- [ ] Touch targets ≥ 44px × 44px (WCAG 2.2)
- [ ] Text readable without pinch-zoom (min 16px body text)
- [ ] All 36+ screenshots captured and reviewed
- [ ] Drawers collapse properly (overlay on mobile, push on desktop)
- [ ] Forms usable on mobile (inputs not cut off, buttons reachable)
- [ ] Tables scroll horizontally within container (not page)
- [ ] Images don't overflow containers
- [ ] Modal dialogs fit within viewport

---

## P4-5: Accessibility Audit (WCAG 2.2 AA)
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3 tasks
- **Effort:** 10 hours
- **Blocks:** None

**Task:** Full WCAG 2.2 AA compliance audit with automated + manual testing.

**Automated Testing:**
- [ ] axe DevTools scan on all 12 page types
- [ ] WAVE evaluation on all 12 page types
- [ ] Lighthouse Accessibility audit
- [ ] Color contrast checker on all color combinations

**Manual Testing:**
- [ ] Full keyboard navigation test (Tab through every page)
- [ ] Screen reader test with NVDA (Windows) or VoiceOver (Mac)
- [ ] Focus indicator visibility test (every interactive element)
- [ ] Heading hierarchy check (h1 → h2 → h3, no skips)
- [ ] Form label association check
- [ ] Image alt text completeness check
- [ ] ARIA landmark verification
- [ ] Skip navigation link test

**Acceptance Criteria:**
- [ ] axe: 0 critical violations, 0 serious violations
- [ ] Lighthouse Accessibility > 95
- [ ] All interactive elements reachable via keyboard
- [ ] All images have meaningful alt text
- [ ] Color contrast ≥ 4.5:1 (normal text), ≥ 3:1 (large text / UI components)
- [ ] Focus indicators visible (2px solid outline minimum)
- [ ] Heading hierarchy correct (no skipped levels)
- [ ] All form inputs have associated labels
- [ ] ARIA landmarks: banner, navigation, main, contentinfo
- [ ] Skip navigation link present and functional
- [ ] `prefers-reduced-motion` respected

---

## P4-6: Browser Compatibility Testing
- **Status:** `[ ]`
- **Dependencies:** All Phase 1-3 tasks
- **Effort:** 6 hours
- **Blocks:** None

**Task:** Test across all major browsers to ensure consistent rendering.

**Browsers:**
- [ ] Chrome (latest) — Desktop
- [ ] Firefox (latest) — Desktop
- [ ] Safari (latest) — Desktop
- [ ] Edge (latest) — Desktop
- [ ] Chrome — Android (phone + tablet)
- [ ] Safari — iOS (iPhone + iPad)

**Test Checklist per Browser:**
- [ ] Layout renders correctly (no broken grids, overlaps)
- [ ] All JS features work (drawers, dark mode, customizer)
- [ ] CSS animations smooth (transitions, hover effects)
- [ ] Dark mode works correctly
- [ ] Forms submit properly
- [ ] Modals open/close
- [ ] Dropdowns work (especially user menu)
- [ ] Font loading works (Inter, custom icon font)
- [ ] CSS custom properties supported

**Acceptance Criteria:**
- [ ] All browsers pass all test checklist items
- [ ] No browser-specific CSS hacks needed
- [ ] Console: no JavaScript errors
- [ ] No layout differences > 2px between browsers
