self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('fetch', (event) => {
  // Biarkan kosong, hanya untuk syarat instalasi
});