import { test, expect } from '@playwright/test';

test.use({
  storageState: 'storageState.json'
});

test('test', async ({ page }) => {


  await page.goto('http://localhost:9000/');

  await page.goto('http://localhost:9000/#/');

  await page.getByText('money_offAchats').click();
  await expect(page).toHaveURL('http://localhost:9000/#/achats');

  await page.getByRole('link', { name: 'Achats' }).click();
  await expect(page).toHaveURL('http://localhost:9000/#/achats/commandes');

  await page.getByRole('tab', { name: 'Nouvel achat' }).click();

  await page.getByText('Choisissez un fournisseurFournisseur').click();
  await page.getByText('Choisissez un fournisseurFournisseur').click();
  await page.getByText('Choisissez un fournisseurFournisseur').click();

  await page.getByRole('option', { name: 'GameCoprs' }).click();

  await page.locator('label:has-text("Selectionner un produitarrow_drop_down") i').click();

  await page.getByText('Chemises à Manches Courtes Imprimées').click();

  await page.locator('input[type="number"]').first().click();

  await page.locator('input[type="number"]').first().press('Meta+a');

  await page.locator('input[type="number"]').first().fill('10');

  await page.locator('input[type="number"]').nth(1).click();

  await page.locator('input[type="number"]').nth(1).press('Meta+a');

  await page.locator('input[type="number"]').nth(1).fill('2500');

  await page.locator('input[type="number"]').nth(2).click();

  await page.locator('input[type="number"]').nth(2).press('Meta+a');

  await page.locator('input[type="number"]').nth(2).fill('18');

  page.once('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.accept()
    // dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: 'Valider' }).click();

  await page.getByText('Commande ajoutée avec succès').dblclick();

});
