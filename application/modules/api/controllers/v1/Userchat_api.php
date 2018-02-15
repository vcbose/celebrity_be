<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load rest controller extenstion
require_once(APPPATH.'/libraries/REST_Controller.php');

/**
*
* API class for Interests  
*
*/
class Userchat_api extends REST_Controller {

	public function __construct()
    {
    	parent::__construct();
        $this->load->model('notifications/Chat_model');
    }

    /**
    * Post method for notifications
    * @param string post params
    * @return json  api response
    */
    public function userchat_post()
	{
		try{
			$a_post  	= $this->post();

	        if(is_array($a_post) && !empty($a_post) ){

	        	$chat_text 	= $a_post['chat_text'];
	        	$chat_to 	= $a_post['chat_to'];
	        	$chat_from 	= $a_post['chat_from'];

	            $response = $this->Chat_model->submit_chat( $chat_text, $chat_to, $chat_from);
	        } else {
	            throw new Exception("Post data processing error", 1);
	        }
			
			if($response['status']){
				$a_response['status']  = 'success';
				$a_response['message'] = 'Chat submitted successfully';
				$this->response($a_response, parent::HTTP_OK);
			}else{
				$err_msg = ($response['msg']) ? $response['msg'] : '';
				throw new Exception("Chat processing error: ".$err_msg, 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>'error', 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

    /**
    * Get method for user chat
    * @param string get params
    * @return json  api response
    */
    public function userchat_get()
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
			
			// $chat_to 	= 2;
			// $chat_from 	= 3;

			$data 	  = $this->Chat_model->get_user_chats($fields, $getParams, $limit, $offset);
			// $data 	  = $this->Chat_model->get_chats($chat_to, $chat_from);
			
			if($data){
				$response = array('status'=>'success', 'data' => $data);
				$this->response($response, parent::HTTP_OK);
			}else{
				throw new Exception("Error on get chat", 1);
			}

		}catch(Exception $ex){
			
			$message = $ex->getMessage();

			$response = array('status'=>'error', 'message'=> $message);
			$this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
		}
	}	

	/**
    * Put method for user chat
    * @param string get params
    * @return json  api response
    */
	public function userchat_put()
	{
		echo json_encode(array('status'=>'put method'));
	}
}

?>