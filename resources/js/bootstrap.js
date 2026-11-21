// Bootstrap configuration for frontend interactions

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    window.csrfToken = token;
}
