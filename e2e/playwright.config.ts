// playwright.config.ts
import { type PlaywrightTestConfig, devices } from '@playwright/test';

const config: PlaywrightTestConfig = {
  projects: [
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        launchOptions: {
          // Put your chromium-specific args here
          args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-web-security"],
          headless: false,
        },
      },
    },
  ],
};
export default config;
