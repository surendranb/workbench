<?php
/**
 * Title: Project Notes
 * Slug: workbench/project-notes
 * Categories: workbench
 * Description: Sticky notes card for project detail pages — tech stack pills and action links.
 * Viewport Width: 1120
 */
?>
<!-- wp:group {"tagName":"aside","className":"bai-notes-card","style":{"border":{"color":"var(--wp--preset--color--border)","width":"1px","radius":"12px"},"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"dimensions":{"minHeight":"100%"}},"backgroundColor":"surface","layout":{"type":"default"}} -->
<aside class="wp-block-group bai-notes-card has-border-color has-surface-background-color has-background" style="border-color:var(--wp--preset--color--border);border-width:1px;border-radius:12px;padding:24px">

	<!-- wp:paragraph {"className":"bai-notes-heading"} -->
	<p class="bai-notes-heading"><?php esc_html_e( 'Project Notes', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"style":{"typography":{"fontSize":"12px","lineHeight":"1.7"},"color":{"text":"var(--wp--preset--color--muted)"},"spacing":{"margin":{"bottom":"16px"}}}} -->
	<p style="font-size:12px;line-height:1.7;color:var(--wp--preset--color--muted);margin-bottom:16px"><strong style="font-weight:700;color:var(--wp--preset--color--title)"><?php esc_html_e( 'Status', 'workbench' ); ?></strong> — <?php esc_html_e( 'Maintained · Active', 'workbench' ); ?><br><strong style="font-weight:700;color:var(--wp--preset--color--title)"><?php esc_html_e( 'Type', 'workbench' ); ?></strong> — <?php esc_html_e( 'Open-source tool', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"className":"bai-built-label"} -->
	<p class="bai-built-label"><?php esc_html_e( 'Tech Stack', 'workbench' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"className":"bai-tech-pills","style":{"typography":{"lineHeight":"1.9"}}} -->
	<p class="bai-tech-pills" style="line-height:1.9"><span class="bai-tech-pill">PHP</span> <span class="bai-tech-pill">WordPress</span> <span class="bai-tech-pill">CSS</span> <span class="bai-tech-pill">JS</span></p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"bai-notes-actions","style":{"spacing":{"margin":{"top":"0"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group bai-notes-actions" style="margin-top:0">

		<!-- wp:buttons {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"default"}} -->
		<div class="wp-block-buttons">

			<!-- wp:button {"width":100,"style":{"border":{"radius":"8px"}}} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100" style="border-radius:8px"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:8px"><?php esc_html_e( 'Visit Project Home ↗', 'workbench' ); ?></a></div>
			<!-- /wp:button -->

			<!-- wp:button {"width":100,"className":"is-style-outline","style":{"border":{"radius":"8px"},"typography":{"fontSize":"12px","fontWeight":"600"}}} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline" style="border-radius:8px"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:8px"><?php esc_html_e( 'View Source ↗', 'workbench' ); ?></a></div>
			<!-- /wp:button -->

		</div>
		<!-- /wp:buttons -->

		<!-- wp:paragraph {"className":"bai-notes-tertiary","style":{"spacing":{"margin":{"top":"14px","bottom":"0"}}}} -->
		<p class="bai-notes-tertiary" style="margin-top:14px;margin-bottom:0"><a href="#"><?php esc_html_e( 'Standalone Showcase Page →', 'workbench' ); ?></a></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

</aside>
<!-- /wp:group -->
