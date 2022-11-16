<?php

/*
$words   = file_get_contents( 'words.html' );
$blocks = explode( '</p>
<p>', $words );
foreach ( $blocks as $key => $block ) {
	$block = strip_tags( $block );
	$block = '<p>' . $block . '</p>';

	$blocks[ $key ] = $block;
}
echo json_encode( $blocks, JSON_PRETTY_PRINT );
die;
*/

function word_count( $string ) {
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

	return $word_count;
}

$json   = file_get_contents( 'words.json' );
$blocks = json_decode( $json );

$blocks = array_slice( $blocks, 0, 50 ); // Chomp all but first 50 blocks off.
$shortened_json = json_encode( $blocks, JSON_PRETTY_PRINT );

header( 'Content-Type: application/json; charset=utf-8' );

echo $shortened_json;
