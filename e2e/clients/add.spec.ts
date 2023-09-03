import { test, chromium, expect } from '@playwright/test';
import { faker } from '@faker-js/faker';
import { baseUrl } from '../constante';


test('test', async () => {
  const browser = await chromium.launch({
    args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-web-security",
      "--load-extension=/Users/$USER/Library/Application\\ Support/Google/Chrome/Profile 1/Extensions/ghbmnnjooekpmoecnnnilnnbdlolhkhi"],
    headless: false,

    executablePath: "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome", // Replace with the actual path
  });
  const context = await browser.newContext({
    storageState: '../authentication.json',
  });
  const page = await context.newPage();


  await page.goto(baseUrl+'/#/clients');
  await page.locator('#add').click();

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
  await page.getByRole('button', { name: 'Valider' }).click();

  await page.waitForTimeout(55000);
  await expect(page).toBeTruthy();
  // await browser.close();
});
