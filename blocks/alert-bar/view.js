( function () {
	const updateToggle = function ( toggle ) {
		const isPressed = toggle.getAttribute( 'aria-pressed' ) === 'true';
		const block = toggle.closest( '.filter-alerts-alert-table' );
		const label = toggle.querySelector(
			'[data-filter-alerts-inactive-toggle-text]'
		);

		if ( label ) {
			label.textContent = isPressed ? 'HIDE' : 'SHOW';
		}

		if ( ! block ) {
			return;
		}

		block
			.querySelectorAll( '[data-filter-alerts-inactive-row]' )
			.forEach( function ( row ) {
				row.classList.toggle(
					'filter-alerts-alert-row--inactive-visible',
					isPressed
				);
				row.setAttribute( 'aria-hidden', isPressed ? 'false' : 'true' );
			} );
	};

	document.addEventListener( 'click', function ( event ) {
		const toggle = event.target.closest(
			'[data-filter-alerts-inactive-toggle]'
		);

		if ( ! toggle ) {
			return;
		}

		const nextState = toggle.getAttribute( 'aria-pressed' ) !== 'true';
		toggle.setAttribute( 'aria-pressed', nextState ? 'true' : 'false' );
		updateToggle( toggle );
	} );

	document
		.querySelectorAll( '[data-filter-alerts-inactive-toggle]' )
		.forEach( updateToggle );
} )();
