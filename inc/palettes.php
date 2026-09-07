<?php
/**
 * Palette switcher & high-contrast structural styling engine.
 * Brings out the authentic essence of each scene with distinct structural contrast.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	if ( ! empty( $_GET['palette'] ) ) {
		$palette = sanitize_key( $_GET['palette'] );
		setcookie( 'wb_active_palette', $palette, time() + 86400 * 30, '/' );
		$_COOKIE['wb_active_palette'] = $palette;
	}
} );

function workbench_get_palette_css( $palette_slug = '' ) {
	if ( ! $palette_slug ) {
		$palette_slug = ! empty( $_GET['palette'] ) 
			? sanitize_key( $_GET['palette'] ) 
			: 'neelakurunji';
	}

	$legacy_map = array(
		'coastal-dusk'   => 'cobalt-precision',
		'granite-ridge'  => 'cobalt-precision',
		'deccan-basalt'  => 'cobalt-precision',
		'chettinad-teak' => 'terracotta-sun',
		'laterite-mist'  => 'terracotta-sun',
		'monsoon-canopy' => 'nordic-pine',
		'chaparral-dusk' => 'nordic-pine',
		'rann-mirage'    => 'drafting-blueprint',
		'happy-hues'     => 'studio-electric',
		'kurinji'        => 'neelakurunji',
	);
	if ( isset( $legacy_map[ $palette_slug ] ) ) {
		$palette_slug = $legacy_map[ $palette_slug ];
	}

	$font_map = array(
		'neelakurunji'       => array(
			'family'   => "'Bricolage Grotesque', -apple-system, BlinkMacSystemFont, sans-serif",
			'weight'   => '800',
			'spacing'  => '-0.035em',
			'kicker'   => 'normal',
			'btn_text' => '#ffffff',
		),
		'studio-electric'    => array(
			'family'   => "'Bricolage Grotesque', -apple-system, BlinkMacSystemFont, sans-serif",
			'weight'   => '800',
			'spacing'  => '-0.035em',
			'kicker'   => 'normal',
			'btn_text' => '#001858',
		),
		'cobalt-precision'   => array(
			'family'   => "'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
			'weight'   => '750',
			'spacing'  => '-0.035em',
			'kicker'   => 'normal',
			'btn_text' => '#ffffff',
		),
		'terracotta-sun'     => array(
			'family'   => "'Newsreader', 'Iowan Old Style', Georgia, serif",
			'weight'   => '700',
			'spacing'  => '-0.02em',
			'kicker'   => 'italic',
			'btn_text' => '#ffffff',
		),
		'nordic-pine'        => array(
			'family'   => "'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
			'weight'   => '700',
			'spacing'  => '-0.03em',
			'kicker'   => 'normal',
			'btn_text' => '#ffffff',
		),
		'drafting-blueprint' => array(
			'family'   => "'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif",
			'weight'   => '700',
			'spacing'  => '-0.03em',
			'kicker'   => 'normal',
			'btn_text' => '#ffffff',
		),
	);

	$font_cfg = isset( $font_map[ $palette_slug ] ) ? $font_map[ $palette_slug ] : $font_map['neelakurunji'];

	$file = get_stylesheet_directory() . "/styles/{$palette_slug}.json";
	if ( ! file_exists( $file ) ) {
		$palette_slug = 'neelakurunji';
		$file = get_stylesheet_directory() . "/styles/neelakurunji.json";
		if ( ! file_exists( $file ) ) {
			return '';
		}
	}

	$data = json_decode( file_get_contents( $file ), true );
	if ( empty( $data['settings']['color']['palette'] ) ) {
		return '';
	}

	$css = "<style id=\"wb-palette-override\">\n:root {\n";
	foreach ( $data['settings']['color']['palette'] as $col ) {
		$slug = esc_attr( $col['slug'] );
		$val  = esc_attr( $col['color'] );
		$css .= "  --wp--preset--color--{$slug}: {$val} !important;\n";
	}
	$css .= "  --wb-font-heading: {$font_cfg['family']} !important;\n";
	$css .= "  --wb-heading-weight: {$font_cfg['weight']} !important;\n";
	$css .= "  --wb-heading-spacing: {$font_cfg['spacing']} !important;\n";
	$css .= "  --wb-btn-text: {$font_cfg['btn_text']} !important;\n";
	$css .= "}\n";
	$css .= '
	/* Structural Contrast & Atmosphere Engine */
	body, .wp-site-blocks { 
		background-color: var(--wp--preset--color--page) !important; 
		color: var(--wp--preset--color--body) !important; 
	}
	h1, .wp-block-post-title {
		font-family: var(--wb-font-heading) !important;
		color: var(--wp--preset--color--ink) !important;
		font-weight: var(--wb-heading-weight) !important;
		letter-spacing: var(--wb-heading-spacing) !important;
		line-height: 1.1 !important;
	}
	h2, h3, h4 {
		font-family: var(--wb-font-heading) !important;
		color: var(--wp--preset--color--title) !important;
		font-weight: var(--wb-heading-weight) !important;
		letter-spacing: var(--wb-heading-spacing) !important;
		line-height: 1.25 !important;
	}
	p {
		color: var(--wp--preset--color--body) !important;
		line-height: 1.62 !important;
	}
	.wp-block-post-content p {
		max-width: 68ch;
	}
	.wp-block-paragraph.has-lede-font-size, .bai-lede {
		color: var(--wp--preset--color--lede) !important;
		line-height: 1.5 !important;
		letter-spacing: -0.01em !important;
	}
	a {
		color: var(--wp--preset--color--secondary) !important;
		transition: color 0.15s ease !important;
	}
	a:hover {
		color: var(--wp--preset--color--accent) !important;
	}
	.bai-header {
		background-color: color-mix(in srgb, var(--wp--preset--color--page) 85%, #ffffff) !important;
		backdrop-filter: blur(14px) !important;
		-webkit-backdrop-filter: blur(14px) !important;
		border-bottom: 1px solid var(--wp--preset--color--border) !important;
	}
	.bai-header .wp-block-site-title a {
		font-family: var(--wb-font-heading) !important;
		color: var(--wp--preset--color--ink) !important;
		font-weight: 850 !important;
		letter-spacing: var(--wb-heading-spacing) !important;
	}
	.bai-header .wp-block-navigation .wp-block-navigation-item__content {
		color: var(--wp--preset--color--muted) !important;
	}
	.bai-header .wp-block-navigation .wp-block-navigation-item__content:hover {
		color: var(--wp--preset--color--accent) !important;
	}
	.bai-side-col, .bai-sidebar {
		background-color: var(--wp--preset--color--sidebar, color-mix(in srgb, var(--wp--preset--color--page) 92%, var(--wp--preset--color--border))) !important;
		border-right: 1px solid var(--wp--preset--color--border) !important;
	}
	.bai-side-head, .bai-side-filter, .bai-side-foot {
		border-color: var(--wp--preset--color--border) !important;
	}
	.bai-side-foot p {
		color: var(--wp--preset--color--muted) !important;
	}
	.bai-filter {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		color: var(--wp--preset--color--ink) !important;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
	}
	.bai-filter:focus {
		border-color: var(--wp--preset--color--accent) !important;
		box-shadow: 0 0 0 3px var(--wp--preset--color--accent-wash) !important;
	}
	.bai-nav-list li > .bai-nav-item {
		border: 1px solid transparent;
		transition: all 0.15s ease;
	}
	.bai-nav-list li > .bai-nav-item:hover {
		background-color: color-mix(in srgb, #ffffff 65%, transparent) !important;
	}
	.bai-nav-list li > .bai-nav-item:hover .wp-block-post-title a {
		color: var(--wp--preset--color--accent) !important;
	}
	.bai-nav-list li.is-active > .bai-nav-item {
		background-color: #ffffff !important;
		border-color: var(--wp--preset--color--border) !important;
		box-shadow: 0 2px 10px color-mix(in srgb, var(--wp--preset--color--ink) 6%, transparent) !important;
		border-left: 4px solid var(--wp--preset--color--accent) !important;
	}
	.bai-nav-list li.is-active .wp-block-post-title a {
		color: var(--wp--preset--color--ink) !important;
		font-weight: 800 !important;
	}
	.bai-nav-item .wp-block-post-title a {
		color: var(--wp--preset--color--title) !important;
		font-weight: 700 !important;
	}
	.bai-nav-item .wp-block-post-excerpt p {
		color: var(--wp--preset--color--muted) !important;
	}
	/* Kicker capsule */
	.bai-kicker {
		background-color: var(--wp--preset--color--secondary-wash, var(--wp--preset--color--accent-wash)) !important;
		border: 1px solid var(--wp--preset--color--secondary-border, var(--wp--preset--color--accent-border)) !important;
		color: var(--wp--preset--color--secondary-text, var(--wp--preset--color--accent-text)) !important;
		box-shadow: 0 1px 4px color-mix(in srgb, var(--wp--preset--color--secondary) 15%, transparent) !important;
	}
	.bai-kicker::before {
		background: var(--wp--preset--color--secondary, var(--wp--preset--color--accent)) !important;
		box-shadow: 0 0 0 2.5px var(--wp--preset--color--secondary-border, var(--wp--preset--color--accent-border)) !important;
	}
	/* Metadata pills */
	.bai-tech-pill, .bai-chip-row .bai-tech-pill {
		background-color: var(--wp--preset--color--secondary-wash, var(--wp--preset--color--accent-wash)) !important;
		color: var(--wp--preset--color--secondary-text, var(--wp--preset--color--accent-text)) !important;
		border: 1px solid var(--wp--preset--color--secondary-border, var(--wp--preset--color--accent-border)) !important;
		font-weight: 700 !important;
		box-shadow: 0 1px 3px color-mix(in srgb, var(--wp--preset--color--secondary) 10%, transparent) !important;
	}
	/* Buttons & Tactile Feedback */
	.wp-block-button:not(.is-style-outline) .wp-block-button__link, .bai-newsletter-btn {
		background-color: var(--wp--preset--color--accent) !important;
		color: var(--wb-btn-text, #ffffff) !important;
		border: 1px solid var(--wp--preset--color--accent) !important;
		font-weight: 800 !important;
		letter-spacing: -0.01em !important;
		border-radius: 8px !important;
		box-shadow: 
			inset 0 1px 0 rgba(255, 255, 255, 0.25),
			0 1px 2px rgba(0, 0, 0, 0.05),
			0 4px 12px color-mix(in srgb, var(--wp--preset--color--accent) 28%, transparent) !important;
		transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
	}
	.wp-block-button:not(.is-style-outline) .wp-block-button__link:hover, .bai-newsletter-btn:hover {
		background-color: var(--wp--preset--color--accent-strong) !important;
		border-color: var(--wp--preset--color--accent-strong) !important;
		color: #ffffff !important;
		transform: translateY(-1px);
		box-shadow: 
			inset 0 1px 0 rgba(255, 255, 255, 0.32),
			0 2px 6px rgba(0, 0, 0, 0.08),
			0 8px 18px color-mix(in srgb, var(--wp--preset--color--accent) 35%, transparent) !important;
	}
	.wp-block-button:not(.is-style-outline) .wp-block-button__link:active, .bai-newsletter-btn:active {
		transform: scale(0.98);
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
	}
	.wp-block-button.is-style-outline .wp-block-button__link {
		background-color: #ffffff !important;
		color: var(--wp--preset--color--ink) !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		border-radius: 8px !important;
		box-shadow: 
			0 1px 2px rgba(0, 0, 0, 0.04),
			inset 0 1px 0 rgba(255, 255, 255, 0.9) !important;
		font-weight: 650 !important;
		transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
	}
	.wp-block-button.is-style-outline .wp-block-button__link:hover {
		border-color: var(--wp--preset--color--accent) !important;
		color: var(--wp--preset--color--accent) !important;
		transform: translateY(-1px);
		box-shadow: 
			0 3px 8px rgba(0, 0, 0, 0.06),
			0 1px 2px rgba(0, 0, 0, 0.04) !important;
	}
	.wp-block-button.is-style-outline .wp-block-button__link:active {
		transform: scale(0.98);
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
	}

	/* Doppelrand Surfaces & Specular Highlights */
	.bai-notes-card, .bai-newsletter-box, .bai-project-footer-card {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		box-shadow: 
			0 1px 2px rgba(0, 0, 0, 0.03),
			0 4px 16px -2px color-mix(in srgb, var(--wp--preset--color--ink) 6%, transparent),
			inset 0 1px 0 rgba(255, 255, 255, 0.95),
			inset 0 0 0 1px rgba(255, 255, 255, 0.5) !important;
		border-radius: 12px !important;
	}
	/* Feature columns in Gutenberg content */
	.wp-block-columns .wp-block-column {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		border-radius: 12px !important;
		box-shadow: 
			0 1px 3px rgba(0, 0, 0, 0.03),
			0 4px 12px -2px color-mix(in srgb, var(--wp--preset--color--ink) 4%, transparent),
			inset 0 1px 0 rgba(255, 255, 255, 0.95) !important;
		transition: border-color 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
	}
	.wp-block-columns .wp-block-column:hover {
		border-color: var(--wp--preset--color--accent) !important;
		transform: translateY(-2px);
		box-shadow: 
			0 4px 12px rgba(0, 0, 0, 0.05),
			0 12px 24px -4px color-mix(in srgb, var(--wp--preset--color--accent) 18%, transparent),
			inset 0 1px 0 rgba(255, 255, 255, 1) !important;
	}
	.wp-block-columns .wp-block-column h3 {
		color: var(--wp--preset--color--ink) !important;
		letter-spacing: -0.015em !important;
	}
	.wp-block-columns .wp-block-column p {
		color: var(--wp--preset--color--body) !important;
		line-height: 1.6 !important;
	}
	/* Code blocks */
	pre.wp-block-code, .wp-block-code, pre {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		border-left: 3.5px solid var(--wp--preset--color--secondary) !important;
		color: var(--wp--preset--color--ink) !important;
		border-radius: 8px !important;
		box-shadow: 
			0 1px 2px rgba(0, 0, 0, 0.03),
			0 3px 10px color-mix(in srgb, var(--wp--preset--color--ink) 4%, transparent),
			inset 0 1px 0 rgba(255, 255, 255, 0.95) !important;
		font-family: var(--wp--preset--font-family--mono) !important;
		font-variant-numeric: tabular-nums !important;
	}
	/* Newsletter badge */
	.bai-newsletter-badge {
		background-color: var(--wp--preset--color--accent-wash) !important;
		color: var(--wp--preset--color--accent-text) !important;
		border: 1px solid var(--wp--preset--color--accent-border) !important;
		font-weight: 750 !important;
		letter-spacing: 0.04em !important;
	}
	/* Sub-nav tabs */
	.bai-project-nav {
		border-bottom: 1.5px solid var(--wp--preset--color--border) !important;
	}
	.bai-project-nav-item {
		color: var(--wp--preset--color--muted) !important;
		font-weight: 600 !important;
		transition: color 0.15s ease, border-color 0.15s ease !important;
	}
	.bai-project-nav-item:hover {
		color: var(--wp--preset--color--accent) !important;
	}
	.bai-project-nav-item.is-active {
		color: var(--wp--preset--color--ink) !important;
		border-bottom: 3px solid var(--wp--preset--color--accent) !important;
		font-weight: 750 !important;
	}
	.bai-project-nav-item.bai-nav-ext {
		color: var(--wp--preset--color--muted) !important;
		opacity: 0.8;
	}
	.bai-project-nav-item.bai-nav-ext:hover {
		color: var(--wp--preset--color--accent) !important;
		opacity: 1;
	}
	</style>';

	return $css;
}

add_action( 'wp_head', function () {
	echo workbench_get_palette_css();
}, 5 );

/**
 * Palette metadata & inspiration registry.
 */
function workbench_get_palette_meta( $slug = '' ) {
	if ( ! $slug ) {
		$slug = ! empty( $_GET['palette'] ) ? sanitize_key( $_GET['palette'] ) : 'neelakurunji';
	}

	$legacy_map = array(
		'coastal-dusk'   => 'cobalt-precision',
		'granite-ridge'  => 'cobalt-precision',
		'deccan-basalt'  => 'cobalt-precision',
		'chettinad-teak' => 'terracotta-sun',
		'laterite-mist'  => 'terracotta-sun',
		'monsoon-canopy' => 'nordic-pine',
		'chaparral-dusk' => 'nordic-pine',
		'rann-mirage'    => 'drafting-blueprint',
		'happy-hues'     => 'studio-electric',
		'kurinji'        => 'neelakurunji',
	);
	if ( isset( $legacy_map[ $slug ] ) ) {
		$slug = $legacy_map[ $slug ];
	}

	$registry = array(
		'neelakurunji'       => array(
			'name'        => 'Neelakurunji',
			'inspired_by' => 'the 12-year Shola bloom at dusk',
			'full_story'  => 'Inspired by Strobilanthes kunthiana blossoming once every 12 years across the Western Ghats under an orange sunset sky.',
			'dot'         => '#664de5',
		),
		'studio-electric'    => array(
			'name'        => 'Studio Electric',
			'inspired_by' => 'Happy Hues #17 & modernist graphic labs',
			'full_story'  => 'Inspired by Happy Hues #17: warm vintage cream, deep modernist navy, electric pink, and luminous teal.',
			'dot'         => '#f582ae',
		),
		'terracotta-sun'     => array(
			'name'        => 'Terracotta Sun',
			'inspired_by' => 'sunlit Chettinad tiles & lime plaster',
			'full_story'  => 'Inspired by the warm baked terracotta of Chettinad courtyards and classic editorial literature.',
			'dot'         => '#c2410c',
		),
		'nordic-pine'        => array(
			'name'        => 'Nordic Pine',
			'inspired_by' => 'mountain Shola canopies & pine mist',
			'full_story'  => 'Inspired by high-altitude Western Ghats forest canopies and alpine evergreen mist.',
			'dot'         => '#0d7e5d',
		),
		'drafting-blueprint' => array(
			'name'        => 'Drafting Blueprint',
			'inspired_by' => 'Rann mirages & architectural drafting paper',
			'full_story'  => 'Inspired by technical grid systems, architectural blueprint paper, and salt mirages.',
			'dot'         => '#ea580c',
		),
		'cobalt-precision'   => array(
			'name'        => 'Cobalt Precision',
			'inspired_by' => 'granite ridges & precision instruments',
			'full_story'  => 'Inspired by cool granite ridges and precision instrument engineering.',
			'dot'         => '#2563eb',
		),
	);

	return isset( $registry[ $slug ] ) ? $registry[ $slug ] : $registry['neelakurunji'];
}

/**
 * [workbench_palette_colophon] shortcode: outputs the palette attribution line.
 */
add_shortcode( 'workbench_palette_colophon', function () {
	$meta = workbench_get_palette_meta();

	return sprintf(
		'<span class="bai-palette-colophon"><span class="bai-colophon-title"><span class="bai-colophon-dot" style="background-color:%s"></span><span class="bai-colophon-name">%s</span></span><span class="bai-colophon-note">Inspired by %s</span></span>',
		esc_attr( $meta['dot'] ),
		esc_html( $meta['name'] ),
		esc_html( $meta['inspired_by'] )
	);
} );

/**
 * Output theme-palette meta tag in document head.
 */
add_action( 'wp_head', function () {
	$meta = workbench_get_palette_meta();
	printf(
		'<meta name="theme-palette" content="%s — %s">' . "\n",
		esc_attr( $meta['name'] ),
		esc_attr( $meta['full_story'] )
	);
}, 2 );

