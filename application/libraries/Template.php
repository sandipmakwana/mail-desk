<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Template
 *
 * Enables the user to load template
 *
 * Usage:
 *
 * Load it within your Controllers:
 * $this->load->library("Template");
 *
 * Configure CodeIgniter to Auto-Load it:
 *
 * Edit application/config/autoload.php
 * $autoload['libraries'] = array('Template');
 *
 *
 * Use it in your view files
 * $this->template->view('view', $parameters);
 * 
 */
class Template {

	var $obj;
	var $template;

	public function __construct($template = array('template' => 'template'))
	{
		$this->obj =& get_instance();
		$this->template = $template['template'];
	}

	/**
	 *
	 * set_template()
	 *
	 * Sets the template 
	 * 
	 */
	public function set_template($template)
	{ 
		$this->template = $template;
	}

	/**
	 *
	 * view()
	 *
	 * Loads the view 
	 * 
	 */
	public function view($view, $data = NULL, $return = FALSE)
	{
		if ($return)
		{
			$output = '';
			$output .= $this->obj->load->view('templates/header', $data, true);
			$output .= $this->obj->load->view($view, $data, true);
			$output .= $this->obj->load->view('templates/footer', array(), true);
			return $output;
		}
		else
		{
			$this->obj->load->view('templates/header', $data);
			$this->obj->load->view($view, $data);
			$this->obj->load->view('templates/footer', array());
		}
	}

}