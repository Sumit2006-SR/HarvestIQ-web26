const CACHE_NAME = 'harvestiq-premium-v1'; // নতুন নাম, যাতে পুরনো ক্যাশ ডিলিট হয়ে যায়

// ১. Install Event - সাথে সাথে নতুন Service Worker চালু করা
self.addEventListener('install', event => {
    self.skipWaiting();
});

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

// ৩. Fetch Event - "Network First, Dynamic Cache Fallback" (The Hackathon Trick)
self.addEventListener('fetch', event => {
    // শুধুমাত্র GET রিকোয়েস্ট ক্যাশ করবে
    if (event.request.method !== 'GET') return;
    // ক্রোম এক্সটেনশনের রিকোয়েস্ট ইগনোর করবে
    if (!event.request.url.startsWith('http')) return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // অনলাইনে থাকলে রিয়েল ডেটা আনবে এবং সাথে সাথে সেটা ক্যাশ মেমোরিতে সেভ করে রাখবে
                const clonedResponse = response.clone();
                caches.open(CACHE_NAME).then(cache => {
                    cache.put(event.request, clonedResponse);
                });
                return response;
            })
            .catch(() => {
                // অফলাইনে থাকলে ক্যাশ থেকে ডেটা দেখাবে (ignoreSearch: true থাকায় ?v=5.0 এর মতো ভার্সন থাকলেও এরর খাবে না)
                return caches.match(event.request, { ignoreSearch: true });
            })
    );
});