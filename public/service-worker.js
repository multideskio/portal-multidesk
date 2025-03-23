// service-worker.js

const CACHE_NAME = 'multidesk-cache-v1'; // Nome do cache
const OFFLINE_PAGE = '/offline.html';   // Caminho para a página offline
const ASSETS_TO_CACHE = [
    //'/',
    '/app.js',
    '/manifest.json',
    '/favicon.ico',
    OFFLINE_PAGE,
    '/android-chrome-192x192.png',
    '/android-chrome-512x512.png',
    '/assets/css/style.css',
    '/assets/vendor/css/bootstrap/bootstrap.css',
    '/assets/img/logo-dark.svg',
    '/assets/img/logo-lite.svg',
    '/assets/img/authentication.svg',
    '/assets/vendor/js/jquery/jquery-3.5.1.min.js',
    '/assets/vendor/js/jquery/jquery-ui.js',
    '/assets/vendor/js/bootstrap/bootstrap.min.js',
    '/assets/js/main.js',
    '/assets/js/login.js',
    '/assets/img/icon/google.svg',
    '/screenshot-desktop.png',
    '/assets/img/favicon.svg'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Interceptando requisições
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            // Retorna o recurso do cache, se disponível
            return response || fetch(event.request).catch(() => {
                // Se offline, retorna a página de "sem internet"
                if (event.request.mode === 'navigate') {
                    return caches.match(OFFLINE_PAGE);
                }
            });
        })
    );
});