// import { test, expect } from '@playwright/test';
const { test, expect } = require('@playwright/test');
test.use({
  storageState: 'storageState.json'
});
test('test', async ({ page }) => {
  await page.goto('http://localhost:9000/');
  await page.goto('http://localhost:9000/#/');
  await page.getByText('money_offDevis').click();
  await expect(page).toHaveURL('http://localhost:9000/#/devis');
  await page.getByRole('button', { name: 'Ajouter' }).click();
  await page.locator('#facture button:has-text("add")').click();
  await page.getByText('arrow_drop_down').click();
  await page.getByText('Chemises à Manches Courtes Imprimées Chemises').click();
  await page.getByLabel('Quantité').click();
  await page.getByLabel('Quantité').press('Meta+a');
  await page.getByLabel('Quantité').fill('10');
  page.once('dialog', dialog => {
    // console.log(`Dialog message: ${dialog.message()}`);
    dialog.accept().catch(() => {});
    // dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: 'Valider' }).click();
  await page.getByText('ajouté avec succès').click();
  // await page.locator('.q-card__actions > button:nth-child(2)').first().click();
});
