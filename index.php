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

<page id="page-0" size="A4" contenteditable>
	<?php
	$string     = file_get_contents( 'words.html' );
	$word_count = count(
		str_word_count(
			strip_tags(
				strtolower(
					$string
				)
			),
			1
		)
	);

	$x          = 0;
	$text       = '';
	while ( $x < 1 ) {
		$x++;
		$text = $text . $string;
	}

	$word_count = $word_count * $x;

	echo '<h1>Word count: ' . $word_count . '</h1>';
	echo $text;
?>
</page>

<script>
window.addEventListener( 'load', function( event ) {
	let pages       = document.querySelectorAll( 'page' );
	let page_number = 0;
	let page        = pages[ page_number ];

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
				if ( undefined === pages[ page_number + 1 ] ) {
					next_page = document.createElement( 'page' );
					next_page.setAttribute( 'size', 'A4' );
					next_page.setAttribute( 'contenteditable', '' );
					next_page.setAttribute( 'id', 'page-' + ( page_number + 1 ) );
					document.body.appendChild( next_page );

					pages = document.querySelectorAll( 'page' );
				} else {
					next_page = pages[ page_number + 1 ];
				}
				next_page.prepend( block );

				// If we have no scrollbars, then bail out of the loop.
				if ( false === has_scroll( page ) ) {
					page_number++;

					page = pages[ page_number ];

					break;
				}

			}

			if ( i === 1 ) {
				something = true;
			}

		} // endif;

	}

	/*
	*/
	let something = false;
	while ( something === false ) {


		move_elements_to_next_page();

		if ( false === has_scroll( page ) ) {
			break;
		}

	} // endwhile;


});


</script>

</body>
</html>