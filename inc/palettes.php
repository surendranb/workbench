<?php
/**
 * Palette switcher: allows instant live preview via ?palette=<slug> with cookie persistence,
 * and renders a discreet floating switcher in local development.
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
		: ( ! empty( $_COOKIE['wb_active_palette'] ) ? sanitize_key( $_COOKIE['wb_active_palette'] ) : '' );

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
	echo "}\nbody, .wp-site-blocks { background-color: var(--wp--preset--color--page) !important; color: var(--wp--preset--color--body) !important; }\n";
	echo ".bai-sidebar, .has-surface-background-color { background-color: var(--wp--preset--color--surface) !important; }\n";
	echo "</style>\n";
}, 5 );

/**
 * Floating palette quick-switcher on localhost / logged-in users.
 */
add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}

	$current = ! empty( $_GET['palette'] ) 
		? sanitize_key( $_GET['palette'] ) 
		: ( ! empty( $_COOKIE['wb_active_palette'] ) ? sanitize_key( $_COOKIE['wb_active_palette'] ) : 'default' );

	$palettes = array(
		'laterite-mist'  => 'Laterite Mist',
		'chaparral-dusk' => 'Chaparral Dusk',
		'granite-ridge'  => 'Granite Ridge',
		'happy-hues'     => 'Happy Hues #17',
	);
	?>
	<div class="wb-palette-switcher" style="position:fixed;bottom:16px;right:16px;z-index:9999;display:flex;align-items:center;gap:6px;background:rgba(0,24,88,0.88);backdrop-filter:blur(8px);padding:6px 10px;border-radius:30px;font-family:system-ui,-apple-system,sans-serif;font-size:11px;font-weight:600;color:#ffffff;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
		<span style="opacity:0.75;letter-spacing:0.04em;text-transform:uppercase;font-size:9px;padding-right:2px">Palette:</span>
		<?php foreach ( $palettes as $slug => $label ) : 
			$is_active = ( $current === $slug );
			$url = add_query_arg( 'palette', $slug );
		?>
			<a href="<?php echo esc_url( $url ); ?>" style="text-decoration:none;padding:3px 8px;border-radius:12px;transition:all 0.15s ease;<?php echo $is_active ? 'background:#ffffff;color:#001858;font-weight:700;' : 'color:#ffffff;opacity:0.85;'; ?>">
				<?php echo esc_html( $label ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
} );
