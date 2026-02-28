# Phase 0: Infrastructure & Scaffolding

> **Timeline:** Week 1 · **Total Effort:** 17 hours · **Tasks:** 6

---

## P0-1: Docker Development Environment
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** None
- **Effort:** 2 hours
- **Blocks:** P0-2, P0-3, P0-5

**Task:** Set up local Moodle 4.5 LTS with MariaDB via Docker.

**Acceptance Criteria:**
- [x] `docker compose up -d` starts Moodle on port 8081
- [x] MariaDB running on port 3306
- [x] Admin login works at http://localhost:8081 (admin / Admin123!)
- [x] Theme source bind-mounted at `/var/www/html/theme/zenith`
- [x] Health checks passing for both containers
- [x] `docker compose down` cleanly stops everything

**Files:** `docker/docker-compose.yml`, `docker/Dockerfile.dev`, `docker/entrypoint.sh`

**Issues Encountered:**
1. `bitnami/moodle:4.5` image no longer exists — Bitnami discontinued free container images (Sept 2025). Moved to `bitnamilegacy/moodle:4.5` but it had a broken bootstrap loop (`config.php` not generated).
2. Switched to `moodlehq/moodle-php-apache:8.3` (official MoodleHQ dev image) + custom Dockerfile that downloads Moodle 4.5 source, generates `config.php`, and runs `install_database.php` CLI.
3. `admin@localhost` rejected by Moodle email validation — changed to `admin@example.com`.
4. `APACHE_DOCUMENT_ROOT` env var required by moodlehq image — set to `/var/www/html`.

---

## P0-2: Production Deployment (Coolify)
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P0-1`
- **Effort:** 3 hours
- **Blocks:** P6-2

**Task:** Deploy to lms.endlessmaker.com via Coolify with Traefik proxy.

**Acceptance Criteria:**
- [x] `docker-compose.coolify.yml` deploys successfully
- [x] Containers on `coolify` external network
- [x] `traefik.enable=false` labels set (routing via dynamic file)
- [x] Traefik config added: `lms.endlessmaker.com` → `zenith-moodle:80`
- [x] HTTPS working (Let's Encrypt via Cloudflare + Traefik)
- [x] Health checks passing
- [x] No port conflicts with TadHub (8080, 8090, 5432 already used)

**Files Created/Modified:**
- [x] `docker/docker-compose.coolify.yml` — Production compose using moodlehq/moodle-php-apache:8.3 + custom Dockerfile
- [x] `docker/Dockerfile.prod` — Production Dockerfile with Zenith theme baked in, PHP tuning
- [x] `docker/entrypoint.prod.sh` — Production entrypoint with auto-install, upgrade check, SSL proxy config
- [x] Traefik dynamic config at `/data/coolify/proxy/dynamic/moodle-lucent.yaml` — Routes lms.endlessmaker.com → zenith-moodle:80

**Issues Encountered:**
1. Old `docker-compose.coolify.yml` used broken `bitnamilegacy/moodle:4.5` image — replaced with working moodlehq setup from P0-1.
2. `$CFG->reverseproxy = true` caused "Reverse proxy enabled" 500 error — this is not a valid Moodle config option (Bitnami-specific). Removed; only `$CFG->sslproxy = true` is needed.
3. `$SITE` variable undefined during install — fixed settings.php with `isset()` guard.
4. Dev and prod compose files in same directory caused project name collision — use `-p zenith-dev` for dev.

**Reference:** TadHub Coolify pattern at `/home/redman/TadHub/docker/docker-compose.coolify.yml`

---

## P0-3: Theme Scaffold (Boost Child)
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P0-1`
- **Effort:** 4 hours
- **Blocks:** P0-4, P1-1

**Task:** Create minimal Boost child theme that installs and activates without errors.

**Files Created:**
- [x] `theme/zenith/version.php` — Version 2026022800, requires Moodle 4.5+, depends on theme_boost
- [x] `theme/zenith/config.php` — 19 layouts, SCSS callbacks, Boost parent, FontAwesome icons
- [x] `theme/zenith/lib.php` — 4 SCSS callbacks + pluginfile handler (logo, favicon, backgrounds)
- [x] `theme/zenith/settings.php` — Brand color picker, logo uploads, raw SCSS fields
- [x] `theme/zenith/lang/en/theme_zenith.php` — Base language strings (14 strings)
- [x] `theme/zenith/scss/preset/default.scss` — Primary color override, inherits Boost SCSS
- [x] `theme/zenith/style/moodle.css` — Precompiled CSS fallback (placeholder)
- [x] `theme/zenith/pix/screenshot.png` — Theme selector thumbnail (500×400 purple)

