<!-- Page Footer-->
<footer class="main-footer">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6">
      <p>Celebrity Be &copy; 2017-2019</p>
    </div>
    <div class="col-sm-6 text-right">
      <p>Design by <a href="#" class="external">Hands on technologies</a></p>
      <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
    </div>
  </div>
</div>
</footer>
</div>
</div>
</div>
<!-- Javascript files-->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="<?php echo asset_url('website/vendor/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo asset_url('website/vendor/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script src="<?php echo asset_url('website/vendor/jquery.cookie/jquery.cookie.js'); ?>"> </script>
<script src="<?php echo asset_url('website/js/owl.carousel.js'); ?>"></script>
<!-- Main File-->
<script src="<?php echo asset_url('website/js/front.js'); ?>"></script>
<script>
$(document).ready(function() {
$("#owl-demo").owlCarousel({
        items : 5,
        lazyLoad : true,
        autoPlay : true,
        pagination : true,
        nav:true,
    });
});
</script>
</body>
</html>