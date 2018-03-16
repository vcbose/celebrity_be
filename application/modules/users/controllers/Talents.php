<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Talents extends MY_Controller
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

    public function dashboard()
    {
        is_login();
        $permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : '';

        /*User data*/
        $a_dashboard['userdata']    = $this->User_model->get_users($this->user_id, 0, TALENT)[0];
        
        $a_dashboard['user_id']     = $this->user_id;
        $a_dashboard['user_name']   = $this->session->get_userdata()['user_details'][0]->user_name;
        $a_dashboard['role']        = ($permission == DIRECTOR) ? 'Director' : 'Talent';

        /*Highlighted profiles*/
        $highligted_profiles_fields         = '';
        $highligted_profiles_where          = array();
        $highligted_profiles                = $this->User_model->get_highlight_users($highligted_profiles_fields, $highligted_profiles_where, 0, 20);
        $a_dashboard['highligted_profiles'] = (!empty($highligted_profiles)) ? $highligted_profiles : array();

        /*Notifications*/
        if ($permission == 2) {
            $noti_where['triggerd_from'] = $this->user_id;
        } else {
            $noti_where['user_id'] = $this->user_id;
        }

        $a_dashboard['notifications'] = array();
        /*Check notification exists or not*/
        $check_exists = $this->Notification_model->get_notifications('', $noti_where, '', '', $permission);
        if (!empty($check_exists)) {
            $notifications                = $this->Notification_model->render_notifications($check_exists);
            $a_dashboard['notifications'] = $notifications;
        }

        $recent_updates = $this->Notification_model->get_notifications('', array(), 0, 10, $permission);
        if (!empty($recent_updates)) {
            $notifications                = $this->Notification_model->render_notifications($recent_updates);
            $a_dashboard['recent_notifications'] = $notifications;
        }

        /*Configuration settings*/
        $a_dashboard['settings'] = $this->a_settings;

        /*Render dashboard view*/
        $this->load->admin_template('dashboard', 'users-', $a_dashboard);
    }

    public function activities()
    {
        is_login();
        $permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : '';

        /*User data*/
        $a_dashboard['user_id']   = $this->user_id;
        $a_dashboard['user_name'] = $this->session->get_userdata()['user_details'][0]->user_name;
        $a_dashboard['role']      = ($permission == 2) ? 'Director' : 'Talent';

        /*Notifications*/
        if ($permission == 2) {
            $noti_where['triggerd_from'] = $this->user_id;
        } else {
            $noti_where['user_id'] = $this->user_id;
        }

        $a_dashboard['notifications'] = array();
        /*Check notification exists or not*/
        $check_exists = $this->Notification_model->get_notifications('', $noti_where, '', '', $permission);
        // print_r($check_exists);
        if (!empty($check_exists))
        {
            $notifications                = $this->Notification_model->render_notifications($check_exists);
            $a_dashboard['notifications'] = $notifications;
        }

        /*Configuration settings*/
        $a_dashboard['settings'] = $this->a_settings;

        /*Render activities view*/
        $this->load->admin_template('activities', 'users-', $a_dashboard);
    }
}
