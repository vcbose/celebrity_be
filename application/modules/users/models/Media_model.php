<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Media_model extends MY_Controller
{

    public $a_settings;
    public $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('plans/Plans_model');
        $this->load->model('plans/Subscriptions_model');
        $this->load->model('notifications/Notification_model');

        $this->user_id  = isset($this->session->get_userdata()['user_details'][0]->user_id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
        $a_all_settings = setting_all();
        foreach ($a_all_settings as $s_key => $s_value) {
            $this->a_settings[$s_value->setting_type][$s_value->setting_id] = $s_value->setting_name;
        }
    }

    public function list_media()
    {

    }

    public function upload_media()
    {

    }

    public function replace_media()
    {

    }

    /**
     * Get existing video 
     * @param integer userId
     * @return boolean result
     */
    public function get_media($select = '', $where = array())
    {
        if ($select) {
            $this->db->select($select);
        }

        // $this->db->select('photos,videos');
        $result = $this->db->get_where('cb_user_medias', $where)->result_array();

        if($result && isset($result)){
            return $result;
        }else{
            return false;
        }
    }
}
