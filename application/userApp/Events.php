<?php

class Events
{

public function __construct( ){

}
public function create( $request_vars ){
// Creates a new event for the user_id passed in

	$facebook_user_id = $request_vars->getParam( 'user_id' );
	$name = $name = $request_vars->getParam( 'name' );

	if( is_numeric( $facebook_user_id ) && $name != '' ){

		Zend_Loader::loadClass( 'Generic' );

                $generic_db = new Generic();

                $data = array();

                //$description = $request_vars->getParam( 'description' );
		$description = '';

		// Add this data into the event table
                $data['user_id_seq'] = $facebook_user_id;
                $data['name'] = $name;
                $data['description'] = $description;
                $data['datetime_created'] = 'NOW()';
                $data['datetime_modified'] = 'NOW()';

                $event_id_seq = $generic_db->save( 'event', $data );

                // Add a record for this event into the event_info table
                $data = array();

                $data['user_id_seq'] = $facebook_user_id;
                $data['event_id_seq'] = $event_id_seq;
                $data['name'] = '';
                $data['description'] = '';
                $data['datetime_created'] = 'NOW()';
                $data['datetime_modified'] = 'NOW()';

                $generic_db->save( 'event_info', $data );

                // Add a record for this event to set it to the default template
                $data = array();

                $data['user_id_seq'] = $facebook_user_id;
                $data['event_id_seq'] = $event_id_seq;
                $data['template_available_page_id_seq'] = 1;
                $data['datetime_created'] = 'NOW()';
                $data['datetime_modified'] = 'NOW()';

                $generic_db->save( 'template_used', $data );

                // Add a record in for the About Us Icon
                $data = array();

                $data['user_id_seq'] = $facebook_user_id;
                $data['event_id_seq'] = $event_id_seq;
                $data['name'] = 'About Us';
                $data['description'] = 'How we met+-+-+-+-+-+-Answer+-+-+-+-+-+-One weird fact+-+-+-+-+-+-Answer+-+-+-+-+-+-Our favoriate Activity+-+-+-+-+-+-Answer+-+-+-+-+-+-Something we would like to share+-+-+-+-+-+-Answer';
                $data['datetime_created'] = 'NOW()';
                $data['datetime_modified'] = 'NOW()';

                $generic_db->save( 'arbitrary_page', $data );

		// Add a record in for the Family Icon
                $data = array();

                $data['user_id_seq'] = $facebook_user_id;
                $data['event_id_seq'] = $event_id_seq;
                $data['name'] = 'Family';
                $data['description'] = 'Family 1s Name+-+-+-+-+-+-Family 1s blurb+-+-+-+-+-+-Family 2s Name+-+-+-+-+-+-Family 2s blurb';
                $data['datetime_created'] = 'NOW()';
                $data['datetime_modified'] = 'NOW()';

                $generic_db->save( 'arbitrary_page', $data );
	}
}


}

?>
