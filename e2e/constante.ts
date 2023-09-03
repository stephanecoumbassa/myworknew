import { test, chromium, expect, Page } from '@playwright/test';
// export const baseUrl = 'http://localhost:9000/admin';
export const baseUrl = 'https://fmmi.ci/admin';
export const mypage = async (): Promise<Page> => {
  const browser = await chromium.launch({
    args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-web-security",
      "--load-extension=/Users/$USER/Library/Application\\ Support/Google/Chrome/Profile 1/Extensions/ghbmnnjooekpmoecnnnilnnbdlolhkhi"],
    headless: false,
  });
  const context = await browser.newContext({
    storageState: '../authentication.json',
  });
  return await context.newPage();
}

export const mypageHide = async (): Promise<Page> => {
  const browser = await chromium.launch({
    args: ["--no-sandbox", "--disable-setuid-sandbox", "--disable-web-security",
      "--load-extension=/Users/$USER/Library/Application\\ Support/Google/Chrome/Profile 1/Extensions/ghbmnnjooekpmoecnnnilnnbdlolhkhi"],
    headless: false,
  });
  return await browser.newPage();
}

// export const context = async() => {
//   const context = await browser.newContext({
//     storageState: '../authentication.json',
//   });
// }
