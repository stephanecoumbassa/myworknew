import { test, chromium, expect } from '@playwright/test';
import { faker } from '@faker-js/faker';
import {baseUrl, mypage} from '../constante';


test('test', async () => {

  const page = await mypage();

  await page.goto(baseUrl+'/#/clients');
  await page.locator('#actions8').click();
  await page.locator('#update8').click();

  // Fill in data using Faker
  await page.getByText('Type de clientarrow_drop_down').click();
  await page.getByRole('option', { name: 'personne' }).click();

  await page.getByLabel('Nom *', { exact: true }).click();
  await page.getByLabel('Nom *', { exact: true }).fill(faker.name.lastName());

  await page.getByLabel('Prenom *').click();
  await page.getByLabel('Prenom *').fill(faker.name.firstName());

  await page.getByLabel('indicatif *').click();
  await page.getByLabel('indicatif *').fill(faker.random.numeric(3));

  await page.getByLabel('telephone *').click();
  await page.getByLabel('telephone *').fill(faker.phone.phoneNumberFormat());

  await page.getByLabel('Email *').click();
  await page.getByLabel('Email *').fill(faker.internet.email());

  await page.getByLabel('Ville').click();
  await page.getByLabel('Ville').fill(faker.address.city());

  await page.getByLabel('Adresse').click();
  await page.getByLabel('Adresse').fill(faker.address.streetAddress());

  await page.getByLabel('Exonere', { exact: true }).click();
  await page.getByRole('button', { name: 'modifier' }).click();

  await page.waitForTimeout(5000);
  await expect(page).toBeTruthy();
  // await browser.close();
});
