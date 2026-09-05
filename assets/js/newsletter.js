/**
 * Workbench: Newsletter top-nav modal interceptor & dialog handler.
 */
( function () {
	var dialog = document.getElementById( 'bai-newsletter-dialog' );
	if ( ! dialog ) {
		return;
	}

	// Intercept clicks on links pointing to builditwithai.substack.com
	// (except links inside the dialog itself or links with data-no-modal)
	document.addEventListener( 'click', function ( e ) {
		var link = e.target.closest( 'a[href*="builditwithai.substack.com"], .bai-nav-newsletter a' );
		if ( ! link || link.closest( '#bai-newsletter-dialog' ) || link.hasAttribute( 'data-no-modal' ) ) {
			return;
		}

		if ( typeof dialog.showModal === 'function' ) {
			e.preventDefault();
			dialog.showModal();
			var input = dialog.querySelector( 'input[type="email"]' );
			if ( input ) {
				input.focus();
			}
		}
	} );

	// Close on backdrop click (click target is dialog itself, outside its inner content)
	dialog.addEventListener( 'click', function ( e ) {
		if ( e.target === dialog ) {
			var rect = dialog.getBoundingClientRect();
			var isInDialog = (
				rect.top <= e.clientY && e.clientY <= rect.top + rect.height &&
				rect.left <= e.clientX && e.clientX <= rect.left + rect.width
			);
			if ( ! isInDialog ) {
				dialog.close();
			}
		}
	} );

	// Close on explicit close button click
	var closeBtn = dialog.querySelector( '.bai-dialog-close' );
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', function () {
			dialog.close();
		} );
	}
} )();
