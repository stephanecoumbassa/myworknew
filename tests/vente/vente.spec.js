import { test, expect } from '@playwright/test';
test.use({
  storageState: 'storageState.json'
});
test('test', async ({ page }) => {
  await page.goto('http://localhost:9000/');
  await page.goto('http://localhost:9000/#/');
  await page.getByText('attach_moneyVentes').click();
  await expect(page).toHaveURL('http://localhost:9000/#/ventes');
  await page.getByRole('link', { name: 'Ventes' }).click();
  await expect(page).toHaveURL('http://localhost:9000/#/ventes/new');
  await page.getByText('Nouvelle vente').click();
  await page.locator('label:has-text("Coumbassa StéphaneClientsarrow_drop_down") i').click();
  await page.getByRole('option', { name: 'Coumbassa Stéphane' }).getByText('Coumbassa Stéphane').click();
  await page.locator('label:has-text("Selectionner un produitarrow_drop_down") i').click();
  await page.getByText('Chemises à Manches Courtes Imprimées').click();
  await page.getByLabel('tva').click();
  await page.getByLabel('qty').click();
  await page.locator('label:has-text("qty")').click();
  await page.locator('label:has-text("Chemises à Manches Courtes Impriméesarrow_drop_down")').click();
  await page.getByLabel('qty').click();
  await page.getByLabel('qty').press('Meta+a');
  await page.getByLabel('qty').fill('5');
  page.on('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.accept()
    // dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: 'Valider' }).click();
  // Enregistrement ok!!
  // await page.getByText('Commande ajoutée avec succès').dblclick();
  // await expect(page.getByText('Enregistrement ok!!')).toContainText('OK');
  await page.getByText('Enregistrement ok!!').dblclick();

});
