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
			$postParams  = json_decode($postParams[0], true);

			$user_id 	 = $this->User_model->registerUser($postParams, true);

			if($user_id > 0){
				$result 	= $this->Plans_model->add_user_plan($user_id, $postParams['plan']);

				$response 	= array('status'=>'success', 'message'=>'User registration successfull', 'user_id'=>$user_id);
				$this->response($response, parent::HTTP_CREATED);
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
    * Get method for setting
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
}

?>