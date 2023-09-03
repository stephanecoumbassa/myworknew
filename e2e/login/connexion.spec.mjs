import { test, chromium, expect } from '@playwright/test';

test('test', async () => {

  const browser = await chromium.launch({
    args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-web-security",],
    // args: ["--disable-setuid-sandbox", "--disable-web-security",],
    headless: false,
    executablePath: "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome", // Replace with the actual path
  });
  const page = await browser.newPage();

  await page.goto('http://localhost:9000/#/login');
  await page.getByLabel('Email').click();
  await page.getByLabel('Email').fill('contact@fmmi.ci');
  await page.getByLabel('Email').press('Tab');
  await page.getByLabel('Mot de passe').fill('12345');
  await page.getByRole('button', { name: 'Se Connecter' }).click();
  await page.waitForURL('http://localhost:9000/admin/#/qstock');
  await page.context().storageState({ path: './e2e/authentication.json' });
  await expect(page).toHaveURL('http://localhost:9000/admin/#/qstock');
  await expect(page).toBeTruthy();
});
