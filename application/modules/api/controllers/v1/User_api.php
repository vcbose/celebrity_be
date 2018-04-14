<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');
/**
*
* API class for settings  
*
*/
class User_api extends REST_Controller {

	public function __construct()
    {
    	parent::__construct();
    	$this->load->model("users/User_model");
    	$this->load->model("plans/Plans_model");
    }

    /**
    * post method for users
    * @param json post params
    * @return json  api response
    */
    public function register_post()
	{
		try{
			
			$post_data  = $this->post();
			$user_data 	 = $this->User_model->register_user($post_data, true);
			if( !empty($user_data) ){

				if(isset($user_data['user_id']) && $user_data['user_id'] > 0){
					// $result 	= $this->Plans_model->add_user_plan($user_id, $post_data['plan']);
					$response 	= array('status'=> true, 'message'=>'User registration successful', 'user_id'=> $user_data['user_id']);
					$this->response($response, parent::HTTP_CREATED);
				} else {

					$response 	= array('status'=> false, 'message'=> $user_data['message'], 'user_id'=> 0);
					$this->response($response, parent::HTTP_BAD_REQUEST);
				}
			} else {

				$response 	= array('status'=> $user_data['status'], 'message'=> $user_data['message'], 'user_id'=> 0);
				$this->response($response, parent::HTTP_BAD_REQUEST);
			}			

		}catch(Exception $ex){
			
			if($ex->getCode() == 10){
				$message = $ex->getMessage();
			}else{
				$message = 'Unexpected error occurred';
			}

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	/**
    * post method for users
    * @param json post params
    * @return json  api response
    */
    public function checkusername_post()
	{
		try{
			$post_data  = $this->post();
			if ( isset($post_data['user_name']) && trim($post_data['user_name']) != '' ) {

			    $check = $this->User_model->check_exists('cb_users', 'user_name', trim($post_data['user_name']) );

			    if (!$check) {
			        $response = array('status'=> false, 'message'=> 'Username already taken!');
			        $this->response($response, parent::HTTP_BAD_REQUEST);
			    } else {
			        $response = array('status'=> true, 'message'=> 'Username available');
			        $this->response($response, parent::HTTP_OK);
			    }
			} else {
				$response = array('status'=> false, 'message'=> 'Bad request! username is empty.');
				$this->response($response, parent::HTTP_BAD_REQUEST);
			}
		}catch(Exception $ex){

			if($ex->getCode() == 10){
				$message = $ex->getMessage();
			}else{
				$message = 'Unexpected error occurred';
			}

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * post method for users
    * @param json post params
    * @return json  api response
    */
    public function resetpassword_post()
	{
		try{

			$post_data  = $this->post();
			if ( isset($post_data['user_name']) && trim($post_data['user_name']) != '' && isset($post_data['password']) ) {

			    $check = $this->User_model->reset_password( $post_data );

			    if (!$check) {
			        $response = array('status'=> false, 'message'=> "Can't reset your password at this moment");
			        $this->response($response, parent::HTTP_BAD_REQUEST);
			    } else {
			        $response = array('status'=> true, 'message'=> 'Password is reset successfully!');
			        $this->response($response, parent::HTTP_OK);
			    }
			} else {
				$response = array('status'=> false, 'message'=> 'Bad request! username or password is empty.');
				$this->response($response, parent::HTTP_BAD_REQUEST);
			}
		}catch(Exception $ex){

			if($ex->getCode() == 10){
				$message = $ex->getMessage();
			}else{
				$message = 'Unexpected error occurred';
			}

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
    /**
    * post method for users
    * @param json post params
    * @return json  api response
    */
    public function userupdate_post()
	{
		try{
			
			$post_data  = $this->post();
			$user_data 	 = $this->User_model->edit_user($post_data, true);
			if( !empty($user_data) ){

				if(isset($user_data['user_id']) && $user_data['user_id'] > 0){
					// $result 	= $this->Plans_model->add_user_plan($user_id, $post_data['plan']);
					$response 	= array('status'=> true, 'message'=> $user_data['message'], 'user_id'=> $user_data['user_id']);
					$this->response($response, parent::HTTP_CREATED);
				} else {

					$response 	= array('status'=> false, 'message'=> $user_data['message'], 'user_id'=> 0);
					$this->response($response, parent::HTTP_BAD_REQUEST);
				}
			} else {

				$response 	= array('status'=> $user_data['status'], 'message'=> $user_data['message'], 'user_id'=> 0);
				$this->response($response, parent::HTTP_BAD_REQUEST);
			}			

		}catch(Exception $ex){
			
			if($ex->getCode() == 10){
				$message = $ex->getMessage();
			}else{
				$message = 'Unexpected error occurred';
			}

			$response = array('status'=> false, 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Get method for user
    * @param string get params
    * @return json  api response
    */
    public function user_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$get_data 	 = $this->get();
			
			if(isset($get_data['fields'])){
				$fields = $get_data['fields'];
				unset($get_data['fields']);
			}
			if(isset($get_data['offset'])){
				$offset = $get_data['offset'];
				unset($get_data['offset']);
			}
			if(isset($get_data['limit'])){
				$limit  = $get_data['limit'];
				unset($get_data['limit']);
			}

			$get_data['cbu.is_deleted'] = 0;
			$get_data['cbu.user_status'] = 1;

			$current_access = $this->User_model->getUserByToken();
			if( isset($current_access) && $current_access != null ){
				if( isset($get_data['cbu.user_id']) && ($get_data['cbu.user_id'] == $current_access) ){
					unset( $get_data['cbu.user_status']);
				}
			}
			
			$data 	  = $this->User_model->get_user_details($fields, $get_data, $offset, $limit);
			if(!empty($data)) {

				$response['status'] = true;
				$response['message'] = 'Users listing is successful';
				$response['data'] = $data;
				$this->response($response, parent::HTTP_OK);
			} else {

				$response['status'] = false;
				if( isset($get_data['cbu.user_id']) ){
					$response['message'] = 'User details not found, account may disabled or deleted';
				} else {
					$response['message'] = 'No users found know!';
				}
				$response['data'] = $data;
				$this->response($response, parent::HTTP_BAD_REQUEST);
			}

		}catch(Exception $ex){
			
			$response = array('status'=> true, 'message'=>'Unexpected error occurred');
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
    * Get method for hightlight user
    * @param string get params
    * @return json  api response
    */
    public function hightlight_user_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$get_data 	 = $this->get();
			
			if(isset($get_data['fields'])){
				$fields = $get_data['fields'];
				unset($get_data['fields']);
			}
			if(isset($get_data['offset'])){
				$offset = $get_data['offset'];
				unset($get_data['offset']);
			}
			if(isset($get_data['limit'])){
				$limit  = $get_data['limit'];
				unset($get_data['limit']);
			}

			$data 	  = $this->User_model->get_highlight_users($fields, $get_data, $offset, $limit);
			if(!empty($data)){
				$response = array('status'=> TRUE, 'message' => 'Users listing is successful', 'data' => $data);
			} else {
				throw new Exception('No hightlighted profiles matches!', 1);				
			}
			
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=> FALSE, 'message'=> $ex->getMessage());
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}


	/**
    * Get method for user count
    * @param string get params
    * @return json  api response
    */
    public function usercount_get()
	{
		try{
			$get_data 	 = $this->get();

			$data 	  = $this->User_model->get_user_count( $get_data );
			
			if(!empty($data)){
				$response = array('status'=> TRUE, 'data' => $data);
			} else {
				throw new Exception('Error getting user count', 1);
			}
			
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=> FALSE, 'message'=> $ex->getMessage());
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	/**
    * Get method for hightlight user
    * @param string get params
    * @return json  api response
    */
    public function testimonials_get()
	{
		try{
			$fields 	 = null;
			$offset 	 = null;
			$limit 		 = null;
			$get_data 	 = $this->get();
			
			if(isset($get_data['fields'])){
				$fields = $get_data['fields'];
				unset($get_data['fields']);
			}
			if(isset($get_data['offset'])){
				$offset = $get_data['offset'];
				unset($get_data['offset']);
			}
			if(isset($get_data['limit'])){
				$limit  = $get_data['limit'];
				unset($get_data['limit']);
			}
			$a_messages = array('I just wanted to share a quick note and let you know that you guys do a really good job. I’m glad I decided to work with you. It’s really great how easy your websites are to update and manage.', 'You made it so simple. My new site is so much faster and easier to work with than my old site. I just choose the page, make the change and click save. Thanks, guys!', 'Wow. I just updated my site and it was SO SIMPLE. I am blown away. You guys truly kick ass. Thanks for being so awesome. High fives!', 'You guys rocked on the sculpture', 'Thank you so much for doing a great job');
			
			$data 	  = $this->User_model->get_highlight_users($fields, $get_data, $offset, $limit);
			print_r($data);
			if(!empty($data)){

				$a_testimonials = array();
				foreach ($data as $key => $value) {
					
				}
				$response = array('status'=> TRUE, 'data' => $data);
			} else {
				throw new Exception('No hightlighted profiles matches!', 1);				
			}
			
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=> FALSE, 'message'=> $ex->getMessage());
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
}
?>