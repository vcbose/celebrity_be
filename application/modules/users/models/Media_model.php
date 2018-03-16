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

    /**
     * This function is used to get users
     */
    public function getRow($table, $fields = null, $where = array(), $offset = null, $limit = null)
    {
        if ($fields) {
            $this->db->select($fields);
        }
        return $this->db->get_where($table, $where, $limit, $offset)->result_array();
    }

    /**
     * This function is used to Insert record in table
     */
    public function insertRow($table, $data)
    {

        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * This function is used to Update record in table
     */
    public function updateRow($table, $data, $a_where, $batch = false)
    {
        // $this->db->where($col,$colVal);
        if( $batch ){
            $this->db->update_batch($table, $data, $a_where);
        } else {
            $this->db->update($table, $data, $a_where);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * This function is used to Update record in table
     */
    public function replaceRow($table, $data)
    {
        $this->db->replace($table, $data);
        return true;
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
    public function get_media( $fields = '', $where = array(), $offset = null, $limit = null )
    {
        /*Updating select cluses*/
        if( $fields != ''){
            $this->db->select( $fields );
        } else {
            $this->db->select( 'cbm.*, cbu.user_id, cbu.user_name, cbu.user_type, cbu.user_status, cbs.subscription_id, cbp.plan_id, cbp.plan_name' );
        }
        /*Default where cluses*/
        $where['cbs.subscription_status'] = isset($where['cbs.subscription_status'])?$where['cbs.subscription_status']:1;
        $where['cbm.moderate_status'] = isset($where['cbm.moderate_status'])?$where['cbm.moderate_status']:1;

        /*Join with users and subscriptions*/
        $this->db->join('cb_users AS cbu', 'cbu.user_id = cbm.user_id', 'left');
        $this->db->join('cb_subscriptions AS cbs', 'cbs.user_id = cbm.user_id', 'left');
        $this->db->join('cb_plans AS cbp', 'cbp.plan_id = cbs.plan_id', 'left');

        /*Collect feture values from plan meta*/
        if(isset($where['user_id'])) {

            $this->db->join('cb_plan_meta AS cbpm', 'cbpm.plan_id = cbp.plan_id', 'left');
            $a_where_in = array(FEATURE_TYPE_IMAGE_ID, FEATURE_TYPE_VIDEO_ID);
            $this->db->where_in( 'cbpm.feature_type', $a_where_in );
            unset($where['user_id']);
        }

        /*Username search*/
        if(isset($where['cbu.user_name'])) {
            
            $this->db->like('cbu.user_name', $where['cbu.user_name'], 'both');
            unset($where['user_name']);
        }

        if(isset($where['cbm.date'])) {
            $search_on_date = date( "Y-m-d",  strtotime($where['cbm.date']) );
            $this->db->where(' DATE(cbm.uploaded_on) = "'.$search_on_date.'" OR  DATE(cbm.modified_on) = "'.$search_on_date.'"');
            unset($where['cbm.date']);
        }

        $this->db->where( $where );

        $this->db->order_by("cbm.media_id", "DESC");
        $result = $this->db->get('cb_user_medias AS cbm', $offset, $limit)->result();
        
        // echo $this->db->last_query();
        if( !empty($result) ){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * Get existing video 
     * @param integer userId
     * @return boolean result
     */
    public function get_media_count( $where = array() )
    {
        $fields = 'sum(case when media_type = 1 then 1 else 0 end) AS image_count , sum(case when media_type = 2 then 1 else 0 end) AS video_count';
        // $this->db->select('photos,videos');
        $result = $this->getRow('cb_user_medias', $fields, $where);

        if($result && isset($result)){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * Get existing video
     * @param integer userId
     * @return boolean result
     */
    public function get_media_by_user($userId, $mediaType = '')
    {
        $whereData['user_id'] = $userId;
        if ($mediaType != '') {
            $whereData['media_type'] = $mediaType;
        }

        // $this->db->select('photos,videos');
        $result = $this->db->get_where('cb_user_medias', $whereData)->result_array();

        if ($result && isset($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get media plan count
     * @param integer userId
     * @return boolean result
     */
    public function get_media_plan_count($userId)
    {
        $media_count = [];

        $this->db->select('(CASE `cbpm`.`feature_type`
                            WHEN ' . FEATURE_TYPE_IMAGE_ID . ' THEN "image"
                            WHEN ' . FEATURE_TYPE_VIDEO_ID . ' THEN "video" END) AS media_type,
                        `cbpm`.`feature_value` AS media_count');
        $this->db->join('cb_plan_meta cbpm', 'cbpm.plan_id = cbs.plan_id', 'left');
        $this->db->where('cbs.user_id=', $userId);
        $this->db->where_in('cbpm.feature_type', array(FEATURE_TYPE_IMAGE_ID, FEATURE_TYPE_VIDEO_ID));
        $this->db->group_by('cbpm.feature_type');
        $result = $this->db->get('cb_subscriptions cbs')->result_array();

        if (is_array($result) && !empty($result)) {

            foreach ($result as $key => $value) {

                if ($value['media_type'] == 'image') {
                    $media_count['image'] = $value['media_count'];
                }

                if ($value['media_type'] == 'video') {
                    $media_count['video'] = $value['media_count'];
                }
            }
        }

        return $media_count;
    }

    /**
     * Get existing video count
     * @param integer userId
     * @return boolean result
     */
    public function get_video_count($userId)
    {
        $whereData['user_id']    = $userId;
        $whereData['media_type'] = MEDIA_TYPE_VIDEO;

        $result = $this->db->get_where('cb_user_medias', $whereData)->result_array();

        $videoCount = count($result);
        return $videoCount;
    }

    /**
     * Update method for user's media
     * @param integer userId
     * @param array image names
     * @param string video url
     * @return boolean result
     */
    public function update_media_info($requestID, $imgNames = [], $userVideoUrl = [], $type = 'insert')
    {
        $imgRes   = true;
        $videoRes = true;

        $insertData['media_type'] = MEDIA_TYPE_IMAGE;

        if (is_array($imgNames) && !empty($imgNames)) {

            foreach ($imgNames as $key => $image) {

                $insertData['media_name'] = $image;

                if ($type == 'insert') {
                    $insertData['user_id']     = $requestID;
                    $insertData['uploaded_on'] = date('Y-m-d h:m:s');
                    $imgRes                    = $this->db->insert('cb_user_medias', $insertData);
                } else {
                    $this->db->where('media_id', $requestID);

                    $insertData['modified_on']     = date('Y-m-d h:m:s');
                    $insertData['moderate_status'] = 0;
                    $imgRes                        = $this->db->update('cb_user_medias', $insertData);
                }
            }
        }

        if (is_array($userVideoUrl) && !empty($userVideoUrl)) {

            $insertData['media_type'] = MEDIA_TYPE_VIDEO;

            foreach ($userVideoUrl as $key => $video) {

                if ($type == 'insert') {
                    $insertData['user_id']    = $requestID;
                    $insertData['media_name'] = $video;
                    $videoRes                 = $this->db->insert('cb_user_medias', $insertData);
                } else {

                }
            }
        }

        return $imgRes + $videoRes;
    }

    /**
     * Update video url from given index
     * @param integer userId
     * @param string newUserVideo
     * @param integer oldVideoIndx
     * @return boolean response
     */
    public function update_video_url($userId, $newUserVideo, $oldVideoIndx)
    {
        $videos = $this->get_media_by_user($userId, MEDIA_TYPE_VIDEO);

        if (is_array($videos) && !empty($videos)) {

            if (isset($videos[$oldVideoIndx]['media_name'])) {

                $updateData['media_name'] = $newUserVideo;
                $whereData['user_id']     = $userId;
                $whereData['media_id']    = $videos[$oldVideoIndx]['media_id'];

                return $this->updateRow('cb_user_medias', $updateData, $whereData);
            } else {
                throw new Exception("Provided video index not exists", 1);
            }

        } else {
            throw new Exception("Could not read user video data", 1);
        }
    }

    /**
     * Dp Image update
     * @return <array> response
     */
    public function update_dp($userId, $dpImage)
    {
        $query = 'UPDATE `cb_user_medias` 
                    SET dp = 
                    CASE 
                      WHEN media_name="'.$dpImage.'" THEN  1
                      ELSE 0
                    END
                    WHERE user_id='.$userId.' AND media_type='.MEDIA_TYPE_IMAGE;
        
        return $this->db->query($query);
    }

    /**
     * Get user medias
     * @return <array> response
     */
    public function get_user_media($userId = null, $limit = null, $offset = null)
    {
        $condition   = "";
        $currentUser = $this->User_model->getUserByToken();

        if (($currentUser != ADMIN_USER_ID) && ($userId != $currentUser)) {
            $condition = " AND moderate_status = 1";
        }

        $query = "SELECT `cbu`.`user_id`, `user_name`,
                (SELECT media_name FROM cb_user_medias WHERE media_type=1 AND user_id=cbu.user_id AND dp=1) AS dp_image,
                (SELECT GROUP_CONCAT(media_name SEPARATOR ',') FROM cb_user_medias WHERE media_type=" . MEDIA_TYPE_IMAGE . $condition . " AND user_id=cbu.user_id) AS photos,
                (SELECT GROUP_CONCAT(media_name SEPARATOR ',') FROM cb_user_medias WHERE media_type=" . MEDIA_TYPE_VIDEO . $condition . " AND user_id=cbu.user_id) AS videos,
                CONCAT('" . USER_IMAGE_URL . "', `cbu`.`user_id`, '/') AS photo_dir_url
                FROM `cb_users` `cbu`";

        $query .= ($userId) ? " WHERE cbu.user_id = " . $userId : '';
        $query .= ($limit) ? " limit " . $limit : '';
        $query .= ($offset) ? " offset " . $offset : '';

        $res = $this->db->query($query);

        return $res->result_array();
    }
}
