# Zenith Moodle Theme — Progress Tracker

> **Last Updated:** 2026-02-28
> **Status Legend:** `[ ]` Pending · `[~]` In Progress · `[x]` Done · `[!]` Blocked

---

## Dashboard

| Phase | Progress | Tasks | Done | Hours Est. | Weeks |
|-------|----------|-------|------|-----------|-------|
| [P0: Infrastructure](tasks/phase-0-infrastructure.md) | ████████░░ 83% | 6 | 5/6 | 17h | Week 1 |
| [P1: Core Theme](tasks/phase-1-core-theme.md) | ██████████ 100% | 10 | 10/10 | 74h | Weeks 2-4 |
| [P2: Customizer](tasks/phase-2-customizer.md) | ███████░░░ 67% | 3 | 2/3 | 38h | Weeks 5-6 |
| [P3: Differentiators](tasks/phase-3-differentiators.md) | ░░░░░░░░░░ 0% | 7 | 0/7 | 92h | Weeks 7-10 |
| [P4: Polish](tasks/phase-4-polish.md) | ░░░░░░░░░░ 0% | 6 | 0/6 | 48h | Weeks 11-12 |
| [P5: Ecosystem](tasks/phase-5-ecosystem.md) | ░░░░░░░░░░ 0% | 3 | 0/3 | 40h | Weeks 13-16 |
| [P6: Launch](tasks/phase-6-launch.md) | ░░░░░░░░░░ 0% | 4 | 0/4 | 28h | Weeks 17-18 |
| **TOTAL** | **████░░░░░░ 44%** | **39** | **17/39** | **337h** | **18 weeks** |

---

## Dependency Graph

```
P0-1 (Docker Dev) ──┬──→ P0-2 (Coolify Deploy)
                    ├──→ P0-3 (Theme Scaffold) ──→ P0-4 (Build Tools) ──┐
                    └──→ P0-5 (E2E Screenshots)                        │
                                                                        ▼
                         P0-4 + P0-5 ──→ P0-6 (CI/CD)

P0-3 ──→ P1-1 (Design Tokens) ──→ P1-2 (Bootstrap) ──┬──→ P1-3 (Navbar)
                                                       └──→ P1-4 (Footer)
                                                            P1-7 (Login)

P1-3 ──→ P1-5 (Drawers)
P1-3 + P1-4 + P1-5 ──→ P1-6 (Layouts)
P1-1 + P1-3 + P1-4 + P1-5 ──→ P1-8 (Dark Mode)
P1-6 ──→ P1-9 (Template Overrides) ──→ P1-10 (Course Cards)

P1-6 ──→ P2-1 (Settings Panel) ──→ P2-2 (Visual Customizer)
                                  ──→ P2-3 (Setup Wizard)

P1-6 ──→ P3-1 (Focus Mode)
P1-6 + P1-9 ──→ P3-2 (Dashboard)
P1-6 ──→ P3-3 (Enrollment Page)
P1-1 ──→ P3-4 (Accessibility Toolkit)
P1-3 + P1-6 ──→ P3-5 (Gamification)
P1-6 ──→ P3-6 (AI Assistant)
P1-6 ──→ P3-7 (Learning Paths)

All P1-P3 ──→ P4-1 (Performance)
              P4-4 (Mobile Audit)
              P4-5 (A11y Audit)
              P4-6 (Browser Testing)
P1-6 ──→ P4-2 (RTL)
P1-6 ──→ P4-3 (Languages)

P1-10 ──→ P5-1 (Course Format Plugin)
P1-6 ──→ P5-2 (Demo Importer)
P2-1 ──→ P5-3 (License System)

All ──→ P6-1 (Docs), P6-4 (Final QA)
P5-2 ──→ P6-2 (Demo Site)
P4-4 ──→ P6-3 (Marketing Assets)
```

---

## What To Work On Next

> Pick the first task that has all dependencies met (marked `[x]`).
> Currently: **P2-3 (Setup Wizard)** — unblocked (P2-1 done). Also unblocked: P3-1 through P3-7 (Differentiators).

---

## Blockers Log

