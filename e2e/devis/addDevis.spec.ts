// import { test, expect } from '@playwright/test';
const { test, expect } = require('@playwright/test');
import { faker } from '@faker-js/faker';
import {baseUrl, mypage} from '../constante';

test('test', async () => {

  const page = await mypage();

  await page.goto(baseUrl+'/#/');
  await page.locator('#menuDevis').click();
  await expect(page).toHaveURL(baseUrl+'/#/devis');
  await page.getByRole('button', { name: 'Ajouter' }).click();

  await page.locator('div').filter({ hasText: /^Projets$/ }).first().click();
  await page.getByRole('option', { name: 'Projet Couronne' }).locator('div').nth(1).click();
  await page.getByText('selectionner un clientClients').click();
  await page.getByRole('option', { name: 'IDH' }).click();
  await page.waitForTimeout(5000);
  await page.getByRole('textbox').nth(1).click();
  await page.getByRole('textbox').nth(1).fill('PROF-134');
  await page.getByRole('textbox').nth(2).click();
  await page.getByRole('textbox').nth(2).fill('DA-234');

  await page.locator('button').filter({ hasText: /^add$/ }).click();
  await page.locator('div').filter({ hasText: /^Product1$/ }).first().click();
  await page.getByRole('option', { name: 'cours' }).click();
  await page.getByLabel('Prix Unitaire').click();
  await page.getByLabel('Prix Unitaire').press('Meta+a');
  await page.getByLabel('Prix Unitaire').fill('5000');
  await page.getByLabel('TVA').click();
  await page.getByLabel('TVA').press('Meta+a');
  await page.getByLabel('TVA').fill('18');
  // await page.getByRole('button', { name: 'Valider' }).click();
  page.on('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.accept()
    // dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: 'Valider' }).click();
  await page.waitForTimeout(50000);
});
