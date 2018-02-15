<?php
class Chat_model extends CI_Model {

	private $table  = '';
	public $user_id;
	
	function __construct(){
		
	  	parent::__construct();
	  	$this->table = 'cb_user_chats';
	  	$this->user_id = isset($this->session->get_userdata()['user_details'][0]->user_id)?$this->session->get_userdata()['user_details'][0]->user_id:'1';
	}

  	/**
   	  * This function is used to get users
   	  */
	public function getRow($table, $fields = null, $where = array(), $offset = null, $limit = null) {
		
		if($fields){
			$this->db->select($fields);
		}
		return $this->db->order_by('chat_id', 'ASC')->get_where($table, $where, $limit, $offset)->result_array();
    }

	/**
      * This function is used to Insert record in table  
      */
  	public function insertRow($table, $data){

	  	$this->db->insert($table, $data);
	  	return  $this->db->insert_id();
	}

	/**
      * This function is used to Update record in table  
      */
  	public function updateRow($table, $data, $a_where) {

  		// $this->db->where($col,$colVal);
		$this->db->update($table, $data, $a_where);
		
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
  	}

	/**
      * This function is used to Update record in table  
      */
  	public function replaceRow($table, $data) {

		$this->db->replace($table, $data);
		return true;
  	}

    /**
    * Submit chat
    */
    public function submit_chat( $chat_text = '', $chat_to = null, $chat_from = null){

    	$a_return  = array();
        $chat_from = ($chat_from) ? $chat_from : $this->user_id;

    	if( $chat_from == 1 ){

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'You are not logged in';
    		return $a_return;
    	}
    	
    	if(!$chat_text){

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'You haven\'t entered a chat message.';
    		return $a_return;
    	}
    
    	$chat['chat_from'] = $chat_from;
    	$chat['chat_to']   = $chat_to;
    	$chat['chat_text'] = $chat_text;
    	$chat['chat_on']   = date('Y-m-d H:i:s');
    	// The save method returns a MySQLi object
    	$insertID = $this->insertRow($this->table, $chat);
    	
    	if($insertID > 0 ){

    		$a_return['status'] = 1;
    		$a_return['msg'] = 'Successfull';
    		$a_return['insertID'] = $insertID;
    	} else {

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'Failed';
    		$a_return['insertID'] = $insertID;
    	}

    	return $a_return;
    }
    
    public function get_users(){

    }
    
    public function get_chats( $chat_to = null, $chat_from = null ){

    	$chats     = array();
        $chat_from = ($chat_from) ? $chat_from : $this->user_id;

    	if( $chat_from == 1 ){

    		$a_return['status'] = 0;
    		$a_return['msg'] = 'You are not logged in';
    		return $a_return;
    	}
        
        $a_where = array('chat_from' => $chat_from, 'chat_to' => $chat_to, );     
		$this->db->where( $a_where );
    	$this->db->join('cb_user_details', 'cb_user_chats.chat_from = cb_user_details.user_id', 'left');
    	$result = $this->db->order_by('chat_id', 'ASC')->get('cb_user_chats')->result();

    	return array('chats' => $result);
    }

    /**
    * Get user chat
    */
    public function get_user_chats($fields = null, $where = array(), $offset = null, $limit = null){
        
        // $a_where = array('chat_from' => $chat_from, 'chat_to' => $chat_to, );     
        
        // $this->db->where( $a_where );
        // $this->db->join('cb_user_details', 'cb_user_chats.chat_from = cb_user_details.user_id', 'left');
        // $result = $this->db->order_by('chat_id', 'ASC')->get('cb_user_chats')->result();

        if ($fields) {

            $this->db->select($fields);
        }

        $this->db->join('cb_user_details', 'cb_user_chats.chat_from = cb_user_details.user_id', 'left');
        $this->db->order_by('chat_id', 'ASC');
        return $this->db->get_where('cb_user_chats', $where, $limit, $offset)->result_array();
    }
    
}