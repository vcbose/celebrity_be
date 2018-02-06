<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	public function admin_template($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
	        $content  = $this->view('../views/templates/header', $vars, $return);
	        $content .= $this->view($template_name, $vars, $return);
	        $content .= $this->view('../views/templates/footer', $vars, $return);

	        return $content;
	    else:
	        $this->view('/templates/header', $vars);
	        $this->view($template_name, $vars);
	        $this->view('/templates/footer', $vars);
	    endif;
    }
}