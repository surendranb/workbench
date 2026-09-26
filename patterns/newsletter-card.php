<?php
/**
 * Title: Newsletter Card
 * Slug: workbench/newsletter-card
 * Categories: workbench
 * Description: Editorial callout card for email dispatch subscriptions.
 * Viewport Width: 880
 */
?>
<!-- wp:group {"className":"bai-newsletter-card","style":{"border":{"color":"var(--wp--preset--color--border)","width":"1px","radius":"12px"},"spacing":{"padding":{"top":"32px","right":"32px","bottom":"32px","left":"32px"}}},"backgroundColor":"surface","layout":{"type":"default"}} -->
<div class="wp-block-group bai-newsletter-card has-border-color has-surface-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:12px;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px">

	<!-- wp:paragraph {"style":{"color":{"text":"var(--wp--preset--color--accent)"},"spacing":{"margin":{"bottom":"8px"}},"typography":{"fontSize":"11px","fontWeight":"800","letterSpacing":"0.08em","textTransform":"uppercase"}}} -->
	<p class="has-text-color" style="color:var(--wp--preset--color--accent);margin-bottom:8px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase"><?php esc_html_e( 'NEWSLETTER', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"0px"}},"typography":{"fontSize":"20px","fontWeight":"700"}}} -->
	<h3 class="wp-block-heading" style="margin-top:0px;font-size:20px;font-weight:700"><?php esc_html_e( 'Stay in the loop', 'workbench' ); ?></h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"style":{"color":{"text":"var(--wp--preset--color--muted)"},"spacing":{"margin":{"bottom":"20px"}}}} -->
	<p class="has-text-color" style="color:var(--wp--preset--color--muted);margin-bottom:20px"><?php esc_html_e( 'Engineering logs, architecture breakdowns, and tool releases straight to your inbox.', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"style":{"border":{"radius":"6px"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:6px"><?php esc_html_e( 'Subscribe', 'workbench' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
