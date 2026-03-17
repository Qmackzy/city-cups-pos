self.addEventListener('install', (e) => {
    console.log('Service Worker: Installed');
});

self.addEventListener('fetch', (e) => {
    // Biarkan request lewat seperti biasa untuk sekarang
});