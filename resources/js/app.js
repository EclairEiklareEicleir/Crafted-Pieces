import './bootstrap';

function scrollMessageThreadsToBottom() {
	const threads = document.querySelectorAll('[data-auto-scroll-thread]');

	threads.forEach((thread) => {
		thread.scrollTop = thread.scrollHeight;
	});
}

function setupGlobalLoadingOverlay() {
	const overlay = document.querySelector('[data-brand-loading-overlay]');
	let fallbackHideTimer = null;

	if (!overlay) {
		return;
	}

	const hideOverlay = () => {
		if (fallbackHideTimer) {
			window.clearTimeout(fallbackHideTimer);
			fallbackHideTimer = null;
		}

		overlay.classList.remove('is-visible');
		overlay.hidden = true;
	};

	const showOverlay = () => {
		if (fallbackHideTimer) {
			window.clearTimeout(fallbackHideTimer);
		}

		overlay.hidden = false;
		overlay.classList.add('is-visible');
		fallbackHideTimer = window.setTimeout(hideOverlay, 2500);
	};

	document.addEventListener('submit', (event) => {
		const form = event.target;

		if (!(form instanceof HTMLFormElement)) {
			return;
		}

		if (form.matches('[data-no-loading]') || form.dataset.noGlobalLoader === 'true') {
			return;
		}

		const submitter = event.submitter;

		if (submitter instanceof HTMLElement && submitter.closest('[data-no-loading], [download]')) {
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

		if (!link || link.closest('[data-no-loading]') || link.hasAttribute('download') || link.target === '_blank') {
			return;
		}

		const href = link.getAttribute('href');

		if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
			return;
		}

		showOverlay();
	}, true);

	window.addEventListener('pageshow', () => {
		hideOverlay();
	});

	window.addEventListener('focus', hideOverlay);

	document.addEventListener('visibilitychange', () => {
		if (!document.hidden) {
			hideOverlay();
		}
	});
}

document.addEventListener('DOMContentLoaded', () => {
	scrollMessageThreadsToBottom();
	setupGlobalLoadingOverlay();
});
