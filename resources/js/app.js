import './bootstrap';
import { createIcons, icons } from 'lucide';

// Global icon renderer
window.renderLucideIcons = function () {
    createIcons({ icons });
};

document.addEventListener('DOMContentLoaded', () => {
    window.renderLucideIcons();
});
