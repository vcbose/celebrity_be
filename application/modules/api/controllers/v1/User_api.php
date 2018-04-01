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
			
			$postParams  = $this->post();
			$user_data 	 = $this->User_model->register_user($postParams, true);
			if( !empty($user_data) ){

				if(isset($user_data['user_id']) && $user_data['user_id'] > 0){
					// $result 	= $this->Plans_model->add_user_plan($user_id, $postParams['plan']);
					$response 	= array('status'=> true, 'message'=>'User registration successful', 'user_id'=> $user_data['user_id']);
					$this->response($response, parent::HTTP_CREATED);
				} else {

					$response 	= array('status'=> false, 'message'=> $user_data['message'], 'user_id'=> 0);
					$this->response($response, parent::HTTP_BAD_REQUEST);
				}
			} else {

				$response 	= array('status'=> false, 'message'=>'User registration failed, please try again!', 'user_id'=> 0);
				$this->response($response, parent::HTTP_BAD_REQUEST);
			}			

		}catch(Exception $ex){
			
			if($ex->getCode() == 10){
				$message = $ex->getMessage();
			}else{
				$message = 'Unexpected error occurred';
			}

			$response = array('status'=>'error', 'message'=> $message);
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
			$getParams 	 = $this->get();
			
			if(isset($getParams['fields'])){
				$fields = $getParams['fields'];
				unset($getParams['fields']);
			}
			if(isset($getParams['offset'])){
				$offset = $getParams['offset'];
				unset($getParams['offset']);
			}
			if(isset($getParams['limit'])){
				$limit  = $getParams['limit'];
				unset($getParams['limit']);
			}

			$data 	  = $this->User_model->get_user_details($fields, $getParams, $offset, $limit);
			$response = array('status'=>'success', 'data' => $data);
			$this->response($response, parent::HTTP_OK);

		}catch(Exception $ex){
			
			$response = array('status'=>'error', 'message'=>'Unexpected error occurred');
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
			$getParams 	 = $this->get();
			
			if(isset($getParams['fields'])){
				$fields = $getParams['fields'];
				unset($getParams['fields']);
			}
			if(isset($getParams['offset'])){
				$offset = $getParams['offset'];
				unset($getParams['offset']);
			}
			if(isset($getParams['limit'])){
				$limit  = $getParams['limit'];
				unset($getParams['limit']);
			}

			$data 	  = $this->User_model->get_highlight_users($fields, $getParams, $offset, $limit);
			if(!empty($data)){
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


	/**
    * Get method for user count
    * @param string get params
    * @return json  api response
    */
    public function usercount_get()
	{
		try{
			$getParams 	 = $this->get();

			$data 	  = $this->User_model->get_user_count( $getParams );
			
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
}

?>