**Acceptance Criteria:**
- [x] Theme appears in Site Admin → Appearance → Theme selector
- [x] Activating theme doesn't break any page
- [x] All Moodle core pages render correctly (dashboard, course, login, admin)
- [x] No PHP errors, warnings, or notices
- [x] Theme info shows version 1.0.0

**Issues Encountered:**
1. Bind-mounted theme directory owned by www-data (from Docker entrypoint) — prevented host editing. Fixed by removing `chown theme/` from entrypoint and using `docker exec chown 1000:1000` to fix.
2. Initial `settings.php` used tabbed pages (`$settings->add($page)`) but Moodle's theme settings injection expects flat settings on the `$settings` object. Fixed by adding settings directly to `$settings`.
3. Boost child themes inherit parent layout files automatically — no need to create layout PHP files in the child theme until we want to override them.

---

## P0-4: Build Tooling
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P0-3`
- **Effort:** 3 hours
- **Blocks:** P0-6

**Task:** Set up Grunt for AMD compilation, SCSS compilation, linting.

**Files Created:**
- [x] `theme/zenith/package.json` — grunt, uglify, eslint, stylelint, watch
- [x] `theme/zenith/Gruntfile.js` — Tasks: amd (lint+minify), lint, watch
- [x] `theme/zenith/.stylelintrc.json` — SCSS lint (stylelint-config-standard-scss)
- [x] `theme/zenith/.eslintrc.json` — JS lint (ES2022, AMD globals)
- [x] `theme/zenith/amd/src/init.js` — Sample AMD module for build testing

**Acceptance Criteria:**
- [x] `npm install` installs all dependencies
- [x] `npx grunt amd` compiles `amd/src/*.js` → `amd/build/*.min.js` (with sourcemaps)
- [x] `npm run lint:scss` checks SCSS with zero errors
- [x] `npm run lint:js` checks JS with zero errors
- [x] `npx grunt watch` watches for file changes

**Notes:** SCSS compilation is handled by Moodle's PHP SCSS compiler (not Grunt). The `grunt amd` task handles JS minification. Theme-level CSS purging will be added in P4-1.

---

## P0-5: E2E Screenshot Setup
- **Status:** `[x]` ✅ Completed 2026-02-28
- **Dependencies:** `P0-1`
- **Effort:** 2 hours
- **Blocks:** P0-6

**Task:** Set up Playwright to capture screenshots of all major pages across 3 viewports.

**Files:** `e2e/take-screenshots.ts`, `e2e/package.json`

**Acceptance Criteria:**
- [x] `cd e2e && npm install && npx playwright install chromium` sets up
- [x] `npm run screenshots` runs without errors
- [x] Captures 12 pages × 3 viewports = 36 screenshots
- [x] Screenshots saved to `test-screenshots/{desktop,tablet,mobile}/`
- [x] Login automation works (fills username/password, clicks login)
- [x] Mobile viewport (375px) properly captures responsive layout
- [x] Script handles errors gracefully (continues on page load failure)

**Notes:** All 36 screenshots captured successfully on first run. Baseline screenshots now available for visual regression comparison.

---

## P0-6: CI/CD Pipeline
- **Status:** `[x]` Completed 2026-02-28
- **Dependencies:** `P0-4`, `P0-5`
- **Effort:** 3 hours
- **Blocks:** None

**Task:** GitHub Actions workflow for automated lint, build, and test on every PR.

**Files Created:**
- [x] `.github/workflows/ci.yml` — Lint JS + SCSS, build AMD, verify build is up to date

**Pre-existing Lint Fixes:**
- [x] `scss/components/_drawer.scss` — Added `stylelint-disable selector-id-pattern` for Moodle core IDs
- [x] `scss/components/_login.scss` — Removed empty comment line
- [x] `scss/components/_premium-polish.scss` — Added empty line before `to` in `@keyframes`
- [x] `scss/components/_customizer.scss` — Added empty line before nested rule
- [x] `scss/components/_setupwizard.scss` — Added empty lines before `to` in `@keyframes`
- [x] `scss/_dark-mode.scss` — Added `stylelint-disable no-duplicate-selectors` + `selector-not-notation`

**Workflow Steps:**
1. Checkout code
2. Setup Node.js 18 (with npm cache)
3. Install dependencies (`npm ci`)
4. Lint JS (`npm run lint:js`)
5. Lint SCSS (`npm run lint:scss`)
6. Build AMD (`npx grunt uglify`)
7. Verify AMD build is up to date (fails if build artifacts differ from committed)

**Acceptance Criteria:**
- [x] Push to main and PRs trigger workflow
- [x] Build fails on lint errors (0 JS errors, 0 SCSS errors)
- [x] Build passes on clean code
- [x] AMD build staleness check catches uncommitted builds
- [x] Workflow should complete in < 2 minutes

**Phase 0 complete!**
