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
        $this->load->model("users/Media_model");
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
            $user_post_image_data = isset($postParams['user_image']) ? $postParams['user_image'] : [];
            $set_dp               = isset($putParams['set_dp']) ? $putParams['set_dp'] : null;
            $user_post_video_urls = isset($postParams['user_video']) ? $postParams['user_video'] : [];
            $response             = array();
            print_r($postParams);
            die();
            
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

            /*Get user media*/
            $a_check_exists_where['user_id'] = $user_id;
            $a_check_exists_where['in_plan'] = $current_plan;
            // $a_check_exists_where['media_type'] = MEDIA_TYPE_IMAGE;
            $check_exists = $this->Media_model->get_media_count($a_check_exists_where);

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

                if ($remaining_images >= count($user_post_image_data)) {

                    foreach ($user_post_image_data as $key => $img_data) {
                        $images[] = $this->base64_to_image($user_id, $img_data);
                    }
                    if(!empty($images)){
                        // Updating media information into user table
                        $result = $this->Media_model->update_media_info($user_id, $images, array(), 'insert', $current_plan, $set_dp);
                        if ($result) {

                            $response['status']     = true;
                            $response['message']    = 'Image has been uploaded successfully';
                            // $this->response($response, parent::HTTP_OK);
                        } else {
                            throw new Exception("Can't proceed media upload action now, please try after sometimes!", 1);
                        }
                    } else {
                        throw new Exception("Can't process images upload now, please try after sometimes!", 1);
                    }
                } else {

                    if ($remaining_images == 0) {
                        $message = "Could not upload images, all available images are uploaded";
                    } else {
                        $message = "Trying to upload more than available images count, you have only " . $remaining_images . " available.";
                    }

                    $response['status'] = false;
                    $response['message'] = $message;
                }
            }

            if (is_array($user_post_video_urls) && !empty($user_post_video_urls)) {

                // Check video url count
                if ($remaining_videos >= count($user_post_video_urls)) {

                    // Updating media information into user table
                    $result = $this->Media_model->update_media_info($user_id, array(), $user_post_video_urls, 'insert', $current_plan);

                    if ($result) {

                        $response['status']     = true;
                        $message                = 'Video has been uploaded successfully';
                        if(isset($response['message'])){
                            $response['message'] .= ', '.$message;
                        } else {
                            $response['message']  = $message;
                        }

                    } else {
                        throw new Exception("Can't process video upload now, please try after sometimes!", 1);
                    }
                } else {

                    if ($remaining_videos == 0) {
                        $message = "Could not upload videos, all available videos are uploaded";
                    } else {
                        $message = "Trying to upload more than available videos count, you have only " . $remaining_videos . " available.";
                    }

                    if(isset( $response['status'] ) &&  $response['status'] ){
                        $response['status'] = true; 
                    } else {
                        $response['status'] = false;  
                    }
                    if(isset($response['message'])){
                        $response['message'] .= ', '.$message;
                    } else {
                        $response['message']    = $message;
                    }
                }
            }

            if (!empty($response)) {
                $this->response($response, parent::HTTP_OK);
            } else {
                throw new Exception("Oops! empty media upload request", 1);
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
                $check_exists = $this->Media_model->getRow('cb_user_medias', 'media_replace', $a_check_exists_where);

                $remaining_images = 0;
                $remaining_videos = 0;

                $dp_status    	  = false;
                $image_status     = true;
                $video_status     = true;

                /*Update upload status to false it will remove upload image option from view*/
                if (!empty($check_exists) && isset($check_exists[0]) && $media_replace != null) {

                    $replace_count = isset($check_exists[0]['media_replace']) ? $check_exists[0]['media_replace'] : 0;

                    if ($media_type == MEDIA_TYPE_IMAGE) {

                        $remaining_images = $a_user_features['Images_replace']['feature_value'] - $replace_count;
                        if ($remaining_images > 0) {

                            $image_status = $this->base64_to_image($user_id, $media_replace, $media);
                            $result       = $this->Media_model->update_media_info($media_id, array('media_name' => $media, 'media_replace' => $replace_count), array(), 'replace', $current_plan);

                            $a_response['status']  = true;
                            $a_response['message'] = 'Image has been replaced successfully';
                        } else {

                            $image_status          = false;
                            $a_response['status']  = $image_status;
                            $a_response['message'] = 'As per your current subscription plan, image replace limit exceeded';
                        }
                    }

                    if ($media_type == MEDIA_TYPE_VIDEO) {

                        $remaining_videos = $a_user_features['Videos_replace']['feature_value'] - $replace_count;
                        if ($remaining_videos > 0) {

                            $video_status = $this->Media_model->update_media_info($media_id, array(), array('media_name' => $media_replace, 'media_replace' => $replace_count), 'replace', $current_plan);
                            // $video_status = $this->Media_model->update_video_url($user_id, $media_replace, $media);
                            $a_response['status']  = true;
                            $a_response['message'] = 'Video has been replaced successfully';
                        } else {

                            $video_status          = false;
                            $a_response['status']  = $video_status;
                            $a_response['message'] = 'As per your current subscription plan, video replace limit exceeded';
                        }
                    }
                } else {

                    $a_response['status']  = false;
                    $a_response['message'] = 'No media found to replace!';
                }

                //Set user DP
                $upload_path = USER_IMAGE_DIR . $user_id . '/';
                if ($set_dp) {

                    /*if (!file_exists($upload_path . $media)) {
                        throw new Exception("Provided image " . $media . " not exists", 1);
                    }*/
                    if ( !$image_status ) {

                        $dp_status             = false;
                        $a_response['status']  = false;
                        $a_response['message'] = 'Can\'t update profile picture now!';
                    } else {

                        // update current DP
                        $dp_status             = $this->Media_model->update_dp($user_id, $media_id);
                        $a_response['status']  = true;
                        $a_response['message'] = 'Your profile picture updated';
                    }
                }

                if ($dp_status || $image_status || $video_status) {
                    // $response     = array('status'=>true, 'message'=>'User media update successful');
                    $this->response($a_response, parent::HTTP_OK);
                } else {
                    throw new Exception("User media update failed", 1);
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
            $data = $this->Media_model->get_api_media($user_id, $limit, $offset);
            if ($data) {
                $response = array('status' => true, 'data' => $data);
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

        // Create output file name
        if (!$outputFileName) {
            $outputFileName = $this->create_image_name($user_id, $outputDir, $base64String);
        }

        // Decode base64 data to image data
        $imageData = base64_decode($base64String);

        // Create file with base64 data
        $result = @file_put_contents($outputDir . $outputFileName, $imageData);

        // Check file creation status
        if ($result) {
            return $outputFileName;
        } else {
            return false;
        }

    }

    /**
     * Get image name
     * @param string base64 image data
     * @return string extension
     */
    private function create_image_name($user_id, $outputDir, $base64String)
    {
        $fileCount = 0;
        $imgExt    = $this->get_image_extension($base64String);

        // Get file count of the directory
        if (file_exists($outputDir)) {
            $fileCount = $this->get_file_count($outputDir);

        } else {
            mkdir($outputDir, 0777, true);
        }

        $outputFileName = $user_id . '_' . ++$fileCount . '.' . $imgExt;

        return $outputFileName;
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
