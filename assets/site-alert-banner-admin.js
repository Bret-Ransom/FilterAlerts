( function () {
	let mediaFrame;

	const updateBackgroundFields = function () {
		const backgroundType = document.querySelector(
			'[data-filter-alerts-background-type]'
		);
		const solidRows = document.querySelectorAll(
			'[data-filter-alerts-background-solid]'
		);
		const gradientRows = document.querySelectorAll(
			'[data-filter-alerts-background-gradient]'
		);

		if ( ! backgroundType ) {
			return;
		}

		solidRows.forEach( function ( row ) {
			row.hidden = backgroundType.value !== 'solid';
		} );

		gradientRows.forEach( function ( row ) {
			row.hidden = backgroundType.value !== 'gradient';
		} );
	};

	document.addEventListener( 'change', function ( event ) {
		if ( event.target.matches( '[data-filter-alerts-background-type]' ) ) {
			updateBackgroundFields();
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		const uploadButton = event.target.closest(
			'[data-filter-alerts-upload-icon]'
		);
		const removeButton = event.target.closest(
			'[data-filter-alerts-remove-icon]'
		);
		const restoreButton = event.target.closest(
			'[data-filter-alerts-restore-defaults]'
		);
		const iconInput = document.querySelector(
			'[data-filter-alerts-icon-url]'
		);

		if ( restoreButton ) {
			event.preventDefault();

			const defaultsScript = document.querySelector(
				'[data-filter-alerts-banner-defaults]'
			);

			if ( ! defaultsScript ) {
				return;
			}

			const defaults = JSON.parse( defaultsScript.textContent );

			Object.keys( defaults ).forEach( function ( key ) {
				const field = document.querySelector(
					'[name="filter_alerts_site_banner_settings[' +
						key +
						']"]'
				);

				if ( field ) {
					field.value = defaults[ key ];
				}
			} );

			updateBackgroundFields();
			return;
		}

		if ( removeButton && iconInput ) {
			event.preventDefault();
			iconInput.value = '';
			return;
		}

		if ( ! uploadButton || ! iconInput || ! window.wp?.media ) {
			return;
		}

		event.preventDefault();

		if ( mediaFrame ) {
			mediaFrame.open();
			return;
		}

		mediaFrame = window.wp.media( {
			title: 'Choose Banner Icon',
			button: {
				text: 'Use this image',
			},
			multiple: false,
		} );

		mediaFrame.on( 'select', function () {
			const attachment = mediaFrame
				.state()
				.get( 'selection' )
				.first()
				.toJSON();

			if ( attachment.url ) {
				iconInput.value = attachment.url;
			}
		} );

		mediaFrame.open();
	} );

	updateBackgroundFields();
} )();
