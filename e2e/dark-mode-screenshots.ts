/**
 * Take dark mode screenshots for P1-8 verification.
 */
import { chromium } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

const BASE_URL = 'http://localhost:8081';
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'Admin123!';
const DIR = path.join(__dirname, '..', 'test-screenshots', 'dark-mode');

async function login(page: any): Promise<void> {
    await page.goto(`${BASE_URL}/login/index.php`);
    await page.waitForTimeout(2000);
    try {
        await page.fill('#username', ADMIN_USER);
        await page.fill('#password', ADMIN_PASS);
        await page.click('#loginbtn');
        await page.waitForURL((url: URL) => !url.toString().includes('/login/'), { timeout: 15000 });
    } catch (err) {
        console.warn('  Login issue:', (err as Error).message);
    }
}

async function run() {
    fs.mkdirSync(DIR, { recursive: true });

    const browser = await chromium.launch({ headless: true });

    const pages = [
        { name: 'login', path: '/login/index.php', auth: false },
        { name: 'dashboard', path: '/my/', auth: true },
        { name: 'course', path: '/course/view.php?id=2', auth: true },
        { name: 'frontpage', path: '/', auth: false },
        { name: 'admin', path: '/admin/search.php', auth: true },
    ];

    for (const [vpName, vp] of [
        ['desktop', { width: 1440, height: 900 }],
        ['tablet', { width: 768, height: 1024 }],
        ['mobile', { width: 375, height: 667 }],
    ] as const) {
        console.log(`\n=== ${vpName.toUpperCase()} (Dark Mode) ===\n`);

        // Unauthenticated pages (login, frontpage)
        const unauthCtx = await browser.newContext({
            viewport: vp as any,
            ignoreHTTPSErrors: true,
        });
        // Set dark mode via localStorage before any page loads
        await unauthCtx.addInitScript(() => {
            localStorage.setItem('theme_zenith_darkmode', 'dark');
        });
        const unauthPage = await unauthCtx.newPage();

        for (const pageDef of pages.filter(p => !p.auth)) {
            await unauthPage.goto(`${BASE_URL}${pageDef.path}`, { waitUntil: 'networkidle' });
            await unauthPage.waitForTimeout(3000);
            const filePath = path.join(DIR, `${pageDef.name}-${vpName}.png`);
            await unauthPage.screenshot({ path: filePath, fullPage: false });
            console.log(`  ✓ ${pageDef.name}-${vpName}.png`);
        }
        await unauthCtx.close();

        // Authenticated pages
        const authCtx = await browser.newContext({
            viewport: vp as any,
            ignoreHTTPSErrors: true,
        });
        await authCtx.addInitScript(() => {
            localStorage.setItem('theme_zenith_darkmode', 'dark');
        });
        const authPage = await authCtx.newPage();
        await login(authPage);

        for (const pageDef of pages.filter(p => p.auth)) {
            await authPage.goto(`${BASE_URL}${pageDef.path}`, { waitUntil: 'networkidle' });
            await authPage.waitForTimeout(3000);
            const filePath = path.join(DIR, `${pageDef.name}-${vpName}.png`);
            await authPage.screenshot({ path: filePath, fullPage: false });
            console.log(`  ✓ ${pageDef.name}-${vpName}.png`);
        }
        await authCtx.close();
    }

    await browser.close();
    console.log(`\n✓ Dark mode screenshots saved to ${DIR}/\n`);
}

run().catch(console.error);
