<?php
/**
 * Title: Showcase Hero
 * Slug: workbench/showcase-hero
 * Categories: workbench
 * Description: Full-width tinted hero with serif display headline, lede and actions. Borrowed from theme-gallery landing language.
 * Viewport Width: 1400
 */
?>
<!-- wp:group {"tagName":"section","align":"full","style":{"spacing":{"padding":{"top":"72px","bottom":"72px","left":"64px","right":"64px"}},"color":{"gradient":"radial-gradient(120% 120% at 85% 10%, var(--wp--preset--color--accent-wash) 0%, var(--wp--preset--color--page) 60%)"},"border":{"radius":"0px"}},"layout":{"type":"constrained","contentSize":"880px"}} -->
<section class="wp-block-group alignfull has-background" style="border-radius:0px;background:radial-gradient(120% 120% at 85% 10%, var(--wp--preset--color--accent-wash) 0%, var(--wp--preset--color--page) 60%);padding-top:72px;padding-right:64px;padding-bottom:72px;padding-left:64px">

	<!-- wp:paragraph {"className":"bai-kicker","style":{"color":{"text":"var(--wp--preset--color--accent)"},"spacing":{"margin":{"bottom":"14px"}},"typography":{"fontSize":"12px","fontWeight":"800","letterSpacing":"0.08em","lineHeight":"1","textTransform":"uppercase"}}} -->
	<p class="bai-kicker has-text-color" style="color:var(--wp--preset--color--accent);margin-bottom:14px;font-size:12px;font-weight:800;line-height:1;text-transform:uppercase;letter-spacing:0.08em"><?php esc_html_e( 'Showcase', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"clamp(40px, 6vw, 64px)","fontWeight":"500","letterSpacing":"-0.02em","lineHeight":"1.05","fontFamily":"var(--wp--preset--font-family--display)"},"spacing":{"margin":{"bottom":"20px"}}}} -->
	<h1 class="wp-block-heading" style="margin-bottom:20px;font-family:var(--wp--preset--font-family--display);font-size:clamp(40px, 6vw, 64px);font-weight:500;letter-spacing:-0.02em;line-height:1.05"><?php esc_html_e( 'Beautiful tools for every idea', 'workbench' ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"style":{"typography":{"fontSize":"19px","lineHeight":"1.55"},"color":{"text":"var(--wp--preset--color--lede)"},"spacing":{"margin":{"top":"0px","bottom":"32px"}}}} -->
	<p class="has-text-color" style="color:var(--wp--preset--color--lede);margin-top:0px;margin-bottom:32px;font-size:19px;line-height:1.55"><?php esc_html_e( 'A short, honest description of what this project does and who it is for. One sentence that earns a click.', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"blockGap":"12px"}},"layout":{"type":"flex"}} -->
	<div class="wp-block-buttons">

		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Get started', 'workbench' ); ?></a></div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'View Source ↗', 'workbench' ); ?></a></div>
		<!-- /wp:button -->

	</div>
	<!-- /wp:buttons -->

</section>
<!-- /wp:group -->
