/* Hearth & Frost — small enhancements, no dependencies. */
(function () {
	'use strict';
	var doc = document.documentElement;
	doc.classList.add('js');

	/* Header: solid after scrolling. */
	var header = document.querySelector('[data-header]');
	function onScroll() {
		if (header) header.classList.toggle('is-scrolled', window.scrollY > 40);
	}
	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });

	/* Mobile menu. */
	var burger = document.querySelector('[data-burger]');
	var panel = document.querySelector('[data-mobile-menu]');
	if (burger && panel) {
		var setOpen = function (open) {
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
			panel.hidden = !open;
			document.body.classList.toggle('menu-open', open);
		};
		burger.addEventListener('click', function () { setOpen(panel.hidden); });
		panel.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !panel.hidden) { setOpen(false); burger.focus(); } });
	}

	/* Reveal on scroll. */
	var items = document.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		items.forEach(function (el, i) {
			el.style.transitionDelay = (i % 4) * 70 + 'ms';
			io.observe(el);
		});
	} else {
		items.forEach(function (el) { el.classList.add('is-in'); });
	}

	/* Reviews carousel. */
	document.querySelectorAll('[data-carousel]').forEach(function (box) {
		var slides = box.querySelectorAll('[data-slide]');
		var count = box.querySelector('[data-count]');
		var i = 0, timer;
		if (slides.length < 2) { var nav = box.querySelector('.quote-nav'); if (nav) nav.hidden = true; return; }
		function go(n) {
			slides[i].classList.remove('is-active');
			i = (n + slides.length) % slides.length;
			slides[i].classList.add('is-active');
			if (count) count.textContent = (i + 1) + ' / ' + slides.length;
		}
		function auto() { clearInterval(timer); timer = setInterval(function () { go(i + 1); }, 7000); }
		box.querySelector('[data-next]').addEventListener('click', function () { go(i + 1); auto(); });
		box.querySelector('[data-prev]').addEventListener('click', function () { go(i - 1); auto(); });
		auto();
	});

	/* Table of contents for legal pages. */
	var toc = document.querySelector('[data-toc]');
	var src = document.querySelector('[data-toc-source]');
	if (toc && src) {
		var list = toc.querySelector('ol');
		var hs = src.querySelectorAll('h2');
		if (!hs.length) { toc.hidden = true; }
		var links = [];
		hs.forEach(function (h, n) {
			if (!h.id) h.id = 'section-' + (n + 1);
			var li = document.createElement('li');
			var a = document.createElement('a');
			a.href = '#' + h.id;
			a.textContent = h.textContent;
			li.appendChild(a);
			list.appendChild(li);
			links.push(a);
		});
		if ('IntersectionObserver' in window && links.length) {
			var spy = new IntersectionObserver(function (entries) {
				entries.forEach(function (en) {
					if (!en.isIntersecting) return;
					links.forEach(function (l) { l.classList.toggle('is-current', l.getAttribute('href') === '#' + en.target.id); });
				});
			}, { rootMargin: '-20% 0px -70% 0px' });
			hs.forEach(function (h) { spy.observe(h); });
		}
	}

	/* Back to top. */
	document.querySelectorAll('[data-to-top]').forEach(function (a) {
		a.addEventListener('click', function (e) { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
	});

	/* Service request form. */
	document.querySelectorAll('[data-hf-form]').forEach(function (form) {
		var msg = form.querySelector('.form-msg');
		var btn = form.querySelector('button[type=submit]');
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			form.querySelectorAll('.is-invalid').forEach(function (f) { f.classList.remove('is-invalid'); });
			var bad = [];
			var name = form.elements.name, phone = form.elements.phone, consent = form.elements.consent;
			if (!name.value.trim()) bad.push(name);
			if (phone.value.replace(/\D/g, '').length < 10) bad.push(phone);
			if (bad.length) {
				bad.forEach(function (f) { f.closest('.field').classList.add('is-invalid'); });
				msg.className = 'form-msg is-error';
				msg.textContent = 'Please enter your name and a phone number we can reach you on.';
				bad[0].focus();
				return;
			}
			if (!consent.checked) {
				msg.className = 'form-msg is-error';
				msg.textContent = 'Please agree to the Privacy Policy so we can contact you.';
				return;
			}
			var data = new FormData(form);
			data.append('action', 'hf_request');
			data.append('nonce', (window.HF && HF.nonce) || '');
			data.append('page', location.href);
			btn.disabled = true;
			msg.className = 'form-msg';
			msg.textContent = 'Sending…';
			// HF.demo (set only in the static preview copy, which has no server): answer locally with that message.
			var send = (window.HF && HF.demo)
				? new Promise(function (ok) { setTimeout(function () { ok({ json: function () { return Promise.resolve({ success: true, data: { message: HF.demo } }); } }); }, 600); })
				: fetch((window.HF && HF.ajax) || '/wp-admin/admin-ajax.php', { method: 'POST', body: data, credentials: 'same-origin' });
			send
				.then(function (r) { return r.json().catch(function () { return { success: false }; }); })
				.then(function (res) {
					btn.disabled = false;
					if (res && res.success) {
						form.classList.add('is-sent');
						msg.className = 'form-msg is-ok';
						msg.textContent = (res.data && res.data.message) || 'Thank you. We will call you shortly.';
						form.scrollIntoView({ behavior: 'smooth', block: 'center' });
					} else {
						msg.className = 'form-msg is-error';
						msg.textContent = (res && res.data && res.data.message) || 'Something went wrong. Please call us.';
					}
				})
				.catch(function () {
					btn.disabled = false;
					msg.className = 'form-msg is-error';
					msg.textContent = 'Connection problem. Please try again or call us.';
				});
		});
	});
})();
