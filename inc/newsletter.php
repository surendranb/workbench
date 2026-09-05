<?php
/**
 * Newsletter integration: Substack subscription forms, top nav modal dialog,
 * and project page footer CTA.
 *
 * Palette: Happy Hues #17 (cream #fef6e4, navy #001858, pink #f582ae, teal #8bd3dd, surface #ffffff, border #e2e8f0).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue newsletter styles and modal script.
 */
add_action( 'wp_enqueue_scripts', function () {
	$css_path = get_theme_file_path( 'assets/css/newsletter.css' );
	$js_path  = get_theme_file_path( 'assets/js/newsletter.js' );

	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'workbench-newsletter',
			get_theme_file_uri( 'assets/css/newsletter.css' ),
			array( 'workbench-style' ),
			(string) filemtime( $css_path )
		);
	}

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'workbench-newsletter',
			get_theme_file_uri( 'assets/js/newsletter.js' ),
			array(),
			(string) filemtime( $js_path ),
			true
		);
	}
} );

/**
 * Helper to render the Substack free subscription form.
 */
function workbench_render_newsletter_form( $placeholder = 'Enter your email', $button_text = 'Subscribe', $extra_class = '' ) {
	$class = 'bai-newsletter-form' . ( $extra_class ? ' ' . esc_attr( $extra_class ) : '' );
	return sprintf(
		'<form action="https://builditwithai.substack.com/api/v1/free?nojs=true" method="post" target="_blank" class="%1$s"><input type="email" name="email" placeholder="%2$s" required class="bai-newsletter-input" /><input type="hidden" name="first_url" value="https://builditwithai.substack.com" /><button type="submit" class="bai-newsletter-btn">%3$s</button></form>',
		esc_attr( $class ),
		esc_attr( $placeholder ),
		esc_html( $button_text )
	);
}

/**
 * [workbench_newsletter_box] — Embeddable newsletter subscription card.
 */
add_shortcode( 'workbench_newsletter_box', function ( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'       => 'The BuildItWithAI Dispatch',
			'description' => 'Engineering logs, architecture breakdowns, and post-mortems on autonomous agents, MCP servers, and local systems. Built in public, zero hype.',
			'class'       => '',
			'badge'       => 'NEWSLETTER',
			'placeholder' => 'Enter your email',
			'button_text' => 'Subscribe',
		),
		$atts,
		'workbench_newsletter_box'
	);

	$class = 'bai-newsletter-box' . ( $atts['class'] ? ' ' . esc_attr( $atts['class'] ) : '' );
	$out = '<div class="' . esc_attr( $class ) . '">';
	if ( ! empty( $atts['badge'] ) ) {
		$out .= '<span class="bai-newsletter-badge">' . esc_html( $atts['badge'] ) . '</span>';
	}
	if ( ! empty( $atts['title'] ) ) {
		$out .= '<h3 class="bai-newsletter-title">' . esc_html( $atts['title'] ) . '</h3>';
	}
	if ( ! empty( $atts['description'] ) ) {
		$out .= '<p class="bai-newsletter-desc">' . esc_html( $atts['description'] ) . '</p>';
	}
	$out .= workbench_render_newsletter_form( $atts['placeholder'], $atts['button_text'] );
	$out .= '<p class="bai-newsletter-microcopy">' . esc_html__( 'No spam. Read by builders shipping AI systems. Unsubscribe anytime.', 'workbench' ) . '</p></div>';

	return $out;
} );

/**
 * [workbench_project_footer] — Newsletter CTA card at the bottom of project pages.
 */
add_shortcode( 'workbench_project_footer', function () {
	if ( ! is_page() ) {
		return '';
	}

	$post = get_post();
	if ( ! $post ) {
		return '';
	}

	// Only render on top-level project pages
	if ( ! empty( $post->post_parent ) || is_front_page() ) {
		return '';
	}

	$form = workbench_render_newsletter_form( 'Enter your email', 'Subscribe', 'bai-newsletter-form--inline' );

	$out = '<footer class="bai-project-footer-cta" aria-label="' . esc_attr__( 'Newsletter Subscription', 'workbench' ) . '"><div class="bai-project-footer-card"><div class="bai-project-footer-header"><p class="bai-project-footer-kicker">' . esc_html__( 'STAY IN THE LOOP', 'workbench' ) . '</p><h3 class="bai-project-footer-heading">' . esc_html__( 'Build with AI, in public.', 'workbench' ) . '</h3><p class="bai-project-footer-text">' . esc_html__( 'New tools, agent experiments, and architecture notes ship weekly. Get the dispatch straight to your inbox.', 'workbench' ) . '</p></div><div class="bai-project-footer-form-wrap">' . $form . '</div><p class="bai-newsletter-microcopy">' . esc_html__( 'No spam. Read by builders shipping AI systems. Unsubscribe anytime.', 'workbench' ) . '</p></div></footer>';

	return $out;
} );

/**
 * Render newsletter popup modal dialog in wp_footer.
 */
add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}
	?>
	<dialog id="bai-newsletter-dialog" class="bai-newsletter-dialog" aria-labelledby="bai-dialog-title">
		<div class="bai-dialog-content">
			<button type="button" class="bai-dialog-close" aria-label="<?php esc_attr_e( 'Close modal', 'workbench' ); ?>">&times;</button>
			<span class="bai-newsletter-badge"><?php esc_html_e( 'NEWSLETTER', 'workbench' ); ?></span>
			<h2 id="bai-dialog-title" class="bai-newsletter-title"><?php esc_html_e( 'The BuildItWithAI Dispatch', 'workbench' ); ?></h2>
			<p class="bai-newsletter-desc"><?php esc_html_e( 'Deep-dive systems essays and real build logs on autonomous agents, MCP servers, and desktop biomes.', 'workbench' ); ?></p>
			<?php echo workbench_render_newsletter_form(); ?>
			<div class="bai-dialog-footer">
				<a href="https://builditwithai.substack.com" target="_blank" rel="noopener" class="bai-dialog-link"><?php esc_html_e( 'Read past issues on Substack ↗', 'workbench' ); ?></a>
			</div>
		</div>
	</dialog>
	<?php
} );
