/**
 * Quokka service worker
 *
 * No CACHE_NAME, no version string, nothing to bump on deploy — every
 * previous version required a "bump version" commit just to touch this
 * file's bytes so the browser would notice an update (see git log). The
 * offline page is inlined below instead, so changing it changes this file's
 * bytes, which is what makes the browser install an update.
 */

const OFFLINE_HTML = `<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Offline</title>
<style>
  :root { color-scheme: light dark; }
  * { box-sizing: border-box; }
  body {
    margin: 0;
    min-height: 100dvh;
    display: grid;
    place-items: center;
    padding: 2rem;
    font: 16px/1.6 -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    background: #eef0f4;
    color: #1b1e26;
  }
  .card { max-width: 22rem; text-align: center; }
  .badge {
    width: 3.5rem; height: 3.5rem; border-radius: 1rem;
    background: #6366f1; color: #fff;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
  }
  .badge svg { width: 1.75rem; height: 1.75rem; fill: none; stroke: currentColor; stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round; }
  h1 { font-size: 1.25rem; font-weight: 800; margin: 0 0 .5rem; }
  p { margin: 0 0 1.75rem; color: #69707d; }
  button {
    font: inherit;
    font-weight: 600;
    padding: .6rem 1.4rem;
    border: 0;
    border-radius: .625rem;
    background: #6366f1;
    color: #fff;
    cursor: pointer;
  }
  button:hover { background: #4f46e5; }
  @media (prefers-color-scheme: dark) {
    body { background: #0c0f13; color: #e9ebf0; }
    p { color: #9aa2af; }
  }
</style>
</head>
<body>
  <div class="card">
    <div class="badge">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M1 1l22 22"/>
        <path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/>
        <path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/>
        <path d="M10.71 5.05A16 16 0 0 1 22.58 9"/>
        <path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"/>
        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
        <path d="M12 20h.01"/>
      </svg>
    </div>
    <h1>Keine Verbindung</h1>
    <p>Das Gerät kann momentan keine Verbindung zum Server herstellen. Bitte überprüfe deine Internetverbindung.</p>
    <button onclick="location.reload()">Erneut versuchen</button>
  </div>
</body>
</html>`;

self.addEventListener("install", function () {
  self.skipWaiting();
});

self.addEventListener("activate", function (event) {
  event.waitUntil(
    (async function () {
      // One-time cleanup of the old versioned caches this service worker
      // used to maintain. Safe to delete this block after a release cycle
      // or two, once every client has picked up this version.
      const names = await caches.keys();
      await Promise.all(
        names
          .filter((name) => name.startsWith("quokka-cache-"))
          .map((name) => caches.delete(name))
      );

      await self.clients.claim();
    })()
  );
});

self.addEventListener("fetch", function (event) {
  // Only handle top-level page loads. Assets, XHR/fetch calls and POSTs go
  // straight to the network with completely normal browser semantics --
  // no interception, no surprise HTML responses to JSON endpoints (the old
  // cache-first strategy intercepted every request indiscriminately).
  if (event.request.mode !== "navigate") return;

  event.respondWith(
    fetch(event.request).catch(function () {
      return new Response(OFFLINE_HTML, {
        status: 503,
        statusText: "Offline",
        headers: {
          "Content-Type": "text/html; charset=utf-8",
          "Cache-Control": "no-store",
        },
      });
    })
  );
});

self.addEventListener("push", function (event) {
  if (!self.Notification || self.Notification.permission !== "granted") return;
  if (!event.data) return;

  let msg;
  try {
    msg = event.data.json();
  } catch (e) {
    return;
  }
  if (!msg || !msg.title) return;

  event.waitUntil(
    self.registration.showNotification(msg.title, {
      body: msg.body,
      icon: msg.icon,
      tag: msg.tag,
      data: msg.data,
      badge: msg.badge,
      dir: msg.dir,
      image: msg.image,
      // renotify requires a tag to be meaningful (it re-alerts the user to
      // an existing notification with that tag) -- passing it without one
      // is invalid per the Notifications API and the old code did exactly
      // that unconditionally.
      renotify: msg.tag ? msg.renotify : undefined,
      requireInteraction: msg.requireInteraction,
      actions: msg.actions,
      vibrate: msg.vibrate,
    })
  );
});

self.addEventListener("notificationclick", function (event) {
  event.notification.close();

  const url = event.notification.data && event.notification.data.url;
  if (!url) return;

  event.waitUntil(
    (async function () {
      const target = new URL(url, self.location.origin).href;
      const windows = await self.clients.matchAll({
        type: "window",
        includeUncontrolled: true,
      });

      // Focus an already-open tab on that URL rather than stacking windows.
      for (const client of windows) {
        if (client.url === target && "focus" in client) {
          return client.focus();
        }
      }

      if (self.clients.openWindow) {
        return self.clients.openWindow(url);
      }
    })()
  );
});
