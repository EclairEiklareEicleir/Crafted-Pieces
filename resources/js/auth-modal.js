document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('auth-required-modal');

    if (!modal) {
        return;
    }

    const closeButton = document.getElementById('auth-required-close');
    const closeTriggers = modal.querySelectorAll('[data-auth-modal-close]');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('[data-auth-modal-open]').forEach(function (trigger) {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            openModal();
        });
    });

    closeButton?.addEventListener('click', closeModal);

    closeTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', closeModal);
    });

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
