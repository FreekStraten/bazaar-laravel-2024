import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Definieer stores eerst zodat components ze bij init zien
Alpine.store('fav', {
    count: parseInt(document.body.dataset.favCount || 0)
});

Alpine.start();

// Simple star rating widget: turns stars yellow on hover/click and
// writes the selected value into the associated input[name="rating"].
document.addEventListener('DOMContentLoaded', () => {
    const initRating = (root) => {
        const input = root.querySelector('input[name="rating"]');
        const stars = Array.from(root.querySelectorAll('[data-value]'));
        const text = root.querySelector('[data-rating-text]');

        let selected = parseInt(input?.value || '0', 10) || 0;

        const render = (highlight) => {
            const count = highlight ?? selected;
            stars.forEach((star, idx) => {
                const active = idx < count;
                star.classList.toggle('text-yellow-500', active);
                star.classList.toggle('text-slate-300', !active);
            });
            if (text) text.textContent = count ? `${count}/5` : '';
        };

        stars.forEach((star, idx) => {
            const val = idx + 1;
            star.addEventListener('mouseenter', () => render(val));
            star.addEventListener('mouseleave', () => render());
            star.addEventListener('click', (e) => {
                e.preventDefault();
                selected = val;
                if (input) input.value = String(selected);
                render();
            });
            // Keyboard support when focused
            star.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selected = val;
                    if (input) input.value = String(selected);
                    render();
                }
            });
            // Make stars focusable for a11y
            star.setAttribute('tabindex', '0');
        });

        render();
    };

    document.querySelectorAll('[data-rating]').forEach(initRating);
});
