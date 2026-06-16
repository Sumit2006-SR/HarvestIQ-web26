const CACHE_NAME = 'harvestiq-pwa-v1';
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
    './assets/logo-192.png'
];

// Install Event - Caching core files
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(urlsToCache);
        })
    );
    self.skipWaiting();
});

// Activate Event - Clearing old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch Event - Serve from Cache first, then Network
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request).catch(() => {
                console.log("You are offline!");
            });
        })
    );
});