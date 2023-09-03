const { chromium } = require('playwright');
// import { test, expect } from '@playwright/test';
// import { faker } from '@faker-js/faker';

const { faker } = require('@faker-js/faker');

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext();
  const page = await context.newPage();

  await page.goto('http://localhost:9000/admin/#/produits');
  await page.click('#add');

  // Remplir le formulaire avec des données aléatoires
  await page.fill('#name', faker.commerce.productName());
  await page.select('#type', faker.random.arrayElement(['matiere', 'produit', 'outil']));
  await page.select('#domainid', faker.random.arrayElement(['id1', 'id2', 'id3']));
  await page.select('#parent_categorie_id', faker.random.arrayElement(['id1', 'id2', 'id3']));
  await page.fill('#price', faker.commerce.price());
  await page.fill('#alert_threshold', faker.random.number({ min: 1, max: 10 }));
  await page.fill('#reference', faker.random.alphaNumeric(10));
  await page.fill('#youtube', faker.internet.url());
  await page.fill('#largeur', faker.random.number({ min: 1, max: 10 }));
  await page.fill('#longueur', faker.random.number({ min: 1, max: 10 }));
  await page.fill('#hauteur', faker.random.number({ min: 1, max: 10 }));
  await page.fill('#poids', faker.random.number({ min: 1, max: 10 }));
  await page.fill('#diametre', faker.random.number({ min: 1, max: 10 }));
  await page.fill('#description', faker.lorem.paragraph());

  // Soumettre le formulaire
  await page.click('[type="submit"]');

  // Vérifier si le produit a été ajouté avec succès
  const successMessage = await page.$eval('.success-message', el => el.textContent);
  console.log(`Success message: ${successMessage}`);

  await browser.close();
})();
