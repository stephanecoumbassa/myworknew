import { test, chromium, expect } from '@playwright/test';

test('test', async () => {

  const browser = await chromium.launch({
    args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-web-security",],
    // args: ["--disable-setuid-sandbox", "--disable-web-security",],
    headless: false,
    executablePath: "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome", // Replace with the actual path
  });
  const page = await browser.newPage();
  await page.goto('https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&emr=1&followup=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&ifkv=AXo7B7Wz8FvdfOCZF9oEl07uDCAFiwwhPMMpfZxYjUjejJMb3kuxL9do0tWt8g472rcdUcgpB6ef&osid=1&passive=1209600&service=mail&flowName=GlifWebSignIn&flowEntry=ServiceLogin&dsh=S470792595%3A1693738340181627&theme=glif');
  await page.getByLabel('Email or phone').click();
  await page.getByLabel('Email or phone').fill('stephane.coumbassa@gmail.com');
  await page.getByRole('button', { name: 'Next' }).click();
  await expect(page).toBeTruthy();
});
