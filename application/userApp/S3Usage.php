<?php
/*
* This class performs actions on S3 datastore
*/

//if (!class_exists('S3')) require_once '../public/src/S3.php';
require '/usr/local/zend/apache2/htdocs/wedvite.us/application/userApp/S3.php';

// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJO6OOIFG3LCMZPGA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

// Bucket name for this app
if (!defined('bucket')) define('bucket', 'wedvite-photos');

class S3Usage{

	var $s3;

	public function __construct( ){

		// Instantiate the class
		$this->s3 = new S3(awsAccessKey, awsSecretKey);

	}
	public function test(){

		// List your buckets:
		echo "S3::listBuckets(): ".print_r($this->s3->listBuckets(), 1)."\n";
	}
	public function upload( $uploadFile ){
		// Input: Full system path to the file

		$uploadedFileName = '';

                // Put our file (also with public read access)
                if ( $this->s3->putObjectFile($uploadFile, bucket, baseName($uploadFile), S3::ACL_PUBLIC_READ)) {

			$uploadedFileName = baseName($uploadFile);
		}

		return $uploadedFileName; 
	}
	public function getObjectInfo( $uploadFile ){ 
	// Input: Just the file name

		return $this->s3->getObjectInfo( bucket, baseName($uploadFile));
	}
}

?>
