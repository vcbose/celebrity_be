    <link href="<?php echo  asset_url('admin/vendors/datatables/dataTables.bootstrap.css'); ?>" rel="stylesheet" media="screen">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo asset_url('admin/js/jquery.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/js/jquery-ui.js'); ?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo asset_url('admin/bootstrap/js/bootstrap.min.js'); ?>"></script>

    <script src="<?php echo asset_url('admin/js/jquery.validate.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/js/notify.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/js/custom-noty.js'); ?>"></script>
    <script type="text/javascript"></script>
    
         <!-- bootstrap-datetimepicker -->
    <link href="<?php echo  asset_url('admin/vendors/bootstrap-datetimepicker/datetimepicker.css'); ?>" rel="stylesheet">
    <script src="<?php echo  asset_url('admin/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js'); ?>"></script> 

    <script src="<?php echo  asset_url('admin/vendors/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo  asset_url('admin/vendors/datatables/dataTables.bootstrap.js'); ?>"></script>

    <script src="<?php echo asset_url('admin/vendors/form-helpers/js/bootstrap-formhelpers.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/vendors/select/bootstrap-select.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/vendors/tags/js/bootstrap-tags.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/vendors/mask/jquery.maskedinput.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/vendors/moment/moment.min.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/vendors/wizard/jquery.bootstrap.wizard.min.js'); ?>"></script>


    <script src="<?php echo asset_url('admin/js/custom.validate.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/js/register-js.js'); ?>"></script>

    <script src="<?php echo asset_url('admin/js/custom.js'); ?>"></script>
    <script src="<?php echo asset_url('admin/js/forms.js'); ?>"></script>
    <?php
    $msg = $this->session->flashdata('messagePr');
    if(isset($msg) && $msg != ''){
        echo '<script type="text/javascript">notification.showNotification( "'.$msg.'", "error", "bottom right");</script>';
    }   
    ?>
    </body>
</html>