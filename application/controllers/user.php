<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User controller.
 */
class User extends CI_Controller {
  
  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();
    // Load form and form validation.
    $this->load->helper('form');
    $this->load->library('form_validation');
  }
  
  /**
   * Login form.
   * Route:
   * /login
   */
	public function user_login() {	  
    if (is_logged()) {
      die('The user is already logged. Redirect to the profile page.');
    }

    $this->form_validation->set_rules('signin_username', 'Username', 'trim|required|xss_clean');
    $this->form_validation->set_rules('signin_password', 'Password', 'trim|required|xss_clean|callback__check_login_data');

    if ($this->form_validation->run() == FALSE) {      
  		$this->load->view('base/html_start');
      $this->load->view('navigation');
      $this->load->view('login');
      $this->load->view('base/html_end');
    }
    else {
      // Redirect to home page.
      redirect();
    }
	}
  
  /**
   * Logout.
   * Route:
   * /logout
   */
  public function user_logout() {
    $this->session->sess_destroy();
    redirect('login');
  }
  
  /**
   * Logout.
   * Route:
   * /user
   */
  public function user_profile($uid = null) {
    if (is_logged()) {
      $this->load->view('base/html_start');
      $this->load->view('navigation');
      $this->load->view('users/user_profile', array('user' => current_user()));
      $this->load->view('base/html_end');
    }
    else {
      redirect('login');
    }
  }
  
  /**
   * Logout.
   * Route:
   * /user/(:num)/edit
   */
  public function user_edit_by_id($uid) {
    if (is_logged()) {
      $user = $this->user_model->get($uid);
      
      if (!$user) {
        show_404();
      }
      
      //if (user is admin) {
      if (FALSE) {
        // Admin can edit everything.
        
      }
      elseif (current_user()->uid == $user->uid) {
        // Editing own account.
        $this->_edit_own_account();
      }
      else {
        // Editing other user account.
        // Only admins can do that.
        show_error("You're not allowed to edit other user's accounts.", 403, 'Operation not allowed');
      }
    }
    else {
      redirect('login');
    }
  }
  
  /**
   * Used by user_edit_by_id
   * When non admin user is attemping to edit own account.
   */
  protected function _edit_own_account() {
    $this->form_validation->set_rules('user_name', 'Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('user_password', 'Password', 'trim|required|xss_clean|callback__check_user_password');
    $this->form_validation->set_rules('user_new_password', 'New Password', 'trim');
    $this->form_validation->set_rules('user_new_password_confirm', 'New Password Confirm', 'trim|callback__check_confirm_password');
    
    $user = current_user();
    
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('base/html_start');
      $this->load->view('navigation');
      $this->load->view('users/user_form_edit_self', array('user' => $user));
      $this->load->view('base/html_end');
    }
    else {
      $user->name = $this->input->post('user_name');
      $user->set_password($this->input->post('user_new_password'));
      
      $this->user_model->save($user);
      // TODO: Saving own profile. Handle success, error.
      redirect('user');
    }
  }
  
  /**
   * Recover password.
   * A link will be sent to the email with the recover data.
   * 
   * Route:
   * /user/recover
   */
  public function user_recover_password() {
    $this->form_validation->set_rules('user_email', 'Email', 'trim|required|xss_clean|valid_email|callback__check_email_exists');
    
    $user = current_user();
    
    if ($this->form_validation->run() == FALSE) {
      $this->load->view('base/html_start');
      $this->load->view('navigation');
      $this->load->view('users/user_recover_password');
      $this->load->view('base/html_end');
    }
    else {
      $this->load->model('recover_password_model');
      $email = $this->input->post('user_email');
      
      $hash = $this->recover_password_model->generate($email);
      
      if ($hash) {
        $this->load->library('email');
        // TODO: Email data. Use settings as much as possible.
        $this->email->from('aw-datacollection@airwolf.edispilf.org', 'Aw-datacollection Admin');
        $this->email->to('daniel.silva@flipside.org');
        
        $this->email->subject('Password Recover');
        $this->email->message('Use the following link. ' . base_url('user/reset_password/' . $hash));
        
        $this->email->send();
        // TODO: Message user. Check your email.
        redirect('login');
      }
      else {
        show_error("An error occurred while generating hash to recover password. Try again later.");
      }
      
    }
  }

  /**
   * Form to reset password. Only accessible through url sent to email.
   * 
   * Route:
   * /user/reset_password
   */
  public function user_reset_password($hash) {
    $this->load->model('recover_password_model');
    $user_email = $this->recover_password_model->validate($hash);
    
    if ($user_email) {
      $this->form_validation->set_rules('user_new_password', 'New Password', 'trim|required');
      $this->form_validation->set_rules('user_new_password_confirm', 'New Password Confirm', 'trim|required|callback__check_confirm_password');
      
      if ($this->form_validation->run() == FALSE) {
        $this->load->view('base/html_start');
        $this->load->view('navigation');
        $this->load->view('users/user_reset_password');
        $this->load->view('base/html_end');
      }
      else {
        $user = $this->user_model->get_by_email($user_email);
        
        if ($user) {
          $user->set_password($this->input->post('user_new_password'));
          
          if ($this->user_model->save($user)) {
            $this->recover_password_model->invalidate($hash);
            // TODO: Message user. Login with your new password.
            redirect('login');
          }
          else {
            show_error("Error saving your new password. Try again later.");
          }
          
        }
        else {
          // This could happen if the email stored with the hash doesn't return a user.
          // Maybe the user was deleted before the link was clicked?
          // During normal usage this is improbable.
          show_error("An error occurred while getting user from the hash. Try again later.");
        }
        
      }
    }
    else {
      // Hash expired.
      show_error('Sorry, this link is no longer valid.', 404);
    }
  }
  
  /**
   * Checks if the login data is valid.
   * Form validation callback.
   */
  public function _check_login_data($password) {
    // Username.
    $username = $this->input->post('signin_username');
    // Get user.
    $user = $this->user_model->get_by_username($username);
    
    if ($user && $user->check_password($password)) {
      // Set session data here since we already loaded the user.
      $data = array(
        'is_logged' => TRUE,
        'user_uid' => $user->uid
      );
      $this->session->set_userdata($data);
      return TRUE;
    }
    else {
      $this->form_validation->set_message('_check_login_data', 'Invalid username or password.');
      return FALSE;
    }
  }

  /**
   * Checks if the password matches the logged user's
   * Form validation callback.
   */
  public function _check_user_password($password) {
    if (current_user()->check_password($password)) {
      return TRUE;
    }
    else {
      $this->form_validation->set_message('_check_user_password', 'The current password is not correct.');
      return FALSE;
    }
  }

  /**
   * Checks if the new password and new password confirm match.
   * Form validation callback.
   */
  public function _check_confirm_password($new_password_confirm) {
    $new_password = $this->input->post('user_new_password');
    
    if ($new_password == $new_password_confirm) {
      return TRUE;
    }
    else {
      $this->form_validation->set_message('_check_confirm_password', 'The New Password Confirmation does not match.');
      return FALSE;
    }
  }

  /**
   * Checks if the user with the given email exists.
   * Used for password recovery
   * Form validation callback.
   */
  public function _check_email_exists($email) {
    $user = $this->user_model->get_by_email($email);
    
    if ($user !== FALSE) {
      return TRUE;
    }
    else {
      $this->form_validation->set_message('_check_email_exists', 'The is no user with the given email.');
      return FALSE;
    }
  }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */