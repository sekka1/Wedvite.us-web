<?php

class fbAuth
{

var $facebook;
var $session;
var $me;
var $generic_db;

public function __construct(){

        require '../public/src/facebook.php';

        // Create our Application instance (replace this with your appId and secret).
        $this->facebook = new Facebook(array(
                                'appId'  => '197440486945083',
                                'secret' => 'ac5377e0d6f60fc22219fd4ea79227ed',
                                'cookie' => true,
                                'api' => '9f5ce02dbfae587d63dfb5368fcebcf3',
                                'baseurl' => 'http://wedvite.us',
                                ));

        // We may or may not have this data based on a $_GET or $_COOKIE based session.
        //
        // If we get a session here, it means we found a correctly signed session using
        // the Application Secret only Facebook and the Application know. We dont know
        // if it is still valid until we make an API call using the session. A session
        // can become invalid if it has already expired (should not be getting the
        // session back in this case) or if the user logged out of Facebook.
        $this->session = $this->facebook->getSession();

	// Setup DB connection
	Zend_Loader::loadClass( 'Generic' );

        $this->generic_db = new Generic();
}

public function hasIdentity(){

	$hasIdentity = false;

	// Session based API call.
	if ($this->session) {
		try {
			$uid = $this->facebook->getUser();
			$this->me = $this->facebook->api('/me');
		} catch (FacebookApiException $e) {
			error_log($e);  }
	}

	// login or logout url will be needed depending on current user state.
	if ($this->me) {  
		// Has a valid session
		//$logoutUrl = $facebook->getLogoutUrl();
		$hasIdentity = true;

		if( !$this->isUserInDB() ){
		// Put user in the db if this user is not in there
			$this->insertUserIntoDB();
		}
	} else {
		// Does not have a valid session
		//$loginUrl = $facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream, user_photos, friends_photos, friends_status, user_videos, friends_videos, read_stream, read_friendlists, manage_friendlists, read_requests'));
	}

	return $hasIdentity;
}

public function getUID(){

	return $this->session['uid'];

}

public function isUserInDB(){
// Checks if the user is in the DB

	$isInDB = false;

	$results = $this->generic_db->customQuery( 'facebook_users', 'SELECT * FROM facebook_users where id = ' . $this->session['uid'] );

	if( count( $results ) > 0 ){
		$isInDB = true;
	}

	return $isInDB;
}

public function getCurrentUsersInfo(){

	$results = $this->generic_db->customQuery( 'facebook_users', 'SELECT * FROM facebook_users where id = ' . $this->session['uid'] );

	return $results;
}

public function insertUserIntoDB(){

	$data = array();

	$data['id'] = $this->me['id'];
	$data['name'] = $this->me['name'];
	$data['first_name'] = $this->me['first_name'];
	$data['last_name'] = $this->me['last_name'];
	$data['fb_link'] = $this->me['link'];
	$data['username'] = $this->me['username'];
	$data['gender'] = $this->me['gender'];
	$data['locale'] = $this->me['locale'];
	$data['datetime_created'] = 'NOW()';
	$data['datetime_modified'] = 'NOW()';

	$fb_users_id_seq = $this->generic_db->save( 'facebook_users', $data );

	return $fb_users_id_seq;
}

}

?>
