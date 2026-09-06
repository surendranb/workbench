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

add_action( 'wp_head', function () {
	$palette_slug = ! empty( $_GET['palette'] ) 
		? sanitize_key( $_GET['palette'] ) 
		: ( ! empty( $_COOKIE['wb_active_palette'] ) ? sanitize_key( $_COOKIE['wb_active_palette'] ) : 'coastal-dawn' );

	if ( ! $palette_slug ) {
		return;
	}

	$file = get_stylesheet_directory() . "/styles/{$palette_slug}.json";
	if ( ! file_exists( $file ) ) {
		return;
	}

	$data = json_decode( file_get_contents( $file ), true );
	if ( empty( $data['settings']['color']['palette'] ) ) {
		return;
	}

	echo '<style id="wb-palette-override">' . "\n:root {\n";
	foreach ( $data['settings']['color']['palette'] as $col ) {
		$slug = esc_attr( $col['slug'] );
		$val  = esc_attr( $col['color'] );
		echo "  --wp--preset--color--{$slug}: {$val} !important;\n";
	}
	echo "}\n";
	?>
	/* Structural Contrast & Atmosphere Engine */
	body, .wp-site-blocks { 
		background-color: var(--wp--preset--color--page) !important; 
		color: var(--wp--preset--color--body) !important; 
	}
	h1, h2, h3, h4, .wp-block-post-title {
		color: var(--wp--preset--color--ink) !important;
	}
	.bai-header {
		background-color: color-mix(in srgb, var(--wp--preset--color--page) 88%, #ffffff) !important;
		backdrop-filter: blur(14px) !important;
		-webkit-backdrop-filter: blur(14px) !important;
		border-bottom: 1px solid var(--wp--preset--color--border) !important;
	}
	.bai-side-col, .bai-sidebar {
		background-color: color-mix(in srgb, var(--wp--preset--color--page) 94%, var(--wp--preset--color--border)) !important;
		border-right: 1px solid var(--wp--preset--color--border) !important;
	}
	.bai-side-head, .bai-side-filter, .bai-side-foot {
		border-color: var(--wp--preset--color--border) !important;
	}
	.bai-filter {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		color: var(--wp--preset--color--ink) !important;
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
		background-color: color-mix(in srgb, #ffffff 60%, transparent) !important;
	}
	.bai-nav-list li.is-active > .bai-nav-item {
		background-color: #ffffff !important;
		border-color: var(--wp--preset--color--border) !important;
		box-shadow: 0 2px 6px rgba(0,0,0,0.06) !important;
		border-left: 3px solid var(--wp--preset--color--accent) !important;
	}
	.bai-nav-list li.is-active .wp-block-post-title a {
		color: var(--wp--preset--color--ink) !important;
		font-weight: 750 !important;
	}
	.wp-block-button__link, .bai-newsletter-btn {
		background-color: var(--wp--preset--color--accent) !important;
		color: #ffffff !important;
		border: none !important;
		font-weight: 700 !important;
		box-shadow: 0 2px 6px rgba(0,0,0,0.12) !important;
		transition: all 0.15s ease !important;
	}
	.wp-block-button__link:hover, .bai-newsletter-btn:hover {
		background-color: var(--wp--preset--color--accent-strong) !important;
		color: #ffffff !important;
		transform: translateY(-1px);
	}
	.wp-block-button.is-style-outline .wp-block-button__link {
		background-color: #ffffff !important;
		color: var(--wp--preset--color--ink) !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
	}
	.wp-block-button.is-style-outline .wp-block-button__link:hover {
		border-color: var(--wp--preset--color--ink) !important;
	}
	.bai-notes-card, .bai-newsletter-box, .bai-project-footer-card {
		background-color: #ffffff !important;
		border: 1px solid var(--wp--preset--color--border) !important;
		box-shadow: 0 4px 12px rgba(0,0,0,0.04) !important;
	}
	.bai-newsletter-badge {
		background-color: var(--wp--preset--color--accent-wash) !important;
		color: var(--wp--preset--color--accent-text) !important;
		border: 1px solid var(--wp--preset--color--accent-border) !important;
	}
	.bai-project-nav {
		border-bottom: 1px solid var(--wp--preset--color--border) !important;
	}
	.bai-project-nav-item.is-active {
		color: var(--wp--preset--color--ink) !important;
		border-bottom-color: var(--wp--preset--color--accent) !important;
	}
	</style>
	<?php
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
		: ( ! empty( $_COOKIE['wb_active_palette'] ) ? sanitize_key( $_COOKIE['wb_active_palette'] ) : 'coastal-dawn' );

	$palettes = array(
		'coastal-dawn'   => 'Coastal Dawn',
		'laterite-mist'  => 'Laterite Mist',
		'chaparral-dusk' => 'Chaparral Dusk',
		'granite-ridge'  => 'Granite Ridge',
		'happy-hues'     => 'Happy Hues #17',
	);
	?>
	<div class="wb-palette-switcher" style="position:fixed;bottom:16px;right:16px;z-index:9999;display:flex;align-items:center;gap:6px;background:rgba(15,34,45,0.92);backdrop-filter:blur(10px);padding:6px 12px;border-radius:30px;font-family:system-ui,-apple-system,sans-serif;font-size:11px;font-weight:600;color:#ffffff;box-shadow:0 4px 16px rgba(0,0,0,0.22);border:1px solid rgba(255,255,255,0.15);">
		<span style="opacity:0.65;letter-spacing:0.04em;text-transform:uppercase;font-size:9px;padding-right:2px">Essence:</span>
		<?php foreach ( $palettes as $slug => $label ) : 
			$is_active = ( $current === $slug );
			$url = add_query_arg( 'palette', $slug );
		?>
			<a href="<?php echo esc_url( $url ); ?>" style="text-decoration:none;padding:4px 9px;border-radius:12px;transition:all 0.15s ease;<?php echo $is_active ? 'background:#ffffff;color:#0f222d;font-weight:750;box-shadow:0 1px 3px rgba(0,0,0,0.15);' : 'color:#ffffff;opacity:0.85;'; ?>">
				<?php echo esc_html( $label ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
} );
