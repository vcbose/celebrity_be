<body class="login-bg">
<div class="header">
    <div class="container">
        <div class="row">
           <div class="col-md-12">
              <!-- Logo -->
              <div class="logo">
                 <h1><a href="index.html">Be Celebrity !</a></h1>
              </div>
           </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row">

        <?php include "templates/side-bar.php";?>

        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12 panel-info">
                    <div class="content-box-header panel-heading">
                        <div class="panel-title ">Profile Detail &raquo; Media<?php //echo $userdata->display_name; ?></div>

                        <div class="panel-options">
                            <!-- <a href="/profile-detail/" data-rel="collapse"><i class="glyphicon glyphicon-refresh"></i></a> -->
                            <a href="#" data-rel="reload"><i class="glyphicon glyphicon-cog"></i></a>
                        </div>
                    </div>

                    <div class="content-box-large box-with-header">

                        <div>
                            <h4>&raquo; Mange user medias</h4>
                            <hr>

                            <?php echo form_open_multipart('uploads', 'role="studentForm" class="form-horizontal"'); ?>
                                <fieldset>
                                <!-- <form action="<?php echo site_url(); ?>uploads" id="media_form" method="post" enctype="multipart/form-data"> -->
                                <?php
                                if (!empty($features)) {
                                    ?>
                                    <div class="form-group">

                                        <div class="col-md-6" id="plan_div">
                                            <label>Plan <span class="mandatory">*</span></label>
                                            <select name="plan" id="plan" class="form-control custom-scroll">
                                            <option value="">Selct Plan</option>
                                            <?php
                                            if (!empty($plans)) {
                                                foreach ($plans as $p_key => $p_value) {
                                                    $selected_plan = ($p_key == $features['Images']['plan_id']) ? 'selected' : '';
                                                    echo "<option " . $selected_plan . " value=" . $p_key . ">" . ucwords($p_value) . "</option>";
                                                }
                                            }
                                            ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <?php if ($image_status) {?>
                                            <label class="control-label">File input</label>
                                                <input type="file" class="btn btn-default" id="photos" name="photos[]" multiple="multiple" >
                                                <p class="help-block">
                                                    Upload photos.
                                                </p>
                                            <?php } else {
                                                echo '<br><p class="help-block" style="color: red;"> ***As per current user plan your Image upload limit exceeded!</p>';
                                            }?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="user-videos">Embedded Videos</label>
                                            <?php if ($video_status) {?>
                                            <div id=video_add>
                                                <div class="video_url" style="margin-bottom: 10px;">
                                                    <input type="text" name="videos[]" id="user-videos" class="form-control" placeholder="https://youtu.be/YicuKTFPxX0">
                                                </div>
                                            </div>
                                                <?php if ($features['Videos']['feature_value'] > 1) {?>
                                                <a href="javascript:void(0);" id="add_more">Add More</a>
                                                <?php }?>
                                            <?php } else {
                                                echo '<br><p class="help-block" style="color: red;"> ***As per current user plan your Video upload limit exceeded!</p>';
                                            }?>
                                        </div>

                                    </div>
                                <hr>
                                <h4>&raquo; Photo Gallery</h4>
                                <hr>
                                <div class="form-group">
                                    <div class="col-md-12" id="image-preview">
                                        <?php
                                        if (!empty($list)):
                                            $il = 1;
                                            foreach ($list as $key => $value) {
                                                if ($value['media_type'] == MEDIA_TYPE_IMAGE) {

                                                    $img     = 'assets/uploads/' . $userdata['user_id'] . '/' . $value['media_name'];
                                                    $img_src = site_url() . $img;

                                                    if (file_exists($img)) {
                                                        $dp_checked = ($value['dp'])?'checked="checked"':'';
                                                        echo '<div class="col-md-3">
                                                            <div class="thumbnail">
                                                            <a href="' . $img_src . '">';
                                                        echo '<img src="' . $img_src . '" style="height: 150px;" alt="Cinque Terre">';
                                                        echo '</a>
                                                            <div class="">
                                                            <p class="help-block">
                                                                 &raquo; Replce this photo.
                                                            </p>
                                                            <input type="file" class="btn btn-default" style="width: 100%; margin-top: 2%;" id="photos" name="replace[' . $value['media_id'] . ']" multiple="multiple" >';
                                                        echo '<label for="dp-'.$il.'"><input type="radio" '.$dp_checked.' name="dp['.$value['media_id'].']" id="dp-'.$il.'" value="' . $img_src . '">  Make it us profile pic </label>';
                                                        echo '</div>
                                                            </div>
                                                        </div>';
                                                        $il++;
                                                    }
                                                }
                                            } else :
                                            echo '<p> No Image gallery found !</p>';
                                        endif;
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <h4>&raquo; Video Gallery</h4>
                                <hr>
                                <div class="form-group">
                                    <?php
                                    if (!empty($list)):
                                        foreach ($list as $key => $value) {
                                            if ($value['media_type'] == MEDIA_TYPE_VIDEO) {
                                                ?>
                                                                                <div class="col-md-6" id="image-preview">
                                                                                    <div id=video_add>
                                                                                        <div class="video_url" style="margin-bottom: 10px;">
                                                                                            <input type="text" name="replace_videos[]" class="form-control" value="<?php echo $value['media_name']; ?>" >
                                                                                        </div>
                                                                                        <a href="<?php echo $value['media_name']; ?>" target="_blnk">Watch in YouTube</a>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                    }
                                        } else :
                                        echo '<div class="col-md-6" id="image-preview">';
                                        echo '<p> No video gallery found !</p>';
                                        echo '</div>';
                                    endif;
                                    ?>
                                </div>
                                <hr>
                                <div>
                                    <div>
                                        <i class="fa fa-save"></i>
                                        <!-- <button class="btn btn-primary" id="save-reg">Submit</button> -->
                                        <input type="submit" name="register_submit" class="btn btn-info" id="save-reg" value="Upload">
                                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $userdata['user_id']; ?>">

                                        <input type="hidden" name="image_count" id="image_count" value="<?php echo $features['Images']['feature_value']; ?>">
                                        <input type="hidden" name="remain_image_count" id="remain_image_count" value="<?php echo $remaining_images; ?>">
                                        <input type="hidden" name="video_count" id="video_count" value="<?php echo $features['Videos']['feature_value']; ?>">
                                        <input type="hidden" name="remain_video_count" id="remain_video_count" value="<?php echo $remaining_videos; ?>">
                                        <input type="hidden" name="current_plan" id="current_plan" value="<?php echo $current_plan; ?>">
                                    </div>
                                </div>

                                <hr>
                            <?php } else {?>
                            <div>
                                <h2> You do't have an access to this system
                                </h2>
                            </div>
                            <?php }?>
                            </fieldset>
                        </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

