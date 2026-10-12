// Screenshots at the widths from docs/responsive-rules.md §12, plus a horizontal
// overflow check. Uses the installed Google Chrome (playwright-core, no browser download).
//
//   npm run screenshots -- https://project.test/wohnen [more URLs]
//   → storage/screenshots/<path>-<width>.png, exit code 1 if a page overflows sideways

import { chromium } from 'playwright-core';
import { mkdirSync } from 'node:fs';

const urls = process.argv.slice(2);
const widths = [390, 768, 1024, 1280, 1440];

if (urls.length === 0) {
  console.error('Usage: npm run screenshots -- <url> [<url> …]');
  process.exit(1);
}

mkdirSync('storage/screenshots', { recursive: true });
const browser = await chromium.launch({ channel: 'chrome' });
let failed = false;

for (const url of urls) {
  const name = new URL(url).pathname.replace(/^\/|\/$/g, '').replace(/\//g, '-') || 'home';

  for (const width of widths) {
    const page = await browser.newPage({ viewport: { width, height: 900 }, ignoreHTTPSErrors: true });
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    await page.goto(url, { waitUntil: 'networkidle' });

    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    const file = `storage/screenshots/${name}-${width}.png`;
    await page.screenshot({ path: file, fullPage: true });

    const problems = [overflow > 0 ? `overflows by ${overflow}px` : null, ...errors.map((e) => `JS error: ${e}`)].filter(Boolean);
    console.log(`${problems.length ? '✗' : '✓'} ${file}${problems.length ? '  ' + problems.join('; ') : ''}`);
    failed ||= problems.length > 0;
    await page.close();
  }
}

await browser.close();
process.exit(failed ? 1 : 0);
