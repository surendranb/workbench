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
			: ( ! empty( $_COOKIE['wb_active_palette'] ) ? sanitize_key( $_COOKIE['wb_active_palette'] ) : 'coastal-dusk' );
	}

	$file = get_stylesheet_directory() . "/styles/{$palette_slug}.json";
	if ( ! file_exists( $file ) ) {
		$file = get_stylesheet_directory() . "/styles/coastal-dusk.json";
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
	$css .= "}\n";
	$css .= '
	/* Structural Contrast & Atmosphere Engine */
	body, .wp-site-blocks { 
		background-color: var(--wp--preset--color--page) !important; 
		color: var(--wp--preset--color--body) !important; 
	}
	h1, .wp-block-post-title {
		color: var(--wp--preset--color--ink) !important;
		font-weight: 850 !important;
		letter-spacing: -0.03em !important;
	}
	h2, h3, h4 {
		color: var(--wp--preset--color--title) !important;
		font-weight: 800 !important;
		letter-spacing: -0.02em !important;
	}
	p {
		color: var(--wp--preset--color--body) !important;
	}
	.wp-block-paragraph.has-lede-font-size, .bai-lede {
		color: var(--wp--preset--color--lede) !important;
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
		color: var(--wp--preset--color--ink) !important;
		font-weight: 900 !important;
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
		background-color: var(--wp--preset--color--accent-wash) !important;
		border: 1px solid var(--wp--preset--color--accent-border) !important;
		color: var(--wp--preset--color--accent-text) !important;
		box-shadow: 0 1px 4px color-mix(in srgb, var(--wp--preset--color--accent) 15%, transparent) !important;
	}
	.bai-kicker::before {
		background: var(--wp--preset--color--accent) !important;
		box-shadow: 0 0 0 2.5px var(--wp--preset--color--accent-border) !important;
	}
	/* Metadata pills */
	.bai-tech-pill, .bai-chip-row .bai-tech-pill {
		background-color: var(--wp--preset--color--secondary-wash, var(--wp--preset--color--accent-wash)) !important;
		color: var(--wp--preset--color--secondary-text, var(--wp--preset--color--accent-text)) !important;
		border: 1px solid var(--wp--preset--color--secondary-border, var(--wp--preset--color--accent-border)) !important;
		font-weight: 700 !important;
		box-shadow: 0 1px 3px color-mix(in srgb, var(--wp--preset--color--secondary) 10%, transparent) !important;
	}
	/* Buttons */
	.wp-block-button:not(.is-style-outline) .wp-block-button__link, .bai-newsletter-btn {
		background-color: var(--wp--preset--color--accent) !important;
		color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--accent) !important;
		font-weight: 750 !important;
		box-shadow: 0 2px 10px color-mix(in srgb, var(--wp--preset--color--accent) 30%, transparent) !important;
		transition: all 0.15s ease !important;
	}
	.wp-block-button:not(.is-style-outline) .wp-block-button__link:hover, .bai-newsletter-btn:hover {
		background-color: var(--wp--preset--color--accent-strong) !important;
		border-color: var(--wp--preset--color--accent-strong) !important;
		color: #ffffff !important;
		transform: translateY(-1px);
		box-shadow: 0 4px 14px color-mix(in srgb, var(--wp--preset--color--accent) 40%, transparent) !important;
	}
	.wp-block-button.is-style-outline .wp-block-button__link {
		background-color: #ffffff !important;
		color: var(--wp--preset--color--ink) !important;
		border: 1.5px solid var(--wp--preset--color--border) !important;
		box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
		font-weight: 700 !important;
		transition: all 0.15s ease !important;
	}
	.wp-block-button.is-style-outline .wp-block-button__link:hover {
		border-color: var(--wp--preset--color--accent) !important;
		color: var(--wp--preset--color--accent) !important;
		transform: translateY(-1px);
		box-shadow: 0 3px 8px rgba(0,0,0,0.06) !important;
	}
	/* Cards & Surfaces */
	.bai-notes-card, .bai-newsletter-box, .bai-project-footer-card {
		background-color: #ffffff !important;
		border: 1.5px solid var(--wp--preset--color--border) !important;
		box-shadow: 0 4px 20px color-mix(in srgb, var(--wp--preset--color--ink) 6%, transparent) !important;
		border-radius: 12px !important;
	}
	/* Feature columns in Gutenberg content */
	.wp-block-columns .wp-block-column {
		background-color: #ffffff !important;
		border: 1.5px solid var(--wp--preset--color--border) !important;
		border-radius: 10px !important;
		box-shadow: 0 2px 10px color-mix(in srgb, var(--wp--preset--color--ink) 4%, transparent) !important;
		transition: border-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease !important;
	}
	.wp-block-columns .wp-block-column:hover {
		border-color: var(--wp--preset--color--accent) !important;
		transform: translateY(-2px);
		box-shadow: 0 6px 18px color-mix(in srgb, var(--wp--preset--color--accent) 15%, transparent) !important;
	}
	.wp-block-columns .wp-block-column h3 {
		color: var(--wp--preset--color--ink) !important;
	}
	.wp-block-columns .wp-block-column p {
		color: var(--wp--preset--color--body) !important;
	}
	/* Code blocks */
	pre.wp-block-code, .wp-block-code, pre {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		border-left: 4px solid var(--wp--preset--color--secondary) !important;
		color: var(--wp--preset--color--ink) !important;
		border-radius: 8px !important;
		box-shadow: 0 2px 8px color-mix(in srgb, var(--wp--preset--color--ink) 4%, transparent) !important;
	}
	/* Newsletter badge */
	.bai-newsletter-badge {
		background-color: var(--wp--preset--color--accent-wash) !important;
		color: var(--wp--preset--color--accent-text) !important;
		border: 1px solid var(--wp--preset--color--accent-border) !important;
		font-weight: 800 !important;
	}
	/* Sub-nav tabs */
	.bai-project-nav {
		border-bottom: 1.5px solid var(--wp--preset--color--border) !important;
	}
	.bai-project-nav-item {
		color: var(--wp--preset--color--muted) !important;
		font-weight: 600 !important;
	}
	.bai-project-nav-item:hover {
		color: var(--wp--preset--color--accent) !important;
	}
	.bai-project-nav-item.is-active {
		color: var(--wp--preset--color--ink) !important;
		border-bottom: 3px solid var(--wp--preset--color--accent) !important;
		font-weight: 800 !important;
	}
	</style>';

	return $css;
}

