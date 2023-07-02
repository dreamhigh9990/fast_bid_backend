<?php
class Admin_isentences extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/isentences';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('courses_model');
        $this->load->model('chapters_model');
        $this->load->model('books_model');
        $this->load->model('sentences_model');

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
        //pagination settings
        $config['per_page'] = 15;

        $config['base_url'] = base_url().'admin/isentences';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        
        $config['total_rows'] = $this->sentences_model->count_isentences();
        $data['isentences'] = $this->sentences_model->get_isentences();

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/isentences/list';
        $this->load->view('includes/template', $data);
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $post_data = $this->input->post();
            $post_data['s_en'] = trim($post_data['s_en']);
            $post_data['s_zh-CN'] = trim($post_data['s_zh-CN']);
            $post_data['s_de'] = trim($post_data['s_de']);
            $post_data['d_en'] = trim($post_data['d_en']);
            $post_data['d_zh-CN'] = trim($post_data['d_zh-CN']);
            $post_data['d_de'] = trim($post_data['d_de']);
            
            if ($post_data['s_zh-CN'] == "" && $post_data['s_de'] == "")
                $this->form_validation->set_rules('s_en', 'Source English Sentence', 'required');
            if ($post_data['d_zh-CN'] == "" && $post_data['d_de'] == "")
                $this->form_validation->set_rules('d_en', 'Target English Sentence', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
            $upload = FALSE;
            if ($this->form_validation->run())
            {
                if ($post_data['s_en'] != "")
                    $s_language = "en";
                else if ($post_data['s_zh-CN'] != "")
                    $s_language = "zh-CN";
                else if ($post_data['s_de'] != "")
                    $s_language = "de";

                if ($post_data['d_en'] != "")
                    $d_language = "en";
                else if ($post_data['d_zh-CN'] != "")
                    $d_language = "zh-CN";
                else if ($post_data['d_de'] != "")
                    $d_language = "de";

                $data_for_isentences = array(
                    's_en' => $this->input->post('s_en'),
                    's_zh-CN' => $this->input->post('s_zh-CN'),
                    's_de' => $this->input->post('s_de'),
                    'd_en' => $this->input->post('d_en'),
                    'd_zh-CN' => $this->input->post('d_zh-CN'),
                    'd_de' => $this->input->post('d_de'),
                    's_language' => $s_language,
                    'd_language' => $d_language,
                    'chapters_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                );

                if (isset($_FILES['image'])) 
                {
                    $config['upload_path']  = './upload/sentences/image/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';      
                    $config['encrypt_name'] = TRUE;    
                    $this->load->library('upload', $config, 'isentences_image');
                    
                    if (!$this->isentences_image->do_upload("image")) {
                        $upload = FALSE;
                    } else {
                        $attache_photo = $this->isentences_image->data();
                        $upload = TRUE;
                    }
                }
                
                if ($upload == TRUE)
                    $data_for_isentences['image'] = $attache_photo["file_name"];

                $upload = FALSE;

                if (isset($_FILES['audio'])) 
                {
                    $config['upload_path']  = './upload/sentences/audio/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';   
                    $config['encrypt_name'] = TRUE;       
                    $this->load->library('upload', $config, 'isentences_audio');
                    
                    if (!$this->isentences_audio->do_upload("audio")) {
                        $upload = FALSE;
                    } else {
                        $attache_audio = $this->isentences_audio->data();
                        $upload = TRUE;
                    }
                }
                
                if ($upload == TRUE)
                    $data_for_isentences['audio'] = $attache_audio["file_name"];

                //if the insert has returned true then we show the flash message
                $last_inserted_id = $this->sentences_model->store_sentences($data_for_isentences);
                if($last_inserted_id >= 0){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }
            } else {
                $data['flash_message'] = FALSE;
            }
        }
        // load the view
        $data['main_content'] = 'admin/isentences/add';
        $this->load->view('includes/template', $data);  
    }

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(4);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $post_data = $this->input->post();
            $post_data['s_en'] = trim($post_data['s_en']);
            $post_data['s_zh-CN'] = trim($post_data['s_zh-CN']);
            $post_data['s_de'] = trim($post_data['s_de']);
            $post_data['d_en'] = trim($post_data['d_en']);
            $post_data['d_zh-CN'] = trim($post_data['d_zh-CN']);
            $post_data['d_de'] = trim($post_data['d_de']);
            
            if ($post_data['s_zh-CN'] == "" && $post_data['s_de'] == "")
                $this->form_validation->set_rules('s_en', 'Source English Sentence', 'required');
            if ($post_data['d_zh-CN'] == "" && $post_data['d_de'] == "")
                $this->form_validation->set_rules('d_en', 'Target English Sentence', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {    
                $upload = FALSE;
                if ($post_data['s_en'] != "")
                    $s_language = "en";
                else if ($post_data['s_zh-CN'] != "")
                    $s_language = "zh-CN";
                else if ($post_data['s_de'] != "")
                    $s_language = "de";

                if ($post_data['d_en'] != "")
                    $d_language = "en";
                else if ($post_data['d_zh-CN'] != "")
                    $d_language = "zh-CN";
                else if ($post_data['d_de'] != "")
                    $d_language = "de";

                $data_for_isentences = array(
                    's_en' => $this->input->post('s_en'),
                    's_zh-CN' => $this->input->post('s_zh-CN'),
                    's_de' => $this->input->post('s_de'),
                    'd_en' => $this->input->post('d_en'),
                    'd_zh-CN' => $this->input->post('d_zh-CN'),
                    'd_de' => $this->input->post('d_de'),
                    's_language' => $s_language,
                    'd_language' => $d_language,
                    'chapters_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                );

                if (isset($_FILES['image'])) 
                {
                    $config['upload_path']  = './upload/sentences/image/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $config['encrypt_name'] = TRUE;

                    $this->load->library('upload', $config, 'isentences_image');
                    
                    if (!$this->isentences_image->do_upload("image")) {
                        $upload = FALSE;
                    } else {
                        $attache_photo = $this->isentences_image->data();
                        $upload = TRUE;
                    }
                }
                
                if ($upload == TRUE)
                    $data_for_isentences['image'] = $attache_photo["file_name"];

                $upload = FALSE;

                if (isset($_FILES['audio'])) 
                {
                    $config['upload_path']  = './upload/sentences/audio/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';  
                    $config['encrypt_name'] = TRUE;        
                    $this->load->library('upload', $config, 'isentences_audio');
                    
                    if (!$this->isentences_audio->do_upload("audio")) {
                        $upload = FALSE;
                    } else {
                        $attache_audio = $this->isentences_audio->data();
                        $upload = TRUE;
                    }
                }
                
                if ($upload == TRUE)
                    $data_for_isentences['audio'] = $attache_audio["file_name"];

                
                //if the insert has returned true then we show the flash message
                if($this->sentences_model->update_sentences($id, $data_for_isentences) == TRUE){
                    $this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/isentences/update/'.$id.'');
            } else {
                $data['flash_message'] = FALSE;
            }
        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data
        $data['isentences'] = $this->sentences_model->get_sentences_by_id($id);

        //load the view
        $data['main_content'] = 'admin/isentences/edit';
        $this->load->view('includes/template', $data);            

    }

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(4);
        $this->sentences_model->delete_sentences($id);
        redirect('admin/isentences');
    }
}
?>