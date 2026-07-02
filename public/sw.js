// Service worker sederhana: network-first, fallback ke cache saat offline.
const CACHE = "ruang-ukhuwah-laravel-v1";
const PRECACHE = ["/manifest.webmanifest", "/icons/icon-192.png", "/icons/icon-512.png"];

self.addEventListener("install", (event) => {
    event.waitUntil(caches.open(CACHE).then((c) => c.addAll(PRECACHE)));
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    const { request } = event;
    if (request.method !== "GET" || !request.url.startsWith(self.location.origin)) return;

    event.respondWith(
        fetch(request)
            .then((response) => {
                const copy = response.clone();
                caches.open(CACHE).then((c) => c.put(request, copy));
                return response;
            })
            .catch(() => caches.match(request))
    );
});