add_action( 'wp_head', function () {
	echo workbench_get_palette_css();
}, 5 );

/**
 * Floating palette quick-switcher on localhost.
 */
add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}

	$current = ! empty( $_GET['palette'] ) 
		? sanitize_key( $_GET['palette'] ) 
		: ( ! empty( $_COOKIE['wb_active_palette'] ) ? sanitize_key( $_COOKIE['wb_active_palette'] ) : 'coastal-dusk' );

	$palettes = array(
		'coastal-dusk'   => 'Coastal Dusk',
		'laterite-mist'  => 'Laterite Mist',
		'chaparral-dusk' => 'Chaparral Dusk',
		'granite-ridge'  => 'Granite Ridge',
		'happy-hues'     => 'Happy Hues #17',
	);
	?>
	<div class="wb-palette-switcher" style="position:fixed;bottom:16px;right:16px;z-index:9999;display:flex;align-items:center;gap:6px;background:rgba(12,27,38,0.92);backdrop-filter:blur(10px);padding:6px 12px;border-radius:30px;font-family:system-ui,-apple-system,sans-serif;font-size:11px;font-weight:600;color:#ffffff;box-shadow:0 4px 16px rgba(0,0,0,0.22);border:1px solid rgba(255,255,255,0.15);">
		<span style="opacity:0.65;letter-spacing:0.04em;text-transform:uppercase;font-size:9px;padding-right:2px">Essence:</span>
		<?php foreach ( $palettes as $slug => $label ) : 
			$is_active = ( $current === $slug );
			$url = add_query_arg( 'palette', $slug );
		?>
			<a href="<?php echo esc_url( $url ); ?>" style="text-decoration:none;padding:4px 9px;border-radius:12px;transition:all 0.15s ease;<?php echo $is_active ? 'background:#ffffff;color:#0c1b26;font-weight:750;box-shadow:0 1px 3px rgba(0,0,0,0.15);' : 'color:#ffffff;opacity:0.85;'; ?>">
				<?php echo esc_html( $label ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
} );
