<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    public function indexAction()
    {
//	header("Location: /events"); /* Redirect browser */
/*        // action body
	$this->view->title = "My Albums"; 
        $this->view->headTitle($this->view->title, 'PREPEND'); 
        $albums = new Model_DbTable_Albums(); 
        $this->view->albums = $albums->fetchAll();
*/
    }
    public function oneAction()
    {

    }
    public function twoAction()
    {

    }
    public function eventsAction()
    {

    }
    public function unauthAction(){

	// Sets which template you want for the output
        $this->view->templateType = 'none';

        $auth = Zend_Auth::getInstance();

        if(!$auth->hasIdentity()){
                 $this->_redirect('/customers');
        } else {
                echo $auth->getIdentity();
                //print_r ($auth);
        }
    }
    public function fbcheckinsAction(){
 
    }
    public function fbfeedAction(){

    }
    public function fblikesAction(){

    }
    public function tests3Action(){

	Zend_Loader::loadClass( 'S3Usage' );

	$s3 = new S3Usage();

	$s3->test() . '<br/>';

	$uploadedFileName = $s3->upload( '/tmp/photo-album.png' );

	print_r( $s3->getObjectInfo( $uploadedFileName ) );

    }
}
