/* Workbench: sidebar list filter + active-item reveal + header current link. */
( function () {
	// Mark the current page in the secondary nav (WP custom links don't self-mark).
	var path = location.pathname.replace( /\/+$/, '' ) || '/';
	document.querySelectorAll( '.bai-header .wp-block-navigation-item__content' ).forEach( function ( a ) {
		var href = a.getAttribute( 'href' );
		if ( ! href ) return;
		var p = href.replace( location.origin, '' ).replace( /\/+$/, '' ) || '/';
		if ( p === path ) a.setAttribute( 'aria-current', 'page' );
	} );

	// Reveal the active item when a deep-linked page loads far down the list.
	var active = document.querySelector( '.bai-nav-list li.is-active' );
	if ( active ) {
		active.scrollIntoView( { block: 'nearest' } );
	}

	var input = document.querySelector( '[data-bai-filter]' );
	var items = document.querySelectorAll( '.bai-nav-list .bai-nav-item' );
	if ( ! input || ! items.length ) {
		return;
	}
	input.addEventListener( 'input', function () {
		var q = input.value.trim().toLowerCase();
		items.forEach( function ( item ) {
			item.hidden = q && item.textContent.toLowerCase().indexOf( q ) === -1;
		} );
	} );

	// Keyboard shortcuts: ⌘K, Ctrl+K, or / to focus search; Escape to clear & blur
	document.addEventListener( 'keydown', function ( e ) {
		var tag = ( e.target.tagName || '' ).toLowerCase();
		var isInput = tag === 'input' || tag === 'textarea' || e.target.isContentEditable;

		if ( ( ( e.metaKey || e.ctrlKey ) && e.key.toLowerCase() === 'k' ) || ( e.key === '/' && ! isInput ) ) {
			e.preventDefault();
			input.focus();
			input.select();
		} else if ( e.key === 'Escape' && document.activeElement === input ) {
			input.value = '';
			input.dispatchEvent( new Event( 'input' ) );
			input.blur();
		}
	} );
} )();
