<!DOCTYPE html>
<html lang="en-NZ">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width" />
	<title>Test</title>
	<style>

body {
	background: rgb(204,204,204); 
}

#page-number {
	position: fixed;
	right: 15px;
	top: 15px;
	padding: 0.5rem 1rem;
	text-align: center;
	font-size: 2rem;
	background: #eee;
	box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}

page {
	background: white;
	display: block;
	margin: 0 auto;
	box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	margin-bottom: 20px;
	overflow: hidden;
}
page[size="A4"] {  
	width: 21cm;
	height: 29.7cm; 

	width: 17cm;
	height: 26.7cm; 
	padding: 1cm 2cm 2cm;
}
page[size="A4"][layout="landscape"] {
	width: 29.7cm;
	height: 21cm;
}
page[size="A3"] {
	width: 29.7cm;
	height: 42cm;
}
page[size="A3"][layout="landscape"] {
	width: 42cm;
	height: 29.7cm;  
}
page[size="A5"] {
	width: 14.8cm;
	height: 21cm;
}
page[size="A5"][layout="landscape"] {
	width: 21cm;
	height: 14.8cm;  
}
@media print {
	body, page {
		margin: 0;
		box-shadow: 0;
	}
}

.hidden-text {
	color: #ccc;
	background: #eee;
}

	</style>
</head>
<body>

<div id="page-number">1</div>

<page class="hidden-text" page_number="1" size="A4" contenteditable></page>

<script>

/**
 * Load page content via AJAX.
 */
const request = new XMLHttpRequest();
request.open(
	'GET',
	'words.php?pages=1,2,3',
	true
);
request.setRequestHeader( 'Content-type', 'application/json' );
request.onreadystatechange = function() {
	if ( request.readyState == 4 && request.status == 200 ) {
		let pages_content = JSON.parse( request.responseText );
		let the_pages    = document.querySelectorAll( 'page' );

		for ( const [ the_page_number, page_content ] of Object.entries( pages_content ) ) {
			const the_page = the_pages[ 0 ];
			the_page.innerHTML = the_page.innerHTML + page_content;
		}

	}
};
request.send();


window.addEventListener( 'load', function( event ) {
	const page_number_box = document.getElementById( 'page-number' );

	let pages             = document.querySelectorAll( 'page' );
	let start_page_number = 1;
	let page_number       = start_page_number;
	let page              = pages[ page_number - 1 ];

	/**
	 * Get the scroll distance from top of page.
	 *
	 * @return int The scroll distance from top of page.
	 */
	function get_scroll_from_top() {
		return window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
	}

	/**
	 * Does the page have scrollbars?
	 *
	 * @param object page The page to check for scrollbars.
	 * @return bool true if has scrollbars.
	 */
	function has_scroll( the_page ) {
		const has_scroll = the_page.scrollHeight > the_page.clientHeight;

		return has_scroll;
	}

	/**
	 * Move elements to the next page (and create that next page).
	 */
	function move_elements_to_next_page() {

		// If we have scrollbars, then remove text.
		if ( true === has_scroll( page ) ) {

			let i = page.children.length;
			while ( i > 0 ) {
				i = i - 1;
let bla = 0;
while ( bla < (1000*1000*10) ) {
	bla++;
}
				const block = page.children[ i ];
				let next_page;
				if ( undefined === pages[ page_number ] ) {
					next_page = document.createElement( 'page' );
					next_page.setAttribute( 'class', 'hidden-text' );
					next_page.setAttribute( 'page_number', ( page_number ) );
					next_page.setAttribute( 'size', 'A4' );
					next_page.setAttribute( 'contenteditable', '' );
					document.body.appendChild( next_page );

					pages = document.querySelectorAll( 'page' );
				} else {
					next_page = pages[ page_number ];
				}
				next_page.prepend( block );

				// If we have no scrollbars, then bail out of the loop.
				if ( false === has_scroll( page ) ) {
					page_number++;

					page = pages[ page_number - 1 ];
					page.removeAttribute( 'class', 'hidden-text' );

					break;
				}

			}

			if ( i === 1 ) {
				pages_left_load = false;
			}

		} // endif;

	}

	let pages_left_load = true;
	while ( pages_left_load === true ) {


		move_elements_to_next_page();

		if ( false === has_scroll( page ) ) {
			pages[ start_page_number - 1 ].removeAttribute( 'class', 'hidden-text' );
			break;
		}

	} // endwhile;

	/**
	 * Update stuff on scrolling.
	 */
	window.addEventListener( 'scroll', function() {
		let z = 0;
		while ( z < pages.length ) {
			if ( get_scroll_from_top() > pages[ z ].offsetTop ) {
				page_number_box.innerHTML = z + 1;
			}
			z++;
		}
	} );

});


</script>

</body>
</html>