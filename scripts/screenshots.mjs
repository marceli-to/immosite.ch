// Screenshots at the widths from docs/responsive-rules.md §12, plus checks for
// horizontal overflow, JS/console errors and failed requests (e.g. a Mapbox token that
// doesn't allow this domain answers 403). Uses the installed Google Chrome
// (playwright-core, no browser download).
//
//   npm run screenshots -- https://project.test/wohnen [more URLs]
//   → storage/screenshots/<path>-<width>.png, exit code 1 on any problem

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
    const failed = new Map(); // "HTTP 403 api.mapbox.com" → count
    page.on('pageerror', (e) => errors.push(`JS error: ${e.message.split('\n')[0]}`));
    page.on('console', (m) => {
      // Failed requests are reported below, grouped by host.
      if (m.type() === 'error' && !m.text().startsWith('Failed to load resource')) {
        errors.push(`console: ${m.text().split('\n')[0].slice(0, 160)}`);
      }
    });
    page.on('response', (r) => {
      if (r.status() >= 400) {
        const key = `HTTP ${r.status()} ${new URL(r.url()).host}`;
        failed.set(key, (failed.get(key) ?? 0) + 1);
      }
    });
    await page.goto(url, { waitUntil: 'networkidle' });

    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
    const file = `storage/screenshots/${name}-${width}.png`;
    await page.screenshot({ path: file, fullPage: true });

    const problems = [
      overflow > 0 ? `overflows by ${overflow}px` : null,
      ...[...failed].map(([key, count]) => `${key} ×${count}`),
      ...new Set(errors),
    ].filter(Boolean);
    console.log(`${problems.length ? '✗' : '✓'} ${file}${problems.length ? '  ' + problems.join('; ') : ''}`);
    failed ||= problems.length > 0;
    await page.close();
  }
}

await browser.close();
process.exit(failed ? 1 : 0);