| Date | Task ID | Blocker Description | Status | Resolution |
|------|---------|-------------------|--------|------------|
| 2026-02-28 | P0-1 | `bitnami/moodle:4.5` image discontinued (Sept 2025) | Resolved | Switched to `moodlehq/moodle-php-apache:8.3` + custom Dockerfile |
| 2026-02-28 | P0-1 | `bitnamilegacy/moodle:4.5` bootstrap loop (config.php missing) | Resolved | Custom entrypoint.sh generates config.php + runs install_database.php |
| 2026-02-28 | P0-1 | `admin@localhost` rejected by Moodle email validation | Resolved | Changed to `admin@example.com` |
| 2026-02-28 | P0-3 | Bind-mounted theme dir owned by www-data, host can't edit | Resolved | Removed `chown theme/` from entrypoint.sh, fix via `docker exec chown 1000:1000` |
| 2026-02-28 | P0-3 | Tabbed settings.php doesn't work for child themes | Resolved | Use flat `$settings->add($setting)` instead of tab pages |
| 2026-02-28 | P0-2 | Old Coolify compose used broken bitnamilegacy/moodle:4.5 image | Resolved | Replaced with moodlehq/moodle-php-apache:8.3 + custom Dockerfile |
| 2026-02-28 | P0-2 | `$CFG->reverseproxy = true` caused 500 error | Resolved | Removed invalid setting; only `$CFG->sslproxy = true` needed |
| 2026-02-28 | P1-1 | SCSS `@import` directives not resolved by Moodle SCSS compiler | Resolved | Updated `lib.php` to inline `@import` via `preg_replace_callback()` |
| 2026-02-28 | P1-7 | `#page-wrapper #page` has two-ID specificity overriding login flex-direction | Resolved | Nested layout rules under `#page-wrapper.z-login` for matching specificity |
| 2026-02-28 | P1-7 | Login screenshot showed "already logged in" dialog instead of form | Resolved | Use separate unauthenticated browser context in screenshot generator |
| 2026-02-28 | P2-2 | Customizer JS files used ES Module syntax but Moodle 4.5 RequireJS expects AMD | Resolved | Rewrote all 4 JS files to use `define()` AMD syntax |
| 2026-02-28 | P2-2 | `M.util.set_string` doesn't exist in Moodle 4.5 | Resolved | Set strings directly on `M.str.theme_zenith` object |
| 2026-02-28 | P2-2 | `close, core` string identifier missing in Moodle 4.5 | Resolved | Changed to `closebuttontitle, core` |

---

## Performance Log

| Date | CSS Size (raw) | CSS Size (gzip) | Lighthouse Perf | Lighthouse A11y | FCP | Notes |
|------|---------------|-----------------|----------------|-----------------|-----|-------|
| — | — | — | — | — | — | Baseline not yet set |

**Targets:** CSS < 200KB raw / < 40KB gzip · Lighthouse Perf > 85 · Lighthouse A11y > 95 · FCP < 2s

---

## Screenshot Changelog

| Date | Viewport | Pages Changed | Notes |
|------|----------|--------------|-------|
| 2026-02-28 | all | 12 pages × 3 viewports | Baseline screenshots with default Boost styling (Zenith scaffold) |
| 2026-02-28 | all | 12 pages × 3 viewports | Post-P1-1: Design tokens + dark mode tokens compiled into CSS |
| 2026-02-28 | all | 12 pages × 3 viewports + 3 drawer-specific | Post-P1-5: Drawer system styled with --z-drawer-* tokens |
| 2026-02-28 | all | 12 pages × 3 viewports | Post-P1-6: Custom layout files, demo data with 13 courses |
| 2026-02-28 | all | 12 pages × 3 viewports + 9 login layouts | Post-P1-7: Login page with 3 layout variants (center/left/right × 3 viewports) |
| 2026-02-28 | all | 12 pages × 3 viewports + 15 dark mode | Post-P1-8: Dark mode with toggle, anti-flash, preference persistence |

**Command:** `cd e2e && npm run screenshots`

---

## Weekly Status

| Week | Date | Tasks Completed | Notes |
|------|------|----------------|-------|
| 1 | 2026-02-28 | P0-1, P0-3, P0-4, P0-5 | Docker dev + theme scaffold + build tools + 36 baseline screenshots |
| 2 | 2026-02-28 | P1-1, P1-2, P1-3, P1-4, P1-5, P1-6 | Design tokens + Bootstrap + navbar + footer + drawers + layout files |
| 3 | 2026-02-28 | P1-7, P1-8 | Login page: 3 configurable layouts + Dark mode: toggle, anti-flash, preference persistence |
| 4 | 2026-02-28 | P1-9, P1-10 | Template overrides + premium visual polish + Course archive: renderer override, toolbar, AJAX search, grid/list toggle. **Phase 1 complete!** |
| 4 | 2026-02-28 | P0-2 | Production deployment: lms.endlessmaker.com live with Zenith theme via Traefik + Cloudflare HTTPS |
| 5 | 2026-02-28 | P2-1 | Tabbed settings panel: 6 tabs (General, Homepage, Courses, Footer, Login, Advanced), 37 settings |
| 5 | 2026-02-28 | P2-2 | Visual Customizer: live preview sidebar, 24 settings across 6 sections, 7 presets, AJAX save/reset, CSS custom property injection |
