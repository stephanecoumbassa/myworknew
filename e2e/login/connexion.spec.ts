import { test, expect } from '@playwright/test';
import {baseUrl, mypageHide} from '../constante';


test('test', async () => {
  const page = await mypageHide();
  await page.goto(baseUrl +'/#/login');
  await page.getByLabel('Email').click();
  await page.getByLabel('Email').fill('contact@fmmi.ci');
  await page.getByLabel('Email').press('Tab');
  await page.getByLabel('Mot de passe').fill('12345');
  await page.getByRole('button', { name: 'Se Connecter' }).click();
  await page.waitForURL(baseUrl+'/#/qstock');
  await page.context().storageState({ path: './e2e/authentication.json' });
  await page.context().storageState({ path: '../authentication.json' });
  await expect(page).toHaveURL(baseUrl+'/#/qstock');
  await expect(page).toBeTruthy();
});
