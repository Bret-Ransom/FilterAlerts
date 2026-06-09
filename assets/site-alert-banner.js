( function () {
	document.addEventListener( 'click', function ( event ) {
		const toggle = event.target.closest(
			'[data-filter-alerts-site-banner-toggle]'
		);

		if ( ! toggle ) {
			return;
		}

		const banner = toggle.closest( '[data-filter-alerts-site-banner]' );

		if ( ! banner ) {
			return;
		}

		const isExpanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
		toggle.setAttribute( 'aria-expanded', isExpanded ? 'false' : 'true' );
		banner.classList.toggle(
			'filter-alerts-site-banner-wrap--expanded',
			! isExpanded
		);
	} );
} )();
