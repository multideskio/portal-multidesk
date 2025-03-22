const CACHE_NAME = "my-app-cache-v1";
const urlsToCache = [
  "/",
  // "/index.html",
  // "/styles.css",
  // "/script.js",
  // "/icon-192x192.png",
  // "/icon-512x512.png"
];

// Instala o Service Worker e adiciona arquivos ao cache
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log("Arquivos em cache");
      return cache.addAll(urlsToCache);
    })
  );
});

// Intercepta requisições para servir arquivos do cache
self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});

// Remove caches antigos durante a ativação
self.addEventListener("activate", event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(keyList =>
      Promise.all(
        keyList.map(key => {
          if (!cacheWhitelist.includes(key)) {
            return caches.delete(key);
          }
        })
      )
    )
  );
});