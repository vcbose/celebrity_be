<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('users/User_model');
        $this->load->model('Notification_model');
        $this->load->model('Chat_model');

        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->user_id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
    }

    public function index()
    {
        $permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : 0;

        // $to = $this->uri->segment('2');
        $check_exists  = $this->Notification_model->get_notifications(null, array(), 0, 100, $permission);

        $notifications = $this->Notification_model->render_notifications($check_exists);

        $this->load->admin_template('notification', array('notifications' => $notifications));
    }

    public function manage_notification()
    {

        $a_post = (isset($_POST) && !empty($_POST)) ? $_POST : array();

        /*echo '<pre>';
        print_r($a_post);
        die;*/

        if (!empty($a_post)) {
            $j_response = $this->Notification_model->manage_notifications($a_post);
        } else {
            $j_response = json_encode(array('status' => false, 'msg' => getCBResponse('ER_REQUEST')));
        }

        echo $j_response;
        die();
    }

    public function get_notifications()
    {

        $a_post = (isset($_POST) && !empty($_POST)) ? $_POST : array();
        if (!empty($a_post)) {
            $j_response = $this->Notification_model->get_notifications('', $a_post);
        } else {
            $j_response = json_encode(array('status' => false, 'msg' => getCBResponse('ER_REQUEST')));
        }

        echo $j_response;
        die();
    }

    public function get_interview()
    {

        $a_post = (isset($_POST) && !empty($_POST)) ? $_POST : array();
        
        if (!empty($a_post)) {
            $a_response = $this->Notification_model->check_interview_exists($a_post);
            $j_response = json_encode($a_response, true);
        } else {
            $j_response = json_encode(array('status' => false, 'msg' => getCBResponse('ER_REQUEST')));
        }

        echo $j_response;
        die();
    }

    public function chat(){

        $to = $this->uri->segment('2'); 
        $this->load->admin_template('chat', array('user_id' => $this->user_id, 'to_user' => $to));
    }

    public function submit_chat($chat_text = '', $user_id = null)
    {

        $user_id = (isset($_POST['to_user'])) ? $_POST['to_user'] : $user_id;

        if ($user_id != null) {

            $chat_text = (isset($_POST['chat_text'])) ? $_POST['chat_text'] : $chat_text;
            $a_return  = $this->Chat_model->submit_chat($chat_text, $user_id);
            echo json_encode($a_return, true);
        } else {

            $a_return['status'] = 0;
            $a_return['msg']    = 'Invalid request';
            echo json_encode($a_return, true);
        }

    }

    public function get_chat($user_id = null)
    {
        $user_id  = (isset($_POST['to_user'])) ? $_POST['to_user'] : $user_id;
        $a_return = $this->Chat_model->get_chats($user_id);
        echo json_encode($a_return, true);
        die();
    }
}
