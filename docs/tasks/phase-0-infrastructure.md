# Phase 0: Infrastructure & Scaffolding

> **Timeline:** Week 1 · **Total Effort:** 17 hours · **Tasks:** 6

---

## P0-1: Docker Development Environment
- **Status:** `[ ]`
- **Dependencies:** None
- **Effort:** 2 hours
- **Blocks:** P0-2, P0-3, P0-5

**Task:** Set up local Moodle 4.5 LTS with MariaDB via Docker.

**Acceptance Criteria:**
- [ ] `docker compose up -d` starts Moodle on port 8081
- [ ] MariaDB running on port 3306
- [ ] Admin login works at http://localhost:8081
- [ ] Theme source bind-mounted at `/bitnami/moodle/theme/zenith`
- [ ] Health checks passing for both containers
- [ ] `docker compose down` cleanly stops everything

**Files:** `docker/docker-compose.yml`, `docker/.env.example`

---

## P0-2: Production Deployment (Coolify)
- **Status:** `[ ]`
- **Dependencies:** `P0-1`
- **Effort:** 3 hours
- **Blocks:** P6-2

**Task:** Deploy to lms.endlessmaker.com via Coolify with Traefik proxy.

**Acceptance Criteria:**
- [ ] `docker-compose.coolify.yml` deploys successfully
- [ ] Containers on `coolify` external network
- [ ] `traefik.enable=false` labels set (routing via dynamic file)
- [ ] Traefik config added: `lms.endlessmaker.com` → `zenith-moodle:8081`
- [ ] HTTPS working (Let's Encrypt via Coolify)
- [ ] Health checks passing
- [ ] No port conflicts with TadHub (8080, 8090, 5432 already used)

**Files:** `docker/docker-compose.coolify.yml`, `docs/DEPLOYMENT.md`

**Reference:** TadHub Coolify pattern at `/home/redman/TadHub/docker/docker-compose.coolify.yml`

---

## P0-3: Theme Scaffold (Boost Child)
- **Status:** `[ ]`
- **Dependencies:** `P0-1`
- **Effort:** 4 hours
- **Blocks:** P0-4, P1-1

**Task:** Create minimal Boost child theme that installs and activates without errors.

**Files to Create:**
- [ ] `theme/zenith/version.php` — Version 2026022800, requires Moodle 4.5+
- [ ] `theme/zenith/config.php` — Layouts, block regions, SCSS callbacks, renderer factory
- [ ] `theme/zenith/lib.php` — pluginfile handler, SCSS functions (pre, main, post, precompiled)
- [ ] `theme/zenith/settings.php` — Minimal settings tab (brand name, logo)
- [ ] `theme/zenith/lang/en/theme_zenith.php` — Base language strings
- [ ] `theme/zenith/scss/preset/default.scss` — Main SCSS entry (imports Boost + custom)
- [ ] `theme/zenith/pix/screenshot.png` — Theme selector thumbnail (500×400)

**Acceptance Criteria:**
- [ ] Theme appears in Site Admin → Appearance → Theme selector
- [ ] Activating theme doesn't break any page
- [ ] All Moodle core pages render correctly (dashboard, course, login, admin)
- [ ] No PHP errors, warnings, or notices
- [ ] Theme info shows version 1.0.0

**Reference:** RemUI scaffold at `/home/redman/Edwiser-RemUI/theme_remui/remui/config.php`

---

## P0-4: Build Tooling
- **Status:** `[ ]`
- **Dependencies:** `P0-3`
- **Effort:** 3 hours
- **Blocks:** P0-6

**Task:** Set up Grunt for AMD compilation, SCSS compilation, linting.

**Files to Create:**
- [ ] `theme/zenith/package.json` — Dependencies: grunt, grunt-contrib-uglify, eslint, stylelint
- [ ] `theme/zenith/Gruntfile.js` — Tasks: amd, css, watch, lint
- [ ] `theme/zenith/.stylelintrc` — SCSS lint rules (Moodle coding standard)
- [ ] `theme/zenith/.eslintrc` — JS lint rules (Moodle coding standard)

**Acceptance Criteria:**
- [ ] `npm install` installs all dependencies
- [ ] `npx grunt amd` compiles `amd/src/*.js` → `amd/build/*.min.js`
- [ ] `npx grunt css` triggers Moodle SCSS compilation
- [ ] `npx grunt watch` watches for file changes
- [ ] `npm run lint` checks SCSS + JS with zero errors on clean scaffold

---

## P0-5: E2E Screenshot Setup
- **Status:** `[ ]`
- **Dependencies:** `P0-1`
- **Effort:** 2 hours
- **Blocks:** P0-6

**Task:** Set up Playwright to capture screenshots of all major pages across 3 viewports.

**Files:** `e2e/` (already scaffolded — verify and test)

**Acceptance Criteria:**
- [ ] `cd e2e && npm install && npx playwright install chromium` sets up
- [ ] `npm run screenshots` runs without errors
- [ ] Captures 12 pages × 3 viewports = 36 screenshots
- [ ] Screenshots saved to `test-screenshots/{desktop,tablet,mobile}/`
- [ ] Login automation works (fills username/password, clicks login)
- [ ] Mobile viewport (375px) properly captures responsive layout
- [ ] Script handles errors gracefully (continues on page load failure)

**Reference:** TadHub screenshot pattern at `/home/redman/TadHub/e2e/take-screenshots.ts`

---

## P0-6: CI/CD Pipeline
- **Status:** `[ ]`
- **Dependencies:** `P0-4`, `P0-5`
- **Effort:** 3 hours
- **Blocks:** None

**Task:** GitHub Actions workflow for automated lint, build, and test on every PR.

**Files to Create:**
- [ ] `.github/workflows/ci.yml`

**Workflow Steps:**
1. Checkout code
2. Setup Node.js 18
3. Install dependencies (`npm ci`)
4. Lint SCSS + JS (`npm run lint`)
5. Compile AMD (`npx grunt amd`)
6. (Optional) Run Playwright screenshots and upload as artifacts

**Acceptance Criteria:**
- [ ] PR triggers workflow automatically
- [ ] Build fails on lint errors
- [ ] Build passes on clean code
- [ ] Screenshots uploaded as downloadable artifacts
- [ ] Workflow completes in < 5 minutes
