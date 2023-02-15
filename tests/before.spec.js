const { test, expect, chromium } = require("@playwright/test");

test.describe("navigation", () => {
  test.beforeEach(async ({ page }) => {
    // const browser = await chromium.launch();
    // const pageChrome = await browser.newPage();
    await page.goto('http://localhost:9000/#/login');
    await page.getByLabel('Email').click();
    await page.getByLabel('Email').fill('stefyu123@yahoo.fr');
    await page.getByLabel('Email').press('Tab');
    await page.getByLabel('Mot de passe').fill('12345');
    await page.getByRole('button', { name: 'Se Connecter' }).click();
    await expect(page).toHaveURL('http://localhost:9000/#/qstock');
    await page.context().storageState({ path: 'storageState.json' });
    await expect(page).toHaveURL("http://localhost:9000/#/qstock");
  });

  test("main navigation", async ({ page }) => {
    // Assertions use the expect API.
    // await page.goto('http://localhost:9000/#/qstock');
    // await expect(page).toHaveURL("http://localhost:9000/#/qstock");
  });
});
