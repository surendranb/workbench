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
 * [workbench_search_box] — sidebar filter input; placeholder carries the site name.
 */
add_shortcode( 'workbench_search_box', function () {
	$name = get_bloginfo( 'name' );
	return sprintf(
		'<input type="search" class="bai-filter" placeholder="Search %1$s" aria-label="Search %1$s" data-bai-filter />',
		esc_attr( $name )
	);
} );

/**
 * [workbench_copyright] — "© {year} {name}"; name = workbench_copyright_name
 * option when set (fleet default: studio brand), else the site name.
 */
add_shortcode( 'workbench_copyright', function () {
	$name = get_option( 'workbench_copyright_name' );
	if ( ! $name ) {
		$name = get_bloginfo( 'name' );
	}
	return sprintf(
		'© %s %s',
		gmdate( 'Y' ),
		esc_html( $name )
	);
} );

/**
 * Fallback favicon when the site has no Site Icon set.
 */
add_action( 'wp_head', function () {
	if ( has_site_icon() ) {
		return;
	}
	$src = get_theme_file_uri( 'assets/favicon.svg' );
	echo '<link rel="icon" href="' . esc_url( $src ) . '" type="image/svg+xml" />' . "\n";
} );

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

/**
 * Standard project sub-navigation bar:
 * Overview | Setup | Docs | [Other Pages] | Website ↗ | GitHub ↗ | PyPI ↗ | npm ↗ | WordPress.org ↗
 */
add_shortcode( 'workbench_project_nav', function () {
	if ( ! is_page() || is_front_page() ) {
		return '';
	}

	$current_id = get_queried_object_id();
	$post = get_post( $current_id );
	if ( ! $post ) {
		return '';
	}

	$is_child = ( $post->post_parent > 0 );
	$parent_id = $is_child ? $post->post_parent : $post->ID;
	$parent_post = $is_child ? get_post( $parent_id ) : $post;
	$parent_url = get_permalink( $parent_id );
	$parent_title = get_the_title( $parent_id );

	// Fetch child pages for this project
	$children = get_posts( array(
		'post_type'      => 'page',
		'post_parent'    => $parent_id,
		'post_status'    => 'publish',
		'posts_per_page' => 20,
		'orderby'        => 'menu_order post_title',
		'order'          => 'ASC',
	) );

	$setup_page = null;
	$docs_page = null;
	$other_pages = array();

	foreach ( $children as $child ) {
		if ( in_array( $child->post_name, array( 'setup', 'how-to', 'install', 'quickstart' ), true ) ) {
			$setup_page = $child;
		} elseif ( in_array( $child->post_name, array( 'docs', 'documentation', 'api' ), true ) ) {
			$docs_page = $child;
		} else {
			$other_pages[] = $child;
		}
	}

	$out = '';
	$out .= '<nav class="bai-project-nav" aria-label="' . esc_attr__( 'Project Navigation', 'workbench' ) . '">';
	$out .= '<div class="bai-project-nav-tabs">';

	// 1. Overview tab
	$is_overview_active = ( ! $is_child );
	$out .= sprintf(
		'<a href="%s" class="bai-project-nav-item%s"%s>%s</a>',
		esc_url( $parent_url ),
		$is_overview_active ? ' is-active' : '',
		$is_overview_active ? ' aria-current="page"' : '',
		esc_html__( 'Overview', 'workbench' )
	);

	// 2. Setup tab
	if ( $setup_page ) {
		$is_setup_active = ( $current_id === $setup_page->ID );
		$out .= sprintf(
			'<a href="%s" class="bai-project-nav-item%s"%s>%s</a>',
			esc_url( get_permalink( $setup_page->ID ) ),
			$is_setup_active ? ' is-active' : '',
			$is_setup_active ? ' aria-current="page"' : '',
			esc_html__( 'Setup', 'workbench' )
		);
	}

	// 3. Docs tab
	if ( $docs_page ) {
		$is_docs_active = ( $current_id === $docs_page->ID );
		$out .= sprintf(
			'<a href="%s" class="bai-project-nav-item%s"%s>%s</a>',
			esc_url( get_permalink( $docs_page->ID ) ),
			$is_docs_active ? ' is-active' : '',
			$is_docs_active ? ' aria-current="page"' : '',
			esc_html__( 'Docs', 'workbench' )
		);
	}

	// 4. Any additional custom child pages
	foreach ( $other_pages as $op ) {
		$is_op_active = ( $current_id === $op->ID );
		$out .= sprintf(
			'<a href="%s" class="bai-project-nav-item%s"%s>%s</a>',
			esc_url( get_permalink( $op->ID ) ),
			$is_op_active ? ' is-active' : '',
			$is_op_active ? ' aria-current="page"' : '',
			esc_html( get_the_title( $op->ID ) )
		);
	}

	// 5. External destinations (custom meta or auto-detected content links)
	$search_content = $parent_post->post_content . ' ' . $post->post_content;

	$external_destinations = array(
		'website_url' => array(
			'label'   => __( 'Website ↗', 'workbench' ),
			'pattern' => '/href="([^"]+)"[^>]*>(?:Visit|Website|Live Portal|Live Demo)[^<]*/i',
		),
		'github_url'  => array(
			'label'   => __( 'GitHub ↗', 'workbench' ),
			'pattern' => '/href="(https:\/\/github\.com\/[^"]+)"/i',
		),
		'pypi_url'    => array(
			'label'   => __( 'PyPI ↗', 'workbench' ),
			'pattern' => '/href="(https:\/\/pypi\.org\/project\/[^"]+)"/i',
		),
		'npm_url'     => array(
			'label'   => __( 'npm ↗', 'workbench' ),
			'pattern' => '/href="(https:\/\/(?:www\.)?npmjs\.com\/package\/[^"]+)"/i',
		),
		'wporg_url'   => array(
			'label'   => __( 'WordPress.org ↗', 'workbench' ),
			'pattern' => '/href="(https:\/\/wordpress\.org\/plugins\/[^"]+)"/i',
		),
	);

	foreach ( $external_destinations as $meta_key => $conf ) {
		$url = get_post_meta( $parent_id, $meta_key, true );
		if ( ! $url && $conf['pattern'] && preg_match( $conf['pattern'], $search_content, $m ) ) {
			$url = $m[1];
		}
		// Never treat legacy *.builditwithai.xyz sub-sites as external websites:
		// the entire purpose of this architecture is retiring those sub-sites into these pages.
		if ( 'website_url' === $meta_key && $url && false !== strpos( $url, 'builditwithai.xyz' ) ) {
			$url = '';
		}
		if ( $url ) {
			$out .= sprintf(
				'<a href="%s" class="bai-project-nav-item bai-nav-ext" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( $url ),
				esc_html( $conf['label'] )
			);
		}
	}

	$out .= '</div>';
	$out .= '</nav>';

	return $out;
} );

// True when the current page is assigned the given block template.
function is_template_assigned( string $slug ) : bool {
	return get_page_template_slug( get_queried_object_id() ) === $slug;
}

// Modular components
if ( file_exists( __DIR__ . '/inc/newsletter.php' ) ) {
	require_once __DIR__ . '/inc/newsletter.php';
}
if ( file_exists( __DIR__ . '/inc/homepage.php' ) ) {
	require_once __DIR__ . '/inc/homepage.php';
}
