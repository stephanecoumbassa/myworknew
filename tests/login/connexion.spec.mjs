import { test, expect } from '@playwright/test';

test('test', async ({ page }) => {
  await page.goto('http://localhost:9000/#/login');
  await page.getByLabel('Email').click();
  await page.getByLabel('Email').fill('stefyu123@yahoo.fr');
  await page.getByLabel('Email').press('Tab');
  await page.getByLabel('Mot de passe').fill('12345');
  await page.getByRole('button', { name: 'Se Connecter' }).click();
  await expect(page).toHaveURL('http://localhost:9000/#/qstock');
});
