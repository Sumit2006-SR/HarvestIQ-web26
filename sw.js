const CACHE_NAME = 'harvestiq-offline-v1';
const urlsToCache = [
    './',
    './index.php',
    './dashboard.php',
    './market_prices.php',
    './weather.php',
    './style.css',
    './assets/css/bootstrap.min.css',
    './assets/css/all.min.css',
    './assets/js/theme.js',
    './assets/logo-192.png',
    './assets/logo-512.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) return caches.delete(cacheName);
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
     if (!(event.request.url.indexOf('http') === 0)) return;

    event.respondWith(
        caches.match(event.request).then(response => {
             if (response) return response;

             return fetch(event.request).catch(() => {
                 console.log("Offline mode detected for:", event.request.url);
                return new Response('You are offline. Please check your connection.', {
                    status: 200,
                    headers: { 'Content-Type': 'text/plain' }
                });
            });
        })
    );
});