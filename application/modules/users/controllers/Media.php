<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Media extends MY_Controller
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
        $this->load->helper('form');
        $this->user_id    = isset($this->session->get_userdata()['user_details'][0]->user_id) ? $this->session->get_userdata()['user_details'][0]->user_id : '1';
        $this->permission = isset($this->session->get_userdata()['user_details'][0]->user_type) ? $this->session->get_userdata()['user_details'][0]->user_type : 0;
        $a_all_settings   = setting_all();
        foreach ($a_all_settings as $s_key => $s_value) {
            $this->a_settings[$s_value->setting_type][$s_value->setting_id] = $s_value->setting_name;
        }
    }

    public function list_media()
    {
        is_login();
        $user_id = $this->uri->segment('2');
        if ($this->permission != 1 && $user_id != $this->user_id):
            $a_media = array();
        else:
            /*Current features*/
            $a_feture_where['user_id'] = $user_id;
            $a_uf_user_features        = $this->Plans_model->get_features('', $a_feture_where, '', '', false);
            $a_user_features           = array();

            $current_plan = isset($a_uf_user_features[0]['plan_id'])?$a_uf_user_features[0]['plan_id']:1;
            foreach ($a_uf_user_features as $f_key => $f_value) {
                $a_user_features[$f_value['setting_name']] = $f_value;
            }

            /*All plan details*/
            $a_plans    = array();
            $a_uf_plans = $this->Plans_model->get_plans('plan_id, plan_name', array('plan_status' => 1));
            foreach ($a_uf_plans as $p_key => $p_value) {
                $a_plans[$p_value->plan_id] = $p_value->plan_name;
            }
            /*get user media*/
            $a_check_exists_where['user_id'] = $user_id;
            $a_check_exists_where['in_plan'] = $current_plan;
            // $a_check_exists_where['media_type'] = MEDIA_TYPE_IMAGE;
            $check_exists = $this->User_model->get_media(' sum(case when media_type = 1 then 1 else 0 end) AS image_count , sum(case when media_type = 2 then 1 else 0 end) AS video_count ', $a_check_exists_where);

            $uploaded_images = 0;
            $uploaded_videos = 0;

            $remaining_images = 0;
            $remaining_videos = 0;

            $image_status = true;
            $video_status = true;

            /*Update upload status to fales it will remove upload image option from view*/
            if (!empty($check_exists) && isset($check_exists[0])) {

                $uploaded_images = isset($check_exists[0]['image_count']) ? $check_exists[0]['image_count'] : 0;
                $uploaded_videos = isset($check_exists[0]['video_count']) ? $check_exists[0]['video_count'] : 0;

                if ($uploaded_images >= $a_user_features['Images']['feature_value']) {
                    $image_status = false;
                }
                $remaining_images = $a_user_features['Images']['feature_value'] - $uploaded_images;

                if ($uploaded_videos >= $a_user_features['Videos']['feature_value']) {
                    $video_status = false;
                }
                $remaining_videos = $a_user_features['Videos']['feature_value'] - $uploaded_videos;
            }

            /*Fetch all user medias*/
            $a_media['list'] = $this->User_model->getMediaByUser($user_id);

            /*Media render data settings*/
            $a_media['image_status']     = $image_status;
            $a_media['remaining_images'] = ($remaining_images < 0) ? 0 : $remaining_images;
            $a_media['video_status']     = $video_status;
            $a_media['remaining_videos'] = ($remaining_videos < 0) ? 0 : $remaining_videos;
            $a_media['current_plan'] = $current_plan;

            $a_media['userdata'] = array('current_user' => $this->user_id, 'user_id' => $user_id, 'permission' => $this->permission);
            $a_media['plans']    = $a_plans;
            $a_media['features'] = $a_user_features;
        endif;

        $this->load->admin_template('media', '', $a_media);
    }

    public function upload_media()
    {
        is_login();
        $dataInfo  = array();
        $user_id   = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $a_reponse = array();
        $a_images  = array();
        $a_videos  = array();
        /*New uploads*/
        if (isset($_FILES['photos']) && !empty($_FILES['photos']) && $user_id != 0) {

            $this->load->library('upload');
            $this->upload->initialize($this->set_upload_options($user_id));

            $files = $_FILES;
            $cpt   = count($_FILES['photos']['name']);

            for ($i = 0; $i < $cpt; $i++) {

                $_FILES['photos']['name']     = $files['photos']['name'][$i];
                $_FILES['photos']['type']     = $files['photos']['type'][$i];
                $_FILES['photos']['tmp_name'] = $files['photos']['tmp_name'][$i];
                $_FILES['photos']['error']    = $files['photos']['error'][$i];
                $_FILES['photos']['size']     = $files['photos']['size'][$i];

                if ($_FILES['photos']['name'] != '') {
                    if (!$this->upload->do_upload('photos')) {
                        $error                 = $this->upload->display_errors();
                        $a_reponse['errors'][] = $error;
                    } else {

                        $data       = array('upload_data' => $this->upload->data());
                        $a_images[] = $data['upload_data']['file_name'];
                        $this->User_model->update_media_info( $user_id, $a_images, $a_videos, 'insert',  $this->input->post('current_plan') );
                        $a_reponse['success'][] = 'Image ' . $data['upload_data']['file_name'] . ' has been uploaded successfully & committed for the moderation !';
                    }
                }
            }
        }
        /*Replace action*/
        if (isset($_FILES['replace']) && !empty($_FILES['replace']) && $user_id != 0) {

            $this->load->library('upload');
            $this->upload->initialize($this->set_upload_options($user_id, true));

            $r_files = $_FILES;

            foreach ($_FILES['replace'] as $file_info => $f_data) {
                if ($file_info == 'name') {
                    foreach ($f_data as $m_id => $data) {
                        $a_replace_where = array('media_id' => $m_id);
                        $remove_media    = $this->User_model->get_media('', $a_replace_where);
                        $replace_src     = isset($remove_media[0]['media_name']) ? $remove_media[0]['media_name'] : '';

                        if (!empty($remove_media) && $replace_src != '') {

                            if ($data != '') {

                                $_FILES['replace']['name']     = $r_files['replace']['name'][$m_id];
                                $_FILES['replace']['type']     = $r_files['replace']['type'][$m_id];
                                $_FILES['replace']['tmp_name'] = $r_files['replace']['tmp_name'][$m_id];
                                $_FILES['replace']['error']    = $r_files['replace']['error'][$m_id];
                                $_FILES['replace']['size']     = $r_files['replace']['size'][$m_id];

                                if (!$this->upload->do_upload('replace')) {

                                    $error                 = $this->upload->display_errors();
                                    $a_reponse['errors'][] = $error;
                                } else {

                                    $remove_img = './assets/uploads/' . $user_id . '/' . $replace_src;
                                    if (file_exists($remove_img)) {
                                        unlink($remove_img);
                                    }

                                    $data               = array('replace_data' => $this->upload->data());
                                    $a_replace_images[] = $data['replace_data']['file_name'];

                                    $this->User_model->update_media_info($m_id, $a_replace_images, $a_videos, 'update', $this->input->post('current_plan'));
                                    $a_reponse['success'][] = 'Image ' . $data['replace_data']['file_name'] . ' replaced successfully & committed for the moderation !';
                                }
                            }
                        }
                    }
                }
            }
        }
        /*Videos*/
        if (isset($_POST['videos']) && !empty($_POST['videos'])) {

            foreach ($_POST['videos'] as $v_key => $v_value) {

                if( '' != trim($v_value) ){
                    $a_new_videos[] = $v_value;
                    $this->User_model->update_media_info($user_id, array(), $a_new_videos);
                    $a_reponse['success'][] = 'Video ' . $v_value . 'updated successfully & committed for the moderation !';
                }
            }
        }

        /*Update profile picture*/
        $dp = $this->input->post($user_id, 'dp');
        $this->set_dp( $dp );

        if (!empty($a_reponse['errors'])) {
            $this->session->set_flashdata('messagePr', strip_tags(implode(',', $a_reponse['errors'])));
        }

        if (!empty($a_reponse['success'])) {
            $this->session->set_flashdata('messageSucces', implode(',', $a_reponse['success']));
        }

        redirect(base_url() . 'media/' . $user_id, 'refresh');
    }

    /*
    * Params  : Media name and media id 
    * dp array's key is media_id and name is value
    */
    public function set_dp( $user_id = 0, $dp = array() )
    {
        if( $user_id != 0 && !empty($dp) ){
            
            if( ($dp_url =  implode(',', $dp)) != '' ){

                // $dp_url = implode(',', $dp);
                $media_id = key($dp);
                $this->User_model->updateRow('cb_user_details', array('dp' => $dp_url ), array('user_id' => $user_id));

                /*Reset all dp values and set new value*/
                $this->User_model->updateRow('cb_user_medias', array('dp' => 0), array('user_id' => $user_id));
                $this->User_model->updateRow('cb_user_medias', array('dp' => 1), array('media_id' => $media_id));
                return true;
            } else {
                return false;
            }
        } else {

            return false;
        }
    }

    private function set_upload_options($dir, $overwrite = false)
    {
        //upload an image options
        $config                  = array();
        $config['upload_path']   = "./assets/uploads";
        $config['allowed_types'] = "jpeg|gif|jpg|jpeg|png";
        $config['overwrite']     = $overwrite;
        $config['max_size']      = 2048;
        $config['encrypt_name']  = true;
        $path                    = $config['upload_path'] . '/' . $dir;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $config['upload_path'] = $path;

        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        return $config;
    }

    public function replace_media()
    {

    }
}
