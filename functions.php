<?php
/**
 * Functions: Workbench theme.
 *
 * Layout lives in templates/parts, design tokens in theme.json.
 * This file wires: stylesheet loading, sidebar page count, active-page
 * highlighting, and the client-side nav filter.
 */

// Enqueue theme stylesheet + nav filter (self-busting versions so updates always land).
add_action( 'wp_enqueue_scripts', function () {
	$style_ver = (string) filemtime( get_stylesheet_directory() . '/style.css' );
	wp_enqueue_style( 'workbench-style', get_stylesheet_uri(), array(), $style_ver );
	wp_enqueue_script(
		'workbench-nav-filter',
		get_theme_file_uri( 'assets/js/nav-filter.js' ),
		array(),
		(string) filemtime( get_stylesheet_directory() . '/assets/js/nav-filter.js' ),
		true
	);
} );

// Shell geometry must never race asset caching — inline it into every HTML response.
add_action( 'wp_head', function () {
	?>
	<style id="workbench-shell">
	:root { --wb-header-h: 64px; }
	.bai-header {
		box-sizing: border-box;
		height: var(--wb-header-h);
		display: flex;
		align-items: center;
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		z-index: 20;
	}
	.wp-site-blocks { padding-top: var(--wb-header-h); }
	body.admin-bar .bai-header { top: 46px; }
	@media (min-width: 601px) {
		body.admin-bar .bai-header { top: 32px; }
	}

	.bai-body { box-sizing: border-box; display: block; }
	.bai-main-col { min-width: 0; margin-left: 360px; }
	.bai-side-col {
		position: fixed;
		top: var(--wb-header-h);
		left: 0;
		width: 360px;
		height: calc(100vh - var(--wb-header-h));
		max-height: calc(100vh - var(--wb-header-h));
		box-sizing: border-box;
		background: var(--wp--preset--color--surface);
		border-right: 1px solid var(--wp--preset--color--border);
	}
	/* Template-part wrapper between column and rail needs the height for % chains */
	.bai-side-col > * {
		height: 100%;
	}
	body.admin-bar .bai-side-col {
		top: calc(var(--wb-header-h) + 46px);
		height: calc(100vh - var(--wb-header-h) - 46px);
		max-height: calc(100vh - var(--wb-header-h) - 46px);
	}
	@media (min-width: 601px) {
		body.admin-bar .bai-side-col {
			top: calc(var(--wb-header-h) + 32px);
			height: calc(100vh - var(--wb-header-h) - 32px);
			max-height: calc(100vh - var(--wb-header-h) - 32px);
		}
	}

	@media (max-width: 960px) {
		.wp-site-blocks { padding-top: var(--wb-header-h); }
		.bai-header { position: sticky; }
		.bai-body { display: flex; flex-direction: column; }
		.bai-main-col { order: 1; margin-left: 0; }
		.bai-side-col {
			order: 2;
			position: static;
			width: 100%;
			height: auto;
			max-height: none;
			border-right: none;
			border-top: 1px solid var(--wp--preset--color--border);
		}
		.bai-sidebar { height: auto; overflow: visible; }
		.bai-side-nav { max-height: 320px; }
		.bai-content {
			min-height: 0;
			padding-left: 24px !important;
			padding-right: 24px !important;
		}
		.bai-detail-grid { display: block; }
		.bai-notes-card { position: static !important; margin-top: 40px; }
	}
	</style>
	<?php
} );

// Same stylesheet for the block editor so the canvas matches the front end.
add_action( 'enqueue_block_editor_assets', function () {
	wp_enqueue_style( 'workbench-editor', get_stylesheet_uri(), array(), '1.0.0' );
} );

// Disable the Font Library UI — fonts are fixed by the theme.
add_filter( 'block_editor_settings_all', function ( $settings ) {
	$settings['fontLibraryEnabled'] = false;
	return $settings;
} );

// Pattern category used by this theme's patterns.
add_action( 'init', function () {
	register_block_pattern_category( 'workbench', array( 'label' => __( 'Workbench', 'workbench' ) ) );
} );

/**
 * Filter paragraph blocks with .bai-side-copyright to inject site copyright.
 */
add_filter( 'render_block_core/paragraph', function ( $block_content, $block ) {
	if ( ! empty( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'bai-side-copyright' ) ) {
		$name = get_bloginfo( 'name' );
		return sprintf(
			'<p class="bai-side-copyright wp-block-paragraph" style="font-size:11px;font-weight:500;color:var(--wp--preset--color--faint)">&copy; %s %s</p>',
			esc_html( gmdate( 'Y' ) ),
			esc_html( $name )
		);
	}
	return $block_content;
}, 10, 2 );

/**
 * Ensure the sidebar project directory query only shows top-level pages.
 * Prevents child documentation/setup pages from cluttering the studio list.
 */
add_filter( 'query_loop_block_query_vars', function ( $query, $block ) {
	if ( isset( $query['post_type'] ) && 'page' === $query['post_type'] ) {
		$query['post_parent'] = 0;
	}
	return $query;
}, 10, 2 );

/**
 * Highlight the current page inside the sidebar query loop.
 * Adds .is-active (+ aria-current) to the item whose post ID matches
 * the page being viewed, or its parent project if viewing a child page.
 */
add_filter( 'render_block_core/post-template', function ( $block_content, $parsed_block ) {
	if ( empty( $parsed_block['attrs']['className'] ) || strpos( $parsed_block['attrs']['className'], 'bai-nav-list' ) === false ) {
		return $block_content;
	}
	if ( ! is_singular( 'page' ) ) {
		return $block_content;
	}

	$current_id = get_queried_object_id();
	if ( ! $current_id ) {
		return $block_content;
	}
	$post = get_post( $current_id );
	$highlight_id = ( $post && $post->post_parent ) ? $post->post_parent : $current_id;

	$p = new WP_HTML_Tag_Processor( $block_content );
	while ( $p->next_tag( array( 'tag_name' => 'li', 'class_name' => 'wp-block-post' ) ) ) {
		$classes = (string) $p->get_attribute( 'class' );
		if ( false !== strpos( $classes, "post-{$highlight_id} " ) || $classes === "post-{$highlight_id}" ) {
			$p->add_class( 'is-active' );
			// ponytail: first anchor after this li is its own title link — one item, one link.
			if ( $p->next_tag( array( 'tag_name' => 'a' ) ) ) {
				$p->set_attribute( 'aria-current', 'page' );
			}
		}
	}
	return $p->get_updated_html();
}, 10, 2 );

add_filter( 'the_content', function ( $content ) {
	if ( is_singular( 'page' ) ) {
		// Strip leading redundant h1 block when template already outputs canonical title
		$content = preg_replace( '/^\s*<!-- wp:heading {"level":1} -->\s*<h1[^>]*>.*?<\/h1>\s*<!-- \/wp:heading -->/si', '', $content );
	}
	return $content;
}, 8 );



// True when the current page is assigned the given block template.
function workbench_is_template_assigned( string $slug ) : bool {
	return get_page_template_slug( get_queried_object_id() ) === $slug;
}

// Modular components
if ( file_exists( __DIR__ . '/inc/palettes.php' ) ) {
	require_once __DIR__ . '/inc/palettes.php';
}
