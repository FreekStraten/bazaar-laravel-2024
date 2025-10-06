import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


Alpine.store('fav', {
    count: parseInt(document.body.dataset.favCount || 0)
})
