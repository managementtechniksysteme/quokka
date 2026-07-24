#!/usr/bin/env node
/**
 * Generates the iOS "Add to Home Screen" launch-splash PNGs and the matching
 * <link rel="apple-touch-startup-image"> tags.
 *
 * Why this exists: Safari (unlike Android/Chrome) doesn't build a splash
 * screen from the manifest's icons/background_color -- it needs an
 * exact-pixel-match PNG per physical device screen size, referenced by a
 * <link> with a `media` query matching that device's width/height/pixel
 * ratio/orientation exactly. There's one image (and one <link>) per
 * device x theme combination.
 *
 * Run: node scripts/generate-pwa-splash.mjs
 * (npm run generate:pwa-splash)
 *
 * Requires the `playwright` devDependency's browsers to be installed
 * (`npx playwright install chromium`). Set PWA_SPLASH_CHROMIUM_PATH to
 * point at a system Chromium instead, if you don't want Playwright to
 * manage its own browser download.
 */

import { chromium } from 'playwright';
import { writeFileSync, mkdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const ROOT = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const SPLASH_DIR = path.join(ROOT, 'public/icons/splash');
const LINKS_PARTIAL = path.join(ROOT, 'resources/views/partials/pwa-splash-links.blade.php');

// Every unique modern iPhone screen geometry Safari's iOS PWA launch splash
// needs (several models share identical specs, already de-duplicated).
// Portrait only -- landscape PWA launch isn't a realistic case for this app.
const DEVICES = [
    { width: 375, height: 667, dpr: 2 }, // SE (2nd/3rd gen)
    { width: 375, height: 812, dpr: 3 }, // X/XS/11 Pro/12 mini/13 mini
    { width: 414, height: 896, dpr: 2 }, // XR/11
    { width: 414, height: 896, dpr: 3 }, // XS Max/11 Pro Max
    { width: 390, height: 844, dpr: 3 }, // 12/13/14
    { width: 428, height: 926, dpr: 3 }, // 12/13 Pro Max, 14 Plus
    { width: 393, height: 852, dpr: 3 }, // 14 Pro, 15, 16
    { width: 430, height: 932, dpr: 3 }, // 14 Pro Max, 15/16 Plus
    { width: 402, height: 874, dpr: 3 }, // 16 Pro
    { width: 440, height: 956, dpr: 3 }, // 16 Pro Max
    { width: 420, height: 912, dpr: 3 }, // 15/16 Pro (some models share 393x852 too, kept distinct on purpose -- see note below)
];

// Matches favicon.svg's badge exactly: rounded-square indigo fill, bold
// white "Q", so the launch splash reads as the same brand mark the OS shows
// everywhere else (home screen icon, browser tab). Badge is a fixed logical
// size (not a fraction of screen width) -- same icon size on every device,
// like a native app's own splash screen.
const THEMES = {
    light: { bg: '#eef0f4' },
    dark: { bg: '#0c0f13' },
};
const BADGE_SIZE = 168; // logical px
const BADGE_RADIUS = Math.round(BADGE_SIZE * (7 / 32)); // favicon.svg's rx/width ratio
const BADGE_COLOR = '#6366f1';
const GLYPH_SIZE = Math.round(BADGE_SIZE * (20 / 32)); // favicon.svg's font-size/width ratio

function pageHtml(bg) {
    return `<!doctype html><html><head><meta charset="utf-8"><style>
        * { margin: 0; padding: 0; }
        html, body { width: 100%; height: 100%; background: ${bg}; }
        body { display: flex; align-items: center; justify-content: center; }
        .badge {
            width: ${BADGE_SIZE}px; height: ${BADGE_SIZE}px; border-radius: ${BADGE_RADIUS}px;
            background: ${BADGE_COLOR}; display: flex; align-items: center; justify-content: center;
        }
        .badge span {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-weight: 800; font-size: ${GLYPH_SIZE}px; color: #ffffff;
        }
    </style></head><body><div class="badge"><span>Q</span></div></body></html>`;
}

async function main() {
    mkdirSync(SPLASH_DIR, { recursive: true });

    const browser = await chromium.launch(
        process.env.PWA_SPLASH_CHROMIUM_PATH
            ? { executablePath: process.env.PWA_SPLASH_CHROMIUM_PATH }
            : {}
    );

    const linkTags = [];

    for (const device of DEVICES) {
        for (const [theme, { bg }] of Object.entries(THEMES)) {
            const context = await browser.newContext({
                viewport: { width: device.width, height: device.height },
                deviceScaleFactor: device.dpr,
            });
            const page = await context.newPage();
            await page.setContent(pageHtml(bg));

            const filename = `apple-splash-${device.width}-${device.height}-${device.dpr}x-${theme}.png`;
            await page.screenshot({ path: path.join(SPLASH_DIR, filename) });
            await context.close();

            const media = `(device-width: ${device.width}px) and (device-height: ${device.height}px) and (-webkit-device-pixel-ratio: ${device.dpr}) and (orientation: portrait) and (prefers-color-scheme: ${theme})`;
            linkTags.push(
                `<link rel="apple-touch-startup-image" href="/icons/splash/${filename}" media="${media}">`
            );
            console.log('wrote', filename);
        }
    }

    await browser.close();

    const partial = `{{--
     GENERATED FILE -- do not hand-edit. Regenerate with:
       npm run generate:pwa-splash (scripts/generate-pwa-splash.mjs)
     Included from layouts/app.blade.php's <head>.
--}}
${linkTags.join('\n')}
`;
    writeFileSync(LINKS_PARTIAL, partial);
    console.log('wrote', LINKS_PARTIAL);
}

main();
