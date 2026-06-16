const CACHE_NAME = 'harvestiq-cache-v2';
const urlsToCache = [
    '/',
    '/index.php',
    '/dashboard.php',
    '/style.css',
    '/assets/css/bootstrap.min.css',
    '/assets/css/all.min.css',
    '/assets/js/bootstrap.bundle.min.js',
    '/assets/js/theme.js',
    '/assets/logo-192.png',
    
    // FontAwesome Webfonts (অফলাইন আইকনের জন্য এগুলো ক্যাশ করা বাধ্যতামূলক)
    '/assets/webfonts/fa-solid-900.woff2',
    '/assets/webfonts/fa-regular-400.woff2',
    '/assets/webfonts/fa-brands-400.woff2'
];

// Install Service Worker and Cache Files
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch from Cache first, then Network
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached response if found
                if (response) {
                    return response;
                }
                // Otherwise fetch from network
                return fetch(event.request).catch(() => {
                    // ঐচ্ছিক: ইন্টারনেট না থাকলে এবং ক্যাশ না পেলে কাস্টম অফলাইন পেজ দেখাতে পারেন
                    console.log('You are totally offline and resource is not cached.');
                });
            })
    );
});

// Update Cache and Delete Old Versions
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});