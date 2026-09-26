<?php
/**
 * Title: Project Navigation
 * Slug: workbench/project-nav
 * Categories: workbench
 * Description: Sub-navigation tabs for project detail pages (Overview, Setup, Docs, and external project links).
 * Viewport Width: 1120
 */
?>
<!-- wp:group {"tagName":"nav","className":"bai-project-nav","layout":{"type":"flex","flexWrap":"wrap"}} -->
<nav class="wp-block-group bai-project-nav">

	<!-- wp:navigation {"overlayMenu":"never","className":"bai-project-nav-tabs","layout":{"type":"flex","orientation":"horizontal"}} -->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Overview', 'workbench' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Setup', 'workbench' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Docs', 'workbench' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Website ↗', 'workbench' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'GitHub ↗', 'workbench' ); ?>","url":"#","kind":"custom","isTopLevelLink":true} /-->
	<!-- /wp:navigation -->

</nav>
<!-- /wp:group -->
