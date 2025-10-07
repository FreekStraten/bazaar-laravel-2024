import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Definieer stores eerst zodat components ze bij init zien
Alpine.store('fav', {
    count: parseInt(document.body.dataset.favCount || 0)
});

Alpine.start();
