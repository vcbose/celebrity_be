<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	public function admin_template($template_name, $template_sufix = '', $vars = array(), $return = FALSE)
    {
        if($return):
	        $content  = $this->view(APPPATH.'views/templates/'.$template_sufix.'header', $vars, $return);
	        $content .= $this->view($template_name, $vars, $return);
	        $content .= $this->view(APPPATH.'views/templates/'.$template_sufix.'footer', $vars, $return);

	        return $content;
	    else:
	        $this->view('templates/'.$template_sufix.'header', $vars);
	        $this->view($template_name, $vars);
	        $this->view('templates/'.$template_sufix.'footer', $vars);
	    endif;
    }
}