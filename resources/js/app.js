import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const prefetchedUrls = new Set();

const canPrefetchLink = (anchor) => {
	if (!anchor || anchor.target === '_blank' || anchor.hasAttribute('download')) {
		return false;
	}

	const href = anchor.getAttribute('href');
	if (!href || href.startsWith('#')) {
		return false;
	}

	const url = new URL(anchor.href, window.location.origin);

	if (url.origin !== window.location.origin) {
		return false;
	}

	if (url.pathname === window.location.pathname && url.search === window.location.search) {
		return false;
	}

	return true;
};

const prefetchPage = (href) => {
	if (prefetchedUrls.has(href)) {
		return;
	}

	prefetchedUrls.add(href);

	const link = document.createElement('link');
	link.rel = 'prefetch';
	link.as = 'document';
	link.href = href;
	document.head.appendChild(link);
};

document.addEventListener('mouseover', (event) => {
	const anchor = event.target.closest('a');
	if (!canPrefetchLink(anchor)) {
		return;
	}

	prefetchPage(anchor.href);
});

document.addEventListener('touchstart', (event) => {
	const anchor = event.target.closest('a');
	if (!canPrefetchLink(anchor)) {
		return;
	}

	prefetchPage(anchor.href);
}, { passive: true });
