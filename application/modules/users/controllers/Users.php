<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

    public $a_settings;
    public $user_id;
    public $permission;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('plans/Plans_model');
        $this->load->model('plans/Subscriptions_model');
        $this->load->model('notifications/Notification_model');

        $this->user_id  = isset($this->session->get_userdata()['user_details'][0]->user_id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
        $this->permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : 0;

        $a_all_settings = setting_all();
        foreach ($a_all_settings as $s_key => $s_value) {
            $this->a_settings[$s_value->setting_type][$s_value->setting_id] = $s_value->setting_name;
        }
    }

    /**
     * This function is used to load login view page
     * @return Void
     */
    public function login()
    {
        if (isset($_SESSION['user_details'])) {
            redirect(base_url() . 'profiles', 'refresh');
        }

        if ('cb-admin' == $this->uri->segment(1)) {

            $this->session->set_userdata('last_segment', 'cb-admin');
            $this->load->admin_template('login');
        } else {

            $this->session->set_userdata('last_segment', 'signin');
            $this->load->view('users-login');

        }
        // $this->auth_user();
    }

    /**
     * This function is used for user authentication ( Working in login process )
     * @return Void
     */
    public function auth_user($page = '')
    {
        $return = $this->User_model->auth_user();
        $permission = 0;

        if (empty($return)) {

            $this->session->set_flashdata('messagePr', 'Invalid details');
            redirect(base_url() . $this->session->userdata('last_segment'), 'refresh');
        } else {

            if ($return == 'not_varified') {

                $this->session->set_flashdata('messagePr', 'This accout is not varified. Please contact to your admin..');
                redirect(base_url() . $this->session->userdata('last_segment'), 'refresh');
            } else {

                $this->session->set_userdata('user_details', $return);
                $permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : 0;
            }

            if ($permission != 1) {
                redirect(base_url() . 'dashboard', 'refresh');
            } else {
                redirect(base_url() . 'profiles', 'refresh');
            }
        }
    }

    /**
     * This function is used to registr user
     * @return Void
     */
    public function registration()
    {
        is_login();
        //Check if admin allow to registration for user
        if (CheckPermission()) {
            if ($this->input->post()) {

                if ($this->add_user()) {
                    // die();
                    $this->session->set_flashdata('messagePr', 'Successfully Registered..');
                }
                redirect(base_url() . '/profiles', 'refresh');
            } else {

                $a_all_plans = $this->Plans_model->get_plans();

                foreach ($a_all_plans as $p_key => $p_value) {
                    $a_plans[$p_value->plan_id] = $p_value->plan_name;
                }
                $this->load->admin_template('register', '', array('a_settings' => $this->a_settings, 'plans' => $a_plans));
            }
        } else {
            $this->session->set_flashdata('messagePr', 'Registration Not allowed..');
            redirect(base_url() . 'profiles', 'refresh');
        }
    }

    public function add_user()
    {
        $user_id = '';
        if ($this->input->post('register_submit')) {

            $postParams = $this->input->post();

            $user_id = $this->User_model->register_user($postParams);

            if ($user_id > 0) {
                $this->session->set_flashdata('messageSucces', 'Profile registrated in celebritybe!');
            } else {
                $this->session->set_flashdata('messagePr', 'Registration failed !');
            }

        }
    }

    public function edit_user()
    {
        $user_id = 0;
        if ($this->input->post('update')) {

            $postParams = $this->input->post();
            $user_id = $this->input->post('user_id');

            if($user_id){
                $upd_status = $this->User_model->edit_user( $postParams );
            } else {
                $upd_status = false;
            }

            if ( $upd_status ) {

                $this->session->set_flashdata('messageSucces', 'Profile details updated Successfully !');
            } else {
                $this->session->set_flashdata('messagePr', 'Registration failed !');
            }
            redirect(base_url() . 'profile-detail/edit/'.$user_id , 'refresh');
        }
    }

    public function checkUserName()
    {
        if ($this->input->post('user_name')) {
            $check = $this->User_model->check_exists('cb_users', 'user_name', $this->input->post('username'));
            if (!$check) {
                echo json_encode("Aready Taken");
            } else {
                echo "true";
            }
        }
    }

    /**
     * This function is used to logout user
     * @return Void
     */
    public function profiles()
    {
        is_login();
        $user_type                     = $this->uri->segment('2');
        $a_users = $this->User_model->get_users('', $this->permission, $user_type);
        $a_uf_plans = $this->Plans_model->get_plans('plan_id, plan_name', array('plan_status' => 1));
        foreach ($a_uf_plans as $key => $value) {
            $a_plans[$value->plan_id] = $value->plan_name;
        }
        if ($this->permission == 1) {
            $this->load->admin_template('profiles', '', array('settings' => $this->a_settings, 'userdata' => $a_users, 'plans' => $a_plans));
        } else {
            $this->load->admin_template('profiles', '', array('settings' => $this->a_settings, 'userdata' => $a_users, 'plans' => $a_plans));
        }
    }

    public function profile_detail($action, $user_id)
    {
        is_login();
        if (CheckPermission() || $this->permission == 2) {

            // $this->session->unset_userdata('user_deta ils');
            $action                      = $this->uri->segment('2');
            $user_id                     = $this->uri->segment('3');
            $user_type                   = $this->uri->segment('4');

            $noty_where['user_id']       = $user_id;
            $noty_where['triggerd_from'] = 3;
            $noty_where['map_id']        = 5;

            $b_edit = ($action == 'view') ? false : true;

            $a_users              = array();
            $a_subscriptions      = array();
            $a_user_features      = array();
            $alrdy_notifyed_where = array();
            $alrdy_notifyed       = array();
            $notifications        = array();

            $a_users = $this->User_model->get_users( $user_id, 0, $user_type );

            $a_post['permission'] = $this->permission;
            $a_post['from']       = $this->user_id;
            $a_post['to']         = $user_id;

            if ($this->permission == 1) {

                switch ($a_users[0]->user_type) {

                    case 3:
                        $a_post['from'] = $user_id;
                        /*Get all subscription of talent*/
                        $a_subscriptions = $this->Subscriptions_model->get_user_subscriptions($user_id);
                        /*Get all talent features*/
                        $a_feture_where['user_id'] = $user_id;
                        $a_user_features           = $this->Plans_model->get_features('', $a_feture_where);

                        $alrdy_notifyed_where['user_id'] = $user_id;
                        break;
                    case 2:
                        $a_post['from'] = $user_id;
                        // $a_post['to']       = '';
                        /*Get all notification triggered from director*/
                        $alrdy_notifyed_where['triggerd_from'] = $user_id;
                        break;
                    default:
                        break;
                }

            } else if ($this->permission == 2) {

                /*Notification for profile visited*/
                if ($a_users[0]->user_type == 3) {

                    $a_post['where']['map_id'] = 1;
                    $this->Notification_model->manage_notifications($a_post);
                    unset($a_post['where']);
                }
                /*Get all subscription of talent*/
                $a_subscriptions = $this->Subscriptions_model->get_user_subscriptions($user_id);

                /*Get all talent features*/
                $a_feture_where['user_id'] = $user_id;
                $a_user_features           = $this->Plans_model->get_features('', $a_feture_where);

                /*Get all notification triggered from director to a talent*/
                $alrdy_notifyed_where['user_id']       = $user_id;
                $alrdy_notifyed_where['triggerd_from'] = $this->user_id;
            } else {

                /*Get all subscription*/
                $a_subscriptions = $this->Subscriptions_model->get_user_subscriptions($user_id);
                /*Get all talent features*/
                $a_feture_where['user_id'] = $user_id;
                $a_user_features           = $this->Plans_model->get_features('', $a_feture_where);
                /*Get all notification of a talent*/
                $alrdy_notifyed_where['user_id'] = $user_id;
            }

            $check_exists = $this->Notification_model->get_notifications('', $alrdy_notifyed_where, '', '', $this->permission);

            $alrdy_notifyed = array();
            if (!empty($check_exists)) {

                $notifications = $this->Notification_model->render_notifications($check_exists);

                foreach ($check_exists as $key => $value) {
                    $alrdy_notifyed[$value['map_id']] = array('notify_id' => $value['notify_id'], 'triggerd_from' => $value['triggerd_from'], 'notification_on' => $value['notification_on'], 'notification_relation' => $value['notification_relation'], 'notification_status' => $value['notification_status']);
                }
            }

            /*All plan details*/
            $a_plans = array();
            $a_uf_plans = $this->Plans_model->get_plans('plan_id, plan_name', array('plan_status' => 1));
            foreach ($a_uf_plans as $p_key => $p_value) {
                $a_plans[$p_value->plan_id] = $p_value->plan_name;
            }

            $a_profil_data['action']        = $b_edit;
            $a_profil_data['user_id']       = $user_id;
            $a_profil_data['permission']    = $this->permission;
            $a_profil_data['user_type']     = $a_users[0]->user_type;
            $a_profil_data['plans']         = $a_plans;
            $a_profil_data['settings']      = $this->a_settings;
            $a_profil_data['userdata']      = $a_users[0];
            $a_profil_data['subscription']  = $a_subscriptions;
            $a_profil_data['features']      = $a_user_features;
            $a_profil_data['triggers']      = $a_post;
            $a_profil_data['notifyed']      = $alrdy_notifyed;
            $a_profil_data['notifications'] = $notifications;

            $this->load->admin_template('profile-detail', '', $a_profil_data);
        } else {

            $this->session->set_flashdata('messagePr', 'You Don\'t have this autherity ');
            redirect(base_url() . 'profiles', 'refresh');
        }
    }

    /**
     * This function is used to logout user
     * @return Void
     */
    public function logout()
    {
        is_login();
        $this->session->unset_userdata('user_details');
        redirect(base_url() . $this->session->userdata('last_segment'), 'refresh');
    }
}
