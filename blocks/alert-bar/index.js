( function (
	blocks,
	element,
	i18n,
	blockEditor,
	components,
	ServerSideRender,
	apiFetch
) {
	const el = element.createElement;
	const __ = i18n.__;
	const Fragment = element.Fragment;
	const useEffect = element.useEffect;
	const useState = element.useState;
	const useBlockProps = blockEditor.useBlockProps;
	const InspectorControls = blockEditor.InspectorControls;
	const MediaUpload = blockEditor.MediaUpload;
	const MediaUploadCheck = blockEditor.MediaUploadCheck;
	const Button = components.Button;
	const ColorPicker = components.ColorPicker;
	const PanelBody = components.PanelBody;
	const RangeControl = components.RangeControl;
	const SelectControl = components.SelectControl;
	const TextControl = components.TextControl;
	const SITE_BANNER_OPTION = 'filter_alerts_site_banner_settings';

	const DEFAULTS = {
		backgroundType: 'gradient',
		solidColor: '#6f8cc3',
		gradientStart: '#6f8cc3',
		gradientEnd: '#9fb5df',
		textColor: '#ffffff',
		fontSize: 13,
		toggleShowBackgroundType: 'solid',
		toggleShowSolidColor: '#7b7b7b',
		toggleShowGradientStart: '#7b7b7b',
		toggleShowGradientEnd: '#5f5f5f',
		toggleHideBackgroundType: 'solid',
		toggleHideSolidColor: '#7b7b7b',
		toggleHideGradientStart: '#7b7b7b',
		toggleHideGradientEnd: '#5f5f5f',
		inactiveBackgroundType: 'solid',
		inactiveSolidColor: '#cccccc',
		inactiveGradientStart: '#000000',
		inactiveGradientEnd: '#333333',
	};

	const DEFAULT_SITE_BANNER_SETTINGS = {
		icon_url: '',
		multiple_label: __( '2 or more Active Alerts', 'filter-alerts' ),
		background_type: 'gradient',
		solid_color: '#d4ebff',
		gradient_start: '#eef7ff',
		gradient_end: '#d4ebff',
		badge_color: '#dce6fb',
		arrow_color: '#0075d8',
	};

	const ColorControl = function ( props ) {
		return el(
			'div',
			{
				className: 'filter-alerts-alert-bar-control',
			},
			el(
				'p',
				{
					className: 'filter-alerts-alert-bar-control__label',
				},
				props.label
			),
			el( ColorPicker, {
				color: props.value,
				onChange: props.onChange,
				enableAlpha: false,
				defaultValue: props.defaultValue,
			} )
		);
	};

	const AlertBar = function ( props ) {
		const attributes = props.attributes;
		const setAttributes = props.setAttributes;
		const backgroundType =
			attributes.backgroundType || DEFAULTS.backgroundType;
		const toggleShowBackgroundType =
			attributes.toggleShowBackgroundType ||
			DEFAULTS.toggleShowBackgroundType;
		const toggleHideBackgroundType =
			attributes.toggleHideBackgroundType ||
			DEFAULTS.toggleHideBackgroundType;
		const inactiveBackgroundType =
			attributes.inactiveBackgroundType ||
			DEFAULTS.inactiveBackgroundType;
		const [ siteBannerSettings, setSiteBannerSettings ] = useState(
			DEFAULT_SITE_BANNER_SETTINGS
		);
		const [
			isSavingSiteBannerSettings,
			setIsSavingSiteBannerSettings,
		] = useState( false );
		const blockProps = useBlockProps( {
			className: 'filter-alerts-alert-table-editor',
		} );
		const updateSiteBannerSettings = function ( nextSettings ) {
			const updatedSettings = {
				...siteBannerSettings,
				...nextSettings,
			};

			setSiteBannerSettings( updatedSettings );
			setIsSavingSiteBannerSettings( true );

			apiFetch( {
				path: '/wp/v2/settings',
				method: 'POST',
				data: {
					[ SITE_BANNER_OPTION ]: updatedSettings,
				},
			} )
				.then( function ( response ) {
					if ( response[ SITE_BANNER_OPTION ] ) {
						setSiteBannerSettings( {
							...DEFAULT_SITE_BANNER_SETTINGS,
							...response[ SITE_BANNER_OPTION ],
						} );
					}
				} )
				.finally( function () {
					setIsSavingSiteBannerSettings( false );
				} );
		};

		useEffect( function () {
			apiFetch( {
				path: '/wp/v2/settings',
			} ).then( function ( response ) {
				if ( response[ SITE_BANNER_OPTION ] ) {
					setSiteBannerSettings( {
						...DEFAULT_SITE_BANNER_SETTINGS,
						...response[ SITE_BANNER_OPTION ],
					} );
				}
			} );
		}, [] );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{
						title: __( 'Alert Bar Style', 'filter-alerts' ),
						initialOpen: true,
					},
					el( SelectControl, {
						label: __( 'Background', 'filter-alerts' ),
						value: backgroundType,
						options: [
							{
								label: __( 'Gradient', 'filter-alerts' ),
								value: 'gradient',
							},
							{
								label: __( 'Solid', 'filter-alerts' ),
								value: 'solid',
							},
						],
						onChange: function ( value ) {
							setAttributes( {
								backgroundType: value,
							} );
						},
					} ),
					backgroundType === 'solid'
						? el( ColorControl, {
								label: __( 'Solid Color', 'filter-alerts' ),
								value:
									attributes.solidColor ||
									DEFAULTS.solidColor,
								defaultValue: DEFAULTS.solidColor,
								onChange: function ( value ) {
									setAttributes( {
										solidColor: value,
									} );
								},
						  } )
						: el(
								Fragment,
								null,
								el( ColorControl, {
									label: __(
										'Gradient Start',
										'filter-alerts'
									),
									value:
										attributes.gradientStart ||
										DEFAULTS.gradientStart,
									defaultValue: DEFAULTS.gradientStart,
									onChange: function ( value ) {
										setAttributes( {
											gradientStart: value,
										} );
									},
								} ),
								el( ColorControl, {
									label: __(
										'Gradient End',
										'filter-alerts'
									),
									value:
										attributes.gradientEnd ||
										DEFAULTS.gradientEnd,
									defaultValue: DEFAULTS.gradientEnd,
									onChange: function ( value ) {
										setAttributes( {
											gradientEnd: value,
										} );
									},
								} )
						  ),
					el( ColorControl, {
						label: __( 'Text Color', 'filter-alerts' ),
						value: attributes.textColor || DEFAULTS.textColor,
						defaultValue: DEFAULTS.textColor,
						onChange: function ( value ) {
							setAttributes( {
								textColor: value,
							} );
						},
					} ),
					el( RangeControl, {
						label: __( 'Font Size', 'filter-alerts' ),
						value: attributes.fontSize || DEFAULTS.fontSize,
						onChange: function ( value ) {
							setAttributes( {
								fontSize: value || DEFAULTS.fontSize,
							} );
						},
						min: 10,
						max: 32,
					} )
				),
				el(
					PanelBody,
					{
						title: __(
							'Toggle SHOW Background',
							'filter-alerts'
						),
						initialOpen: false,
					},
					el( SelectControl, {
						label: __( 'Background', 'filter-alerts' ),
						value: toggleShowBackgroundType,
						options: [
							{
								label: __( 'Gradient', 'filter-alerts' ),
								value: 'gradient',
							},
							{
								label: __( 'Solid', 'filter-alerts' ),
								value: 'solid',
							},
						],
						onChange: function ( value ) {
							setAttributes( {
								toggleShowBackgroundType: value,
							} );
						},
					} ),
					toggleShowBackgroundType === 'solid'
						? el( ColorControl, {
								label: __( 'Solid Color', 'filter-alerts' ),
								value:
									attributes.toggleShowSolidColor ||
									DEFAULTS.toggleShowSolidColor,
								defaultValue: DEFAULTS.toggleShowSolidColor,
								onChange: function ( value ) {
									setAttributes( {
										toggleShowSolidColor: value,
									} );
								},
						  } )
						: el(
								Fragment,
								null,
								el( ColorControl, {
									label: __(
										'Gradient Start',
										'filter-alerts'
									),
									value:
										attributes.toggleShowGradientStart ||
										DEFAULTS.toggleShowGradientStart,
									defaultValue:
										DEFAULTS.toggleShowGradientStart,
									onChange: function ( value ) {
										setAttributes( {
											toggleShowGradientStart: value,
										} );
									},
								} ),
								el( ColorControl, {
									label: __(
										'Gradient End',
										'filter-alerts'
									),
									value:
										attributes.toggleShowGradientEnd ||
										DEFAULTS.toggleShowGradientEnd,
									defaultValue:
										DEFAULTS.toggleShowGradientEnd,
									onChange: function ( value ) {
										setAttributes( {
											toggleShowGradientEnd: value,
										} );
									},
								} )
						  )
				),
				el(
					PanelBody,
					{
						title: __(
							'Toggle HIDE Background',
							'filter-alerts'
						),
						initialOpen: false,
					},
					el( SelectControl, {
						label: __( 'Background', 'filter-alerts' ),
						value: toggleHideBackgroundType,
						options: [
							{
								label: __( 'Gradient', 'filter-alerts' ),
								value: 'gradient',
							},
							{
								label: __( 'Solid', 'filter-alerts' ),
								value: 'solid',
							},
						],
						onChange: function ( value ) {
							setAttributes( {
								toggleHideBackgroundType: value,
							} );
						},
					} ),
					toggleHideBackgroundType === 'solid'
						? el( ColorControl, {
								label: __( 'Solid Color', 'filter-alerts' ),
								value:
									attributes.toggleHideSolidColor ||
									DEFAULTS.toggleHideSolidColor,
								defaultValue: DEFAULTS.toggleHideSolidColor,
								onChange: function ( value ) {
									setAttributes( {
										toggleHideSolidColor: value,
									} );
								},
						  } )
						: el(
								Fragment,
								null,
								el( ColorControl, {
									label: __(
										'Gradient Start',
										'filter-alerts'
									),
									value:
										attributes.toggleHideGradientStart ||
										DEFAULTS.toggleHideGradientStart,
									defaultValue:
										DEFAULTS.toggleHideGradientStart,
									onChange: function ( value ) {
										setAttributes( {
											toggleHideGradientStart: value,
										} );
									},
								} ),
								el( ColorControl, {
									label: __(
										'Gradient End',
										'filter-alerts'
									),
									value:
										attributes.toggleHideGradientEnd ||
										DEFAULTS.toggleHideGradientEnd,
									defaultValue:
										DEFAULTS.toggleHideGradientEnd,
									onChange: function ( value ) {
										setAttributes( {
											toggleHideGradientEnd: value,
										} );
									},
								} )
						  )
				),
				el(
					PanelBody,
					{
						title: __(
							'Inactive Alert Background',
							'filter-alerts'
						),
						initialOpen: false,
					},
					el( SelectControl, {
						label: __( 'Background', 'filter-alerts' ),
						value: inactiveBackgroundType,
						options: [
							{
								label: __( 'Gradient', 'filter-alerts' ),
								value: 'gradient',
							},
							{
								label: __( 'Solid', 'filter-alerts' ),
								value: 'solid',
							},
						],
						onChange: function ( value ) {
							setAttributes( {
								inactiveBackgroundType: value,
							} );
						},
					} ),
					inactiveBackgroundType === 'solid'
						? el( ColorControl, {
								label: __( 'Solid Color', 'filter-alerts' ),
								value:
									attributes.inactiveSolidColor ||
									DEFAULTS.inactiveSolidColor,
								defaultValue: DEFAULTS.inactiveSolidColor,
								onChange: function ( value ) {
									setAttributes( {
										inactiveSolidColor: value,
									} );
								},
						  } )
						: el(
								Fragment,
								null,
								el( ColorControl, {
									label: __(
										'Gradient Start',
										'filter-alerts'
									),
									value:
										attributes.inactiveGradientStart ||
										DEFAULTS.inactiveGradientStart,
									defaultValue:
										DEFAULTS.inactiveGradientStart,
									onChange: function ( value ) {
										setAttributes( {
											inactiveGradientStart: value,
										} );
									},
								} ),
								el( ColorControl, {
									label: __(
										'Gradient End',
										'filter-alerts'
									),
									value:
										attributes.inactiveGradientEnd ||
										DEFAULTS.inactiveGradientEnd,
									defaultValue:
										DEFAULTS.inactiveGradientEnd,
									onChange: function ( value ) {
										setAttributes( {
											inactiveGradientEnd: value,
										} );
									},
								} )
						  )
				),
				el(
					PanelBody,
					{
						title: __( 'Reset Block Settings', 'filter-alerts' ),
						initialOpen: false,
					},
					el(
						Button,
						{
							variant: 'secondary',
							onClick: function () {
								setAttributes( {
									backgroundType: DEFAULTS.backgroundType,
									solidColor: DEFAULTS.solidColor,
									gradientStart: DEFAULTS.gradientStart,
									gradientEnd: DEFAULTS.gradientEnd,
									textColor: DEFAULTS.textColor,
									fontSize: DEFAULTS.fontSize,
									toggleShowBackgroundType:
										DEFAULTS.toggleShowBackgroundType,
									toggleShowSolidColor:
										DEFAULTS.toggleShowSolidColor,
									toggleShowGradientStart:
										DEFAULTS.toggleShowGradientStart,
									toggleShowGradientEnd:
										DEFAULTS.toggleShowGradientEnd,
									toggleHideBackgroundType:
										DEFAULTS.toggleHideBackgroundType,
									toggleHideSolidColor:
										DEFAULTS.toggleHideSolidColor,
									toggleHideGradientStart:
										DEFAULTS.toggleHideGradientStart,
									toggleHideGradientEnd:
										DEFAULTS.toggleHideGradientEnd,
									inactiveBackgroundType:
										DEFAULTS.inactiveBackgroundType,
									inactiveSolidColor:
										DEFAULTS.inactiveSolidColor,
									inactiveGradientStart:
										DEFAULTS.inactiveGradientStart,
									inactiveGradientEnd:
										DEFAULTS.inactiveGradientEnd,
								} );
							},
						},
						__( 'Restore Block Defaults', 'filter-alerts' )
					)
				),
				el(
					PanelBody,
					{
						title: __(
							'Global Active Alert Banner',
							'filter-alerts'
						),
						initialOpen: false,
					},
					el(
						'p',
						{
							className:
								'filter-alerts-alert-bar-control__label',
						},
						__( 'Icon', 'filter-alerts' )
					),
					el(
						MediaUploadCheck,
						null,
						el( MediaUpload, {
							allowedTypes: [ 'image' ],
							onSelect: function ( media ) {
								updateSiteBannerSettings( {
									icon_url: media.url || '',
								} );
							},
							render: function ( mediaProps ) {
								return el(
									Fragment,
									null,
									el(
										Button,
										{
											variant: 'secondary',
											onClick: mediaProps.open,
										},
										siteBannerSettings.icon_url
											? __(
													'Replace Image',
													'filter-alerts'
											  )
											: __(
													'Choose Image',
													'filter-alerts'
											  )
									),
									siteBannerSettings.icon_url
										? el(
												Button,
												{
													variant: 'tertiary',
													onClick: function () {
														updateSiteBannerSettings(
															{
																icon_url: '',
															}
														);
													},
												},
												__( 'Remove', 'filter-alerts' )
										  )
										: null
								);
							},
						} )
					),
					el( TextControl, {
						label: __(
							'Multiple Alerts Text',
							'filter-alerts'
						),
						value: siteBannerSettings.multiple_label,
						onChange: function ( value ) {
							updateSiteBannerSettings( {
								multiple_label: value,
							} );
						},
					} ),
					el(
						'p',
						{
							className:
								'filter-alerts-alert-bar-control__help',
						},
						__(
							'Overrides the banner text when changed.',
							'filter-alerts'
						)
					),
					el( SelectControl, {
						label: __( 'Background', 'filter-alerts' ),
						value: siteBannerSettings.background_type,
						options: [
							{
								label: __( 'Gradient', 'filter-alerts' ),
								value: 'gradient',
							},
							{
								label: __( 'Solid', 'filter-alerts' ),
								value: 'solid',
							},
						],
						onChange: function ( value ) {
							updateSiteBannerSettings( {
								background_type: value,
							} );
						},
					} ),
					siteBannerSettings.background_type === 'solid'
						? el( ColorControl, {
								label: __(
									'Solid Background Color',
									'filter-alerts'
								),
								value: siteBannerSettings.solid_color,
								defaultValue:
									DEFAULT_SITE_BANNER_SETTINGS.solid_color,
								onChange: function ( value ) {
									updateSiteBannerSettings( {
										solid_color: value,
									} );
								},
						  } )
						: el(
								Fragment,
								null,
								el( ColorControl, {
									label: __(
										'Gradient Start Color',
										'filter-alerts'
									),
									value: siteBannerSettings.gradient_start,
									defaultValue:
										DEFAULT_SITE_BANNER_SETTINGS.gradient_start,
									onChange: function ( value ) {
										updateSiteBannerSettings( {
											gradient_start: value,
										} );
									},
								} ),
								el( ColorControl, {
									label: __(
										'Gradient End Color',
										'filter-alerts'
									),
									value: siteBannerSettings.gradient_end,
									defaultValue:
										DEFAULT_SITE_BANNER_SETTINGS.gradient_end,
									onChange: function ( value ) {
										updateSiteBannerSettings( {
											gradient_end: value,
										} );
									},
								} )
						  ),
					el( ColorControl, {
						label: __(
							'Icon/Text Box Background Color',
							'filter-alerts'
						),
						value: siteBannerSettings.badge_color,
						defaultValue:
							DEFAULT_SITE_BANNER_SETTINGS.badge_color,
						onChange: function ( value ) {
							updateSiteBannerSettings( {
								badge_color: value,
							} );
						},
					} ),
					el( ColorControl, {
						label: __( 'Arrow Color', 'filter-alerts' ),
						value: siteBannerSettings.arrow_color,
						defaultValue:
							DEFAULT_SITE_BANNER_SETTINGS.arrow_color,
						onChange: function ( value ) {
							updateSiteBannerSettings( {
								arrow_color: value,
							} );
						},
					} ),
					isSavingSiteBannerSettings
						? el(
								'p',
								{
									className:
										'filter-alerts-alert-bar-control__help',
								},
								__( 'Saving...', 'filter-alerts' )
						  )
						: null,
					el(
						Button,
						{
							variant: 'secondary',
							onClick: function () {
								updateSiteBannerSettings(
									DEFAULT_SITE_BANNER_SETTINGS
								);
							},
						},
						__( 'Restore Banner Defaults', 'filter-alerts' )
					)
				)
			),
			el(
				'div',
				blockProps,
				el( ServerSideRender, {
					block: 'filter-alerts/alert-bar',
					attributes: attributes,
				} )
			)
		);
	};

	blocks.registerBlockType( 'filter-alerts/alert-bar', {
		apiVersion: 3,
		title: __( 'Alert Bar', 'filter-alerts' ),
		category: 'filter-alerts',
		icon: 'warning',
		description: __(
			'Displays the top section of a filter alert block.',
			'filter-alerts'
		),
		supports: {
			align: [ 'wide', 'full' ],
			html: false,
		},
		attributes: {
			backgroundType: {
				type: 'string',
				default: DEFAULTS.backgroundType,
			},
			solidColor: {
				type: 'string',
				default: DEFAULTS.solidColor,
			},
			gradientStart: {
				type: 'string',
				default: DEFAULTS.gradientStart,
			},
			gradientEnd: {
				type: 'string',
				default: DEFAULTS.gradientEnd,
			},
			textColor: {
				type: 'string',
				default: DEFAULTS.textColor,
			},
			fontSize: {
				type: 'number',
				default: DEFAULTS.fontSize,
			},
			toggleShowBackgroundType: {
				type: 'string',
				default: DEFAULTS.toggleShowBackgroundType,
			},
			toggleShowSolidColor: {
				type: 'string',
				default: DEFAULTS.toggleShowSolidColor,
			},
			toggleShowGradientStart: {
				type: 'string',
				default: DEFAULTS.toggleShowGradientStart,
			},
			toggleShowGradientEnd: {
				type: 'string',
				default: DEFAULTS.toggleShowGradientEnd,
			},
			toggleHideBackgroundType: {
				type: 'string',
				default: DEFAULTS.toggleHideBackgroundType,
			},
			toggleHideSolidColor: {
				type: 'string',
				default: DEFAULTS.toggleHideSolidColor,
			},
			toggleHideGradientStart: {
				type: 'string',
				default: DEFAULTS.toggleHideGradientStart,
			},
			toggleHideGradientEnd: {
				type: 'string',
				default: DEFAULTS.toggleHideGradientEnd,
			},
			inactiveBackgroundType: {
				type: 'string',
				default: DEFAULTS.inactiveBackgroundType,
			},
			inactiveSolidColor: {
				type: 'string',
				default: DEFAULTS.inactiveSolidColor,
			},
			inactiveGradientStart: {
				type: 'string',
				default: DEFAULTS.inactiveGradientStart,
			},
			inactiveGradientEnd: {
				type: 'string',
				default: DEFAULTS.inactiveGradientEnd,
			},
		},
		edit: AlertBar,
		save: function () {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.i18n,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.serverSideRender,
	window.wp.apiFetch
);
