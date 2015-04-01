<?php

class Auth extends Application {
  function __construct() {
    parent::__construct();
    $this->load->helper('url');
  }
  function index() {
    $this->data['pagebody'] = 'login';
    $this->render();
  }
  
  //Login submit code
    function submit() {
      //get user
      $key = $_POST['userid'];
      $user = $this->users->get($key);
      
      //Create session if password matches
      if (password_verify($this->input->post('password'), $user->password)) {
        $this->session->set_userdata('userID',$key);
        $this->session->set_userdata('userName',$user->name);
        $this->session->set_userdata('userRole',$user->role);
      }
      redirect('/');
  }
  function logout() {
    $this->session->sess_destroy();
    redirect('/');
}
}