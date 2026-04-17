(function () {
	var toggle = document.querySelector('.site-header__toggle');
	var nav = document.getElementById('primary-navigation');
	if (!toggle || !nav) {
		return;
	}

	toggle.addEventListener('click', function () {
		var isOpen = nav.classList.toggle('is-open');
		toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
	});
})();
