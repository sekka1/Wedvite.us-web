<?php
/*
This class looks for images to convert to a thumbnail

*/

class convertToThumbnail
{

public function __construct( $client_id_seq, $user_id_seq ){


}
public function convert( $something ){

	Zend_Loader::loadClass('Generic');

	$generic_db = new Generic();

	// Pull up items that needs to be converted
	$results = $generic_db->customQuery( 'photos', 'SELECT * FROM photos WHERE thumb_url="/place-holder.png" LIMIT 5' );

	foreach( $results as $aResult ){
	// These items will need to be converted

		$image_uri = 'http://' . $aResult['server_location'] . $aResult['image_url'];

		$thumbFile = '/tmp' . str_replace( ".", "-thumb.", $aResult['image_url'] );

		// Get the image from Amazon S3
		$image = file_get_contents( $image_uri );

		$temp_filename = '/tmp/' . rand();

		// Put file in the /tmp dir
		$fh = fopen( $temp_filename, 'w'); 
		fwrite($fh, $image); 
		fclose( $fh );

		// Convert
		system("/usr/bin/convert $temp_filename -resize 90x100 $thumbFile");

		// Upload the thumbnail to Amazon S3
		Zend_Loader::loadClass( 'S3Usage' );

		$s3 = new S3Usage();

		$uploadedFileName = $s3->upload( $thumbFile );

		// Delete these files as it is going up to Amazon S3 for storage
		unlink( $temp_filename );
		unlink( $thumbFile );

		if( $uploadedFileName != '' ){
			// Upload to S3 was sucessful

			// Update DB of the new file name
			$data = array();

			$data['thumb_url'] = '/' . $uploadedFileName; //str_replace( ".", "-thumb.", $aResult['image_url'] );
			$data['datetime_modified'] = 'NOW()';

			$id_seq = $generic_db->edit_noauth( 'photos', $aResult['photos_id_seq'], $data, 'photos_id_seq' );

		}
	}
}


}

?>
