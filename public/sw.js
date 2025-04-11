const CACHE_NAME = 'tourism-cache-v1';
const urlsToCache = [
    '/',
    '/offline',
    '/css/bootstrap.min.css',
    '/css/fontawesome-all.min.css',
    '/css/tour-operator.css',
    '/js/bootstrap.bundle.min.js',
    '/fonts/Poppins-Regular.woff2',
    '/fonts/Poppins-Medium.woff2',
    '/fonts/Poppins-SemiBold.woff2',
    '/fonts/Poppins-Bold.woff2',
    '/webfonts/fa-solid-900.woff2',
    '/webfonts/fa-regular-400.woff2',
    '/webfonts/fa-brands-400.woff2',
    '/images/favicon.png',
    '/images/logo.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request)
                    .then(response => {
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(event.request, responseToCache);
                            });
                        return response;
                    })
                    .catch(() => {
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline');
                        }
                    });
            })
    );
});

// ... rest of your service worker code ... 