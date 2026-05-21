import './bootstrap';

function scrollMessageThreadsToBottom() {
	const threads = document.querySelectorAll('[data-auto-scroll-thread]');

	threads.forEach((thread) => {
		thread.scrollTop = thread.scrollHeight;
	});
}

function setupGlobalLoadingOverlay() {
	const overlay = document.querySelector('[data-brand-loading-overlay]');

	if (!overlay) {
		return;
	}

	const showOverlay = () => {
		overlay.hidden = false;
		overlay.classList.add('is-visible');
	};

	document.addEventListener('submit', (event) => {
		const form = event.target;

		if (!(form instanceof HTMLFormElement)) {
			return;
		}

		if (form.dataset.noGlobalLoader === 'true') {
			return;
		}

		showOverlay();
	});

	document.addEventListener('click', (event) => {
		const target = event.target;

		if (!(target instanceof Element)) {
			return;
		}

		const link = target.closest('a');

		if (!link || link.dataset.noGlobalLoader === 'true' || link.hasAttribute('download') || link.target === '_blank') {
			return;
		}

		const href = link.getAttribute('href');

		if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
			return;
		}

		showOverlay();
	}, true);

	window.addEventListener('pageshow', () => {
		overlay.classList.remove('is-visible');
		overlay.hidden = true;
	});
}

document.addEventListener('DOMContentLoaded', () => {
	scrollMessageThreadsToBottom();
	setupGlobalLoadingOverlay();
});
