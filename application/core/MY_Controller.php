<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;                  // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */

    function __construct() {
        parent::__construct();
        $this->data = array();
        $this->data['title'] = "Top Secret Government Site";    // our default title
        $this->errors = array();
        $this->data['pageTitle'] = 'welcome';   // our default page
    }
    
    //restrict access
    function restrict($roleNeeded = null) {
      $userRole = $this->session->userdata('userRole');
      
    
      if ($roleNeeded != null) {
        if (is_array($roleNeeded)) {
          if (!in_array($userRole, $roleNeeded)) { //check if user role is one of the needed roles; send away if not
            redirect("/");
            return;
          }
        } else if ($userRole != $roleNeeded) {
          redirect("/");
          return;
        }
    }
    }
    
    function makemenu() {
    // make array, with menu choice for alpha
    $menu_choices = array(
        'menudata' => array(
            array('name' => "Alpha", 'link' => '/alpha')
        )
    );
    
    //get role & name from session
    $userRole = $this->session->userdata('userRole');
    $userName = $this->session->userdata('userName');

    // if not logged in, add menu choice to login
    if($userRole == null)
        $menu_choices['menudata'][] = array('name' => "Login", 'link' => '/auth');
    // if user, add menu choice for beta and logout
    else if($userRole == 'user') {
        $menu_choices['menudata'][] = array('name' => "Beta", 'link' => '/beta');
        $menu_choices['menudata'][] = array('name' => "Logout", 'link' => '/auth/logout');
    }
    // if admin, add menu choices for beta, gamma and logout
    else if($userRole == 'admin') {
        $menu_choices['menudata'][] = array('name' => "Beta", 'link' => '/beta');
        $menu_choices['menudata'][] = array('name' => "Gamma", 'link' => '/gamma');
        $menu_choices['menudata'][] = array('name' => "Logout", 'link' => '/auth/logout');
    }
    // return the choices array
        return $this->parser->parse('_menubar', $menu_choices, true);
    }

    /**
     * Render this page
     */
    function render() {
        //$this->data['menubar'] = $this->parser->parse('_menubar', $this->config->item('menu_choices'),true);
        $this->data['menubar'] = $this->makemenu();
    
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
        $this->data['sessionid'] = session_id();

        // finally, build the browser page!
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template', $this->data);
    }

}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */