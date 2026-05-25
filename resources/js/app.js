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
	let showTimer = null;

	if (!overlay) {
		return;
	}

	const hideOverlay = () => {
		if (showTimer) {
			window.clearTimeout(showTimer);
			showTimer = null;
		}

		if (fallbackHideTimer) {
			window.clearTimeout(fallbackHideTimer);
			fallbackHideTimer = null;
		}

		overlay.classList.remove('is-visible');
		overlay.hidden = true;
	};

	const showOverlay = () => {
		if (showTimer) {
			window.clearTimeout(showTimer);
		}

		if (fallbackHideTimer) {
			window.clearTimeout(fallbackHideTimer);
		}

		showTimer = window.setTimeout(() => {
			showTimer = null;
			overlay.hidden = false;
			overlay.classList.add('is-visible');
			fallbackHideTimer = window.setTimeout(hideOverlay, 1400);
		}, 120);
	};

	const hasNoLoadingMarker = (element) => {
		return element instanceof Element && Boolean(element.closest('[data-no-loading], [data-no-global-loader]'));
	};

	const isModifiedClick = (event) => {
		return event.defaultPrevented
			|| event.button !== 0
			|| event.metaKey
			|| event.ctrlKey
			|| event.shiftKey
			|| event.altKey;
	};

	const shouldSkipUrl = (url, rawHref) => {
		if (!rawHref || rawHref.startsWith('#') || rawHref.startsWith('javascript:')) {
			return true;
		}

		if (!['http:', 'https:'].includes(url.protocol)) {
			return true;
		}

		if (url.origin !== window.location.origin) {
			return true;
		}

		return url.pathname === window.location.pathname
			&& url.search === window.location.search
			&& url.hash !== '';
	};

	document.addEventListener('submit', (event) => {
		if (event.defaultPrevented) {
			return;
		}

		const form = event.target;

		if (!(form instanceof HTMLFormElement)) {
			return;
		}

		if (hasNoLoadingMarker(form) || form.dataset.noGlobalLoader === 'true') {
			return;
		}

		if (form.method.toLowerCase() === 'get') {
			return;
		}

		const submitter = event.submitter;

		if (submitter instanceof HTMLElement && (hasNoLoadingMarker(submitter) || submitter.closest('[download]'))) {
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

		if (!link || isModifiedClick(event) || hasNoLoadingMarker(link) || link.hasAttribute('download') || link.target === '_blank') {
			return;
		}

		const href = link.getAttribute('href');
		const url = new URL(link.href, window.location.href);

		if (shouldSkipUrl(url, href)) {
			return;
		}

		showOverlay();
	}, true);

	window.addEventListener('pagehide', hideOverlay);
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

function setupScrollRestoration() {
	const key = 'craftedPieces:scrollRestore';

	const savePosition = () => {
		sessionStorage.setItem(key, JSON.stringify({
			path: window.location.pathname,
			y: window.scrollY,
			hash: window.location.hash,
			createdAt: Date.now(),
		}));
	};

	document.addEventListener('submit', (event) => {
		const form = event.target;

		if (!(form instanceof HTMLFormElement) || form.dataset.noPreserveScroll === 'true') {
			return;
		}

		if (form.method.toLowerCase() === 'get') {
			return;
		}

		savePosition();
	});

	const raw = sessionStorage.getItem(key);

	if (!raw) {
		return;
	}

	sessionStorage.removeItem(key);

	try {
		const payload = JSON.parse(raw);
		const isFresh = Date.now() - Number(payload.createdAt || 0) < 60000;

		if (!isFresh) {
			return;
		}

		if (payload.path !== window.location.pathname) {
			return;
		}

		window.requestAnimationFrame(() => {
			if (window.location.hash) {
				const target = document.querySelector(window.location.hash);

				if (target) {
					target.scrollIntoView({ block: 'start' });
					return;
				}
			}

			window.scrollTo({ top: Number(payload.y || 0), left: 0, behavior: 'auto' });
		});
	} catch (error) {
		sessionStorage.removeItem(key);
	}
}

function setupFlashDismissal() {
	const alerts = document.querySelectorAll('[data-flash-alert]');

	alerts.forEach((alert) => {
		const dismiss = () => {
			alert.classList.add('opacity-0', 'translate-y-1');
			window.setTimeout(() => alert.remove(), 300);
		};

		alert.querySelectorAll('[data-flash-dismiss]').forEach((button) => {
			button.addEventListener('click', dismiss);
		});

		if (alert.dataset.flashAutoDismiss === 'false') {
			return;
		}

		const delay = Number(alert.dataset.flashDelay || 4500);
		window.setTimeout(dismiss, delay);
	});
}

function setupConfirmationModals() {
	const forms = document.querySelectorAll('form[data-confirm-title]');

	if (!forms.length) {
		return;
	}

	const modal = document.createElement('div');
	modal.className = 'fixed inset-0 z-[90] hidden items-center justify-center bg-brand-primary/35 px-4 py-6 backdrop-blur-sm';
	modal.setAttribute('role', 'dialog');
	modal.setAttribute('aria-modal', 'true');
	modal.innerHTML = `
		<div class="w-full max-w-md rounded-[2rem] border border-brand-border bg-white p-6 shadow-2xl shadow-brand-primary/20">
			<p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary" data-confirm-step>Confirmation</p>
			<h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary" data-confirm-title></h2>
			<p class="mt-3 text-sm leading-6 text-brand-ink/70" data-confirm-message></p>
			<div class="mt-6 flex flex-wrap justify-end gap-3">
				<button type="button" class="brand-btn-secondary px-5 py-2 text-sm" data-confirm-cancel>Cancel</button>
				<button type="button" class="brand-btn-primary px-5 py-2 text-sm" data-confirm-continue>Continue</button>
			</div>
		</div>
	`;

	document.body.appendChild(modal);

	const title = modal.querySelector('[data-confirm-title]');
	const message = modal.querySelector('[data-confirm-message]');
	const step = modal.querySelector('[data-confirm-step]');
	const cancelButton = modal.querySelector('[data-confirm-cancel]');
	const continueButton = modal.querySelector('[data-confirm-continue]');
	let activeForm = null;
	let activeSubmitter = null;
	let isFinalStep = false;

	const selectedCount = (form) => {
		const selector = form.dataset.confirmCountSelector;

		if (!selector) {
			return 0;
		}

		return document.querySelectorAll(selector).length;
	};

	const resolveMessage = (template, form) => {
		return (template || '').replace('{count}', String(selectedCount(form)));
	};

	const close = () => {
		modal.classList.add('hidden');
		modal.classList.remove('flex');
		document.body.classList.remove('overflow-hidden');
		activeForm = null;
		activeSubmitter = null;
		isFinalStep = false;
	};

	const render = (form, final = false) => {
		isFinalStep = final;
		step.textContent = final ? 'Final confirmation' : 'Confirmation';
		title.textContent = final ? (form.dataset.confirmFinalTitle || 'Final Confirmation') : form.dataset.confirmTitle;
		message.textContent = final
			? resolveMessage(form.dataset.confirmFinalMessage || form.dataset.confirmMessage, form)
			: resolveMessage(form.dataset.confirmMessage, form);
		continueButton.textContent = final
			? (form.dataset.confirmFinalAction || 'Yes, Continue')
			: (form.dataset.confirmAction || 'Continue');
	};

	const open = (form, submitter) => {
		activeForm = form;
		activeSubmitter = submitter;
		render(form, false);
		modal.classList.remove('hidden');
		modal.classList.add('flex');
		document.body.classList.add('overflow-hidden');
		cancelButton.focus();
	};

	forms.forEach((form) => {
		form.addEventListener('submit', (event) => {
			if (form.dataset.confirmed === 'true') {
				delete form.dataset.confirmed;
				return;
			}

			if (event.defaultPrevented) {
				return;
			}

			const requireSelector = form.dataset.confirmRequireSelector;

			if (requireSelector && document.querySelectorAll(requireSelector).length === 0) {
				event.preventDefault();
				const error = form.dataset.confirmErrorSelector
					? document.querySelector(form.dataset.confirmErrorSelector)
					: null;
				error?.classList.remove('hidden');
				return;
			}

			event.preventDefault();
			open(form, event.submitter);
		});
	});

	cancelButton.addEventListener('click', close);

	modal.addEventListener('click', (event) => {
		if (event.target === modal) {
			close();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
			close();
		}
	});

	continueButton.addEventListener('click', () => {
		if (!activeForm) {
			return;
		}

		const hasFinalStep = Boolean(activeForm.dataset.confirmFinalTitle || activeForm.dataset.confirmFinalMessage);

		if (hasFinalStep && !isFinalStep) {
			render(activeForm, true);
			return;
		}

		const formToSubmit = activeForm;
		const submitter = activeSubmitter;
		formToSubmit.dataset.confirmed = 'true';
		close();

		if (submitter instanceof HTMLElement) {
			formToSubmit.requestSubmit(submitter);
		} else {
			formToSubmit.requestSubmit();
		}
	});
}

document.addEventListener('DOMContentLoaded', () => {
	scrollMessageThreadsToBottom();
	setupScrollRestoration();
	setupFlashDismissal();
	setupConfirmationModals();
	setupGlobalLoadingOverlay();
});
