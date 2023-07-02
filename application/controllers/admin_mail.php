<?php
class Admin_mail extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/mail';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mail_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {
		if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('content', 'Content', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {				
				$data_to_store = array(
                    'title' => $this->input->post('title'),
					'content' => $this->input->post('content'),
                );
				
				//if the insert has returned true then we show the flash message
				if($this->mail_model->update_mail($data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/mail/edit');
            }//validation run
        }
		
		$data['mail'] = $this->mail_model->get_mail();                
        //load the view
        $data['main_content'] = 'admin/mail/edit';
        $this->load->view('includes/template', $data);  

    }//index
}