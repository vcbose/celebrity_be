<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Load rest controller extenstion
require_once APPPATH . '/libraries/REST_Controller.php';
/**
 *
 * API class for settings
 *
 */
class Usermedia_api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("users/User_model");
        $this->load->model("plans/Plans_model");
        $this->load->model("users/Mediav2_model");
    }

    /**
     * Create method for user's media
     * @param json post params
     * @return json  api response
     */
    public function usermedia_post()
    {
        try {

            $postParams           = $this->post();
            $images               = [];
            $user_id              = isset($postParams['user_id']) ? $postParams['user_id'] : 0;
            $set_dp               = isset($postParams['set_dp']) ? $postParams['set_dp'] : null;
            $user_post_image_data = isset($postParams['user_image']) ? $postParams['user_image'] : [];
            $user_post_video_urls = isset($postParams['user_video']) ? $postParams['user_video'] : [];
            $response             = array();

            if (!$user_id) {
                throw new Exception("Invalid user id requested", 1);
            }

            // Get image count from user plan
            $a_feture_where['user_id'] = $user_id;
            $a_uf_user_features        = $this->Plans_model->get_features('', $a_feture_where, '', '', false);
            $a_user_features           = array();

            $current_plan = isset($a_uf_user_features[0]['plan_id']) ? $a_uf_user_features[0]['plan_id'] : 1;
            foreach ($a_uf_user_features as $f_key => $f_value) {
                $a_user_features[$f_value['setting_name']] = $f_value;
            }
            // print_r($a_user_features);

            /*Get user media*/
            $a_check_exists_where['user_id'] = $user_id;
            $a_check_exists_where['in_plan'] = $current_plan;
            // $a_check_exists_where['media_type'] = MEDIA_TYPE_IMAGE;
            $check_exists = $this->Mediav2_model->get_media_count($a_check_exists_where);
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
            } else {

                throw new Exception("Not available on you current subscription plan", 1);
            }

            // Base64 to image convertion
            if (is_array($user_post_image_data) && !empty($user_post_image_data)) {
                $i_response = array();
                if ($remaining_images >= count($user_post_image_data)) {

                    foreach ($user_post_image_data as $key => $img_data) {
                        $image = $this->base64_to_image($user_id, $img_data);
                        // Updating media information into user table
                        if ($image) {
                            $images[] = $image;
                            $i_response['images'] = array('status' => true, 'message' => 'Image has been uploaded successfully');
                        } else {
                            $i_response['images'] = array('status' => false, 'message' => "Can't proceed with requested media file, please upload file size under ".USER_IMAGE_MAX_UPLOAD."MB. ");
                        }
                    }

                    if(!empty($images)){
                        $result = $this->Mediav2_model->update_media_info($user_id, $images, array(), 'insert', $current_plan, $set_dp);
                        if ($result) {

                            $response['status']     = $i_response['images']['status'];
                            $response['message']    = $i_response['images']['message'];
                            // $this->response($response, parent::HTTP_OK);
                        } else {
                            $response['status']     = false;
                            $response['message']    = 'Imgage upload failed, please try after sometimes!';
                        }
                    }
                } else {

                    if ($remaining_images == 0) {
                        $message = "Could not upload images, all available images are uploaded";
                    } else {
                        $message = "Trying to upload more than available images count, you have only " . $remaining_images . " available.";
                    }

                    $response['status']     = false;
                    $response['message']    = $message;
                }
            }

            if (is_array($user_post_video_urls) && !empty($user_post_video_urls)) {
                $v_response = array();
                // Check video url count
                if ($remaining_videos >= count($user_post_video_urls)) {

                    // Updating media information into user table
                    $result = $this->Mediav2_model->update_media_info($user_id, array(), $user_post_video_urls, 'insert', $current_plan);

                    if ($result) {
                        $v_response['videos'] = array('status' => true, 'message' => 'Video has been uploaded successfully');
                        // $this->response($response, parent::HTTP_OK);
                    } else {
                        // throw new Exception("Media upload failed", 1);
                        $v_response['videos'] = array('status' => false, 'message' => 'Video url update failed');
                    }

                    $response['status']     = $v_response['videos']['status'];

                    if(isset($response['message']))
                        $response['message']    .= ', '.$v_response['videos']['message'];
                    else
                        $response['message']    = $v_response['videos']['message'];

                } else {

                    if ($remaining_videos == 0) {
                        $message = "Could not upload videos, all available videos are uploaded";
                    } else {
                        $message = "Trying to upload more than available videos count, you have only " . $remaining_videos . " available.";
                    }

                    if(isset($response['status']) && $response['status']){
                        $response['status']     = true;
                        $response['message']    .= ', '.$message;
                    } else {
                        $response['status']     = false;
                        
                        if(isset($response['message']))
                            $response['message']    .= ', '.$message;
                        else
                            $response['message']    = $message;
                    }
                }
            }

            if (!empty($response)) {
                $this->response($response, parent::HTTP_OK);
            } else {
                throw new Exception("Empty media upload request", 1);
            }

        } catch (Exception $ex) {

            $message = $ex->getMessage();

            $response = array('status' => false, 'message' => $message);
            $this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update method for user's media
     * @param json post params
     * @return json  api response
     */
    public function usermedia_put()
    {
        try {
            $putParams    = $this->get_put_data();
            $a_response   = array();

            $user_id       = isset($putParams['user_id']) ? $putParams['user_id'] : 0;
            $set_dp        = isset($putParams['set_dp']) ? $putParams['set_dp'] : null;
            $media_id      = isset($putParams['media_id']) ? $putParams['media_id'] : null;
            $media         = isset($putParams['file']) ? $putParams['file'] : null;
            $media_type    = isset($putParams['media_type']) ? $putParams['media_type'] : null;
            $media_replace = isset($putParams['media_replace']) ? $putParams['media_replace'] : null;

            if ($user_id > 0) {

                // Get image count from user plan
                $a_feture_where['user_id'] = $user_id;
                $a_uf_user_features        = $this->Plans_model->get_features('', $a_feture_where, '', '', false);
                $a_user_features           = array();
                $current_plan              = isset($a_uf_user_features[0]['plan_id']) ? $a_uf_user_features[0]['plan_id'] : 1;
                foreach ($a_uf_user_features as $f_key => $f_value) {
                    $a_user_features[$f_value['setting_name']] = $f_value;
                }

                /*get user media*/
                $a_check_exists_where['user_id']    = $user_id;
                $a_check_exists_where['media_id']   = $media_id;
                $a_check_exists_where['media_type'] = $media_type; //$media_id;
                // $a_check_exists_where['media_type'] = MEDIA_TYPE_IMAGE;
                $check_exists = $this->Mediav2_model->getRow('cb_user_medias', 'media_replace', $a_check_exists_where);

                $remaining_images = 0;
                $remaining_videos = 0;

                $dp_status    	  = false;
                $image_status     = true;
                $video_status     = true;

                /*Update upload status to false it will remove upload image option from view*/
                if (!empty($check_exists) && isset($check_exists[0]) && $media_replace != null) {

                    $replace_count = isset($check_exists[0]['media_replace']) ? $check_exists[0]['media_replace'] : 1;

                    if ($media_type == MEDIA_TYPE_IMAGE) {

                        $remaining_images = $a_user_features['Images_replace']['feature_value'] - $replace_count;
                        if ($remaining_images > 0) {

                            $reaplced_media = $this->base64_to_image($user_id, $media_replace, $media);
                            
                            if($reaplced_media){

                                $result       = $this->Mediav2_model->update_media_info($media_id, array('media_name' => $reaplced_media, 'media_replace' => $replace_count), array(), 'replace', $current_plan);
                                $a_response['status']  = true;
                                $a_response['message'] = 'Image has been replaced successfully';
                            } else {
                                $a_response['status']  = false;
                                $a_response['message'] = "Can't proceed with requested media file, please upload file size under 4MB.";
                            }
                            
                        } else {

                            $image_status          = false;
                            $a_response['status']  = $image_status;
                            $a_response['message'] = 'As per your current plan, image replace limit exceeded';
                        }
                    }

                    if ($media_type == MEDIA_TYPE_VIDEO) {

                        $remaining_videos = $a_user_features['Videos_replace']['feature_value'] - $replace_count;
                        if ($remaining_videos > 0) {

                            $video_status = $this->Mediav2_model->update_media_info($media_id, array(), array('media_name' => $media_replace, 'media_replace' => $replace_count), 'replace', $current_plan);
                            // $video_status = $this->Mediav2_model->update_video_url($user_id, $media_replace, $media);
                            $a_response['status']  = true;
                            $a_response['message'] = 'Video has been replaced successfully';
                        } else {

                            $video_status          = false;
                            $a_response['status']  = $video_status;
                            $a_response['message'] = 'As per your current plan, video replace limit exceeded';
                        }
                    }

                    //Set user DP
                    $upload_path = USER_IMAGE_DIR . $user_id . '/';
                    
                    if ($set_dp) {

                        if ( !$image_status ) {

                            $dp_status             = false;
                            $a_response['status']  = false;
                            $a_response['message'] .= ' and can\'t update profile picture now!';
                        } else {

                            // update current DP
                            $dp_status             = $this->Mediav2_model->update_dp($user_id, $media_id);
                            if($dp_status){
                                $a_response['message'] .= ' and profile picture updated';
                            } else {
                                $a_response['message'] .= ' and profile picture upadte failed';
                            }
                        }
                    }

                } else {

                    $a_response['status']  = false;
                    $a_response['message'] = 'Sorry, we are unable to locate any results matching your request.';
                }

                if ($dp_status || $image_status || $video_status) {
                    // $response     = array('status'=>true, 'message'=>'User media update successful');
                    $this->response($a_response, parent::HTTP_OK);
                } else {
                    throw new Exception("Sorry, we can't procees your media request now!", 1);
                }

            } else {
                throw new Exception("Provided user id is invalid", 1);
            }

        } catch (Exception $ex) {

            $message    = $ex->getMessage();
            $a_response = array('status' => false, 'message' => $message);
            $this->response($a_response, parent::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Read method for user's media
     * @param string get params
     * @return json  api response
     */
    public function usermedia_get()
    {
        try {
            $user_id   = null;
            $offset    = null;
            $limit     = null;
            $getParams = $this->get();

            // Verify user id param exists
            if (!isset($getParams['user_id'])) {
                throw new Exception("Could not find user_id with the request", 1);
            }

            // Assign field values
            $fields = 'cbu.user_id, user_name, photos, videos';

            if (isset($getParams['user_id'])) {
                $user_id = $getParams['user_id'];
                unset($getParams['user_id']);
            }

            if (isset($getParams['offset'])) {
                $offset = $getParams['offset'];
                unset($getParams['offset']);
            }
            if (isset($getParams['limit'])) {
                $limit = $getParams['limit'];
                unset($getParams['limit']);
            }

            // Get user media details from user model
            $data = $this->Mediav2_model->get_api_media($user_id, $limit, $offset);

            if ( $data ) {
                $response = array('status' => true, 'data' => $data, 'message' => 'Media listing successful!');
                $this->response($response, parent::HTTP_OK);
            } else {
                throw new Exception("Error on get user media", 1);
            }

        } catch (Exception $ex) {

            $message = $ex->getMessage();
            $response = array('status' => false, 'message' => $message);
            $this->response($response, parent::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Convert base64 data to image file
     * @param string base64 image data
     * @param string output file name
     * @return boolean result
     */
    private function base64_to_image($user_id, $base64String, $outputFileName = null)
    {
        $outputDir = USER_IMAGE_DIR . $user_id . '/';

        // Check base64 string contain comma separation
        if ((strpos($base64String, ',') != -1) || (strpos($base64String, ';') != -1)) {
            $base64String = preg_replace('#^data:image/\w+;base64,#i', '', $base64String);
        }

        // Check file size
        $image_size = $this->getBase64ImageSize( $base64String );

        if($image_size > USER_IMAGE_MAX_UPLOAD){
            return false;
        }

        // Create output file name
        $uploaded_media = $this->create_image_name($user_id, $outputDir, $outputFileName, $base64String);
        
        // Check file creation status
        if ($uploaded_media) {
            return $uploaded_media;
        } else {
            return false;
        }

    }

    /**
     * Get image name
     * @param string base64 image data
     * @return string extension
     */
    private function create_image_name($user_id, $outputDir, $outputFileName = null, $base64String = null)
    {
        $fileCount = 0;
        $imgExt    = $this->get_image_extension($base64String);

        if(!$outputFileName){

            // Get file count of the directory
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0777, true);
            }
        }

        $outputFileName = $user_id . '_' . date('mdY_his') . '.' . $imgExt;
        // Decode base64 data to image data
        $imageData = base64_decode($base64String);

        if(@file_put_contents($outputDir . $outputFileName, $imageData)){
            return $outputFileName;
        } else {
            return false;
        }
    }

    public function getBase64ImageSize($base64Image){ //return memory size in B, KB, MB
        try{
            $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            $size_in_mb    = $size_in_kb / 1024;

            return $size_in_mb;
        }
        catch(Exception $e){
            return $e;
        }
    }

    /**
     * Get image extension
     * @param string base64 image data
     * @return string extension
     */
    private function get_image_extension($base64String)
    {
        // Default extensions for different mime types
        $mimeFilter = ['jpeg' => 'jpg', 'jpg' => 'jpg', 'png' => 'png'];

        $imgdata = base64_decode($base64String);
        $f       = finfo_open();

        // Get mime type from the image data
        $mimeType = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
        $extArr   = explode('/', $mimeType);

        return $mimeFilter[$extArr[1]];
    }

    /**
     * Get file count of given directory
     * @param string base64 image data
     * @return string extension
     */
    private function get_file_count($dirPath, $fileType = '*')
    {
        try {

            $count = 0;
            foreach (glob($dirPath . $fileType) as $file) {
                $count++;
            }

            return $count;

        } catch (Exception $ex) {

            return 0;
        }
    }

    /**
     * Get put requested data
     * @return array put data
     */
    private function get_put_data()
    {
        return json_decode(file_get_contents("php://input"), true);
    }
}
