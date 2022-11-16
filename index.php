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

	</style>
</head>
<body>

<div id="page-number">1</div>

<page page_number="1" size="A4" contenteditable></page>

<script>

/**
 * Load page content via AJAX.
 */
const request = new XMLHttpRequest();
request.open(
	'GET',
	'words.php',
	true
);
request.setRequestHeader( 'Content-type', 'application/json' );
request.onreadystatechange = function() {
	if ( request.readyState == 4 && request.status == 200 ) {
		let blocks = JSON.parse( request.responseText );
		let pages  = document.querySelectorAll( 'page' );
		let page   = pages[0];

		let z = 0;
		while ( z < blocks.length ) {
			page.innerHTML = page.innerHTML + blocks[ z ];

			z++;
		}

	}
};
request.send();


window.addEventListener( 'load', function( event ) {
	let pages       = document.querySelectorAll( 'page' );
	let page_number = 1;
	let page        = pages[ page_number - 1 ];

	/**
	 * Update stuff on scrolling.
	 */
	window.addEventListener( 'scroll', function() {
		let z = 0;
		while ( z < pages.length ) {
			if ( get_scroll_from_top() > pages[ z ].offsetTop ) {
				const page_number_box = document.getElementById( 'page-number' );
				page_number_box.innerHTML = z + 1;
			}
			z++;
		}
	} );

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

				const block = page.children[ i ];
				let next_page;
				if ( undefined === pages[ page_number ] ) {
					next_page = document.createElement( 'page' );
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
			break;
		}

	} // endwhile;


});


</script>

</body>
</html>