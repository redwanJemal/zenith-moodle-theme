import { defineConfig, devices } from '@playwright/test';
import * as dotenv from 'dotenv';

dotenv.config();

export default defineConfig({
  testDir: './tests',
  timeout: 60_000,
  expect: { timeout: 10_000 },
  fullyParallel: false,
  workers: 1,
  reporter: [['html'], ['list']],
  use: {
    baseURL: process.env.MOODLE_URL || 'http://localhost:8081',
    screenshot: 'only-on-failure',
    video: 'on-first-retry',
    trace: 'on-first-retry',
  },
  projects: [
    // Auth setup
    {
      name: 'setup',
      testMatch: /auth\.setup\.ts/,
    },
    // Desktop tests (1440x900)
    {
      name: 'desktop',
      use: { ...devices['Desktop Chrome'] },
      dependencies: ['setup'],
    },
    // Tablet tests (768x1024)
    {
      name: 'tablet',
      use: { ...devices['iPad (gen 7)'] },
      dependencies: ['setup'],
    },
    // Mobile tests (375x667)
    {
      name: 'mobile',
      use: { ...devices['iPhone 13'] },
      dependencies: ['setup'],
    },
  ],
});
