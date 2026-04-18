document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-confirm-delete]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const message = form.getAttribute('data-confirm-delete') || 'Delete this product?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});
