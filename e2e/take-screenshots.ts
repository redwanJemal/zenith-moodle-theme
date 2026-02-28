/**
 * Zenith Moodle Theme — Screenshot Generator
 *
 * Takes screenshots of all major Moodle pages across desktop, tablet, and mobile viewports.
 * Based on TadHub's screenshot pattern.
 *
 * Usage:
 *   npx tsx take-screenshots.ts
 *   npx tsx take-screenshots.ts --mobile    # Mobile only
 */

import { chromium, type Page, type Browser } from '@playwright/test';
import * as dotenv from 'dotenv';
import * as fs from 'fs';
import * as path from 'path';

dotenv.config();

const BASE_URL = process.env.MOODLE_URL || 'http://localhost:8081';
const ADMIN_USER = process.env.MOODLE_ADMIN_USER || 'admin';
const ADMIN_PASS = process.env.MOODLE_ADMIN_PASSWORD || 'Admin123!';
const SCREENSHOTS_DIR = path.join(__dirname, '..', 'test-screenshots');

const VIEWPORTS = {
  desktop: { width: 1440, height: 900 },
  tablet: { width: 768, height: 1024 },
  mobile: { width: 375, height: 667 },
};

// Pages to screenshot (path relative to Moodle root)
const PAGES = [
  { name: '01-login', path: '/login/index.php', requiresAuth: false },
  { name: '02-dashboard', path: '/my/', requiresAuth: true },
  { name: '03-frontpage', path: '/', requiresAuth: false },
  { name: '04-course-list', path: '/course/', requiresAuth: false },
  { name: '05-course-view', path: '/course/view.php?id=2', requiresAuth: true },
  { name: '06-profile', path: '/user/profile.php', requiresAuth: true },
  { name: '07-messages', path: '/message/index.php', requiresAuth: true },
  { name: '08-calendar', path: '/calendar/view.php?view=month', requiresAuth: true },
  { name: '09-admin-dashboard', path: '/admin/index.php', requiresAuth: true },
  { name: '10-site-admin', path: '/admin/search.php', requiresAuth: true },
  { name: '11-course-categories', path: '/course/index.php', requiresAuth: false },
  { name: '12-notifications', path: '/message/output/popup/notifications.php', requiresAuth: true },
];

async function login(page: Page): Promise<void> {
  console.log('  Logging in as admin...');
  await page.goto(`${BASE_URL}/login/index.php`);
  await page.waitForTimeout(2000);

  try {
    await page.fill('#username', ADMIN_USER);
    await page.fill('#password', ADMIN_PASS);
    await page.click('#loginbtn');
    await page.waitForURL((url) => !url.toString().includes('/login/'), {
      timeout: 15_000,
    });
    console.log('  Login successful');
  } catch (err) {
    console.warn('  Login failed, continuing...', (err as Error).message);
  }
}

async function takeScreenshot(
  page: Page,
  viewport: string,
  pageDef: (typeof PAGES)[number],
): Promise<void> {
  const dir = path.join(SCREENSHOTS_DIR, viewport);
  fs.mkdirSync(dir, { recursive: true });

  const filePath = path.join(dir, `${pageDef.name}.png`);

  try {
    await page.goto(`${BASE_URL}${pageDef.path}`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(3000); // Let animations settle

    await page.screenshot({ path: filePath, fullPage: false });
    console.log(`  ✓ ${viewport}/${pageDef.name}.png`);
  } catch (err) {
    console.warn(`  ✗ ${viewport}/${pageDef.name} — ${(err as Error).message}`);
    // Take whatever is on screen
    try {
      await page.screenshot({ path: filePath, fullPage: false });
    } catch {
      // ignore
    }
  }
}

async function run(): Promise<void> {
  const mobileOnly = process.argv.includes('--mobile');
  const viewportsToRun = mobileOnly
    ? { mobile: VIEWPORTS.mobile }
    : VIEWPORTS;

  console.log(`\nZenith Screenshot Generator`);
  console.log(`Base URL: ${BASE_URL}`);
  console.log(`Viewports: ${Object.keys(viewportsToRun).join(', ')}\n`);

  const browser: Browser = await chromium.launch({ headless: true });

  for (const [viewportName, viewport] of Object.entries(viewportsToRun)) {
    console.log(`\n=== ${viewportName.toUpperCase()} (${viewport.width}x${viewport.height}) ===\n`);

    // Take unauthenticated screenshots first (login page).
    const unauthPages = PAGES.filter((p) => !p.requiresAuth);
    const authPages = PAGES.filter((p) => p.requiresAuth);

    // Unauthenticated context for login page etc.
    const unauthContext = await browser.newContext({
      viewport,
      ignoreHTTPSErrors: true,
    });
    const unauthPage = await unauthContext.newPage();
    unauthPage.on('pageerror', (err) => {
      console.warn(`  [JS Error] ${err.message}`);
    });

    for (const pageDef of unauthPages) {
      await takeScreenshot(unauthPage, viewportName, pageDef);
    }
    await unauthContext.close();

    // Authenticated context for dashboard, course, etc.
    const context = await browser.newContext({
      viewport,
      ignoreHTTPSErrors: true,
    });
    const page = await context.newPage();
    page.on('pageerror', (err) => {
      console.warn(`  [JS Error] ${err.message}`);
    });

    await login(page);

    for (const pageDef of authPages) {
      await takeScreenshot(page, viewportName, pageDef);
    }

    await context.close();
  }

  await browser.close();

  console.log(`\n✓ Screenshots saved to ${SCREENSHOTS_DIR}/\n`);
}

run().catch(console.error);
