// Gerenciador de registro e operações do Service Worker
const ServiceWorkerManager = {
    register() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(registration => {
                        //console.log('Service Worker registrado com sucesso:', registration);
                    })
                    .catch(error => {
                        console.error('Erro ao registrar o Service Worker:', error);
                    });
            });
        } else {
            console.warn('Service Workers não são suportados neste navegador.');
        }
    },

    refreshCache() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(registrations => {
                registrations.forEach(registration => {
                    registration.unregister().then(() => {
                        alert('Cache renovado: Novo Service Worker será registrado ao recarregar a página.');
                        console.log('Service Worker desregistrado.');
                        window.location.reload(); // Recarrega a página
                    });
                });
            });
        } else {
            alert('Service Workers não são suportados neste navegador.');
        }
    }
};

// Gerenciador de cache
const CacheManager = {
    clearCache() {
        if ('caches' in window) {
            caches.keys()
                .then(cacheNames => Promise.all(cacheNames.map(cacheName => caches.delete(cacheName))))
                .then(() => {
                    alert('O cache foi apagado com sucesso!');
                    console.log('Cache limpo!');
                })
                .catch(error => {
                    console.error('Erro ao limpar o cache:', error);
                });
        } else {
            alert('API de Cache não é suportada neste navegador.');
        }
    }
};

// Inicializar a aplicação
function initApp() {
    console.log('Aplicação inicializada!');

    const clearCacheBtn = document.getElementById('clearCacheBtn');
    const refreshCacheBtn = document.getElementById('refreshCacheBtn');

    if (clearCacheBtn && refreshCacheBtn) {
        clearCacheBtn.addEventListener('click', CacheManager.clearCache);
        refreshCacheBtn.addEventListener('click', ServiceWorkerManager.refreshCache);
    } else {
        //console.error('Os botões não foram encontrados no DOM. Verifique os IDs ou certifique-se de que estão sendo criados no HTML.');
    }
}

// Registro do Service Worker
ServiceWorkerManager.register();

// Inicialização após o DOM estar carregado
document.addEventListener('DOMContentLoaded', initApp);