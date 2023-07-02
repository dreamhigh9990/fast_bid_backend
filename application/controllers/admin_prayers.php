<?php
class Admin_prayers extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/prayers';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('prayers_model');
        $this->load->model('categories_model');

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

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 
        $category = $this->uri->segment(3);

        //pagination settings
        $config['per_page'] = 15;

        $config['base_url'] = base_url().'admin/prayers';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(4);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session? 
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');    
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';    
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        


        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        $this->prayers_model->select_category($category);
        if($search_string !== false && $order !== false || $this->uri->segment(3) == true){ 
           
            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected       
            */
            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            if(isset($filter_session_data)){
              $this->session->set_userdata($filter_session_data);    
            }
            
            //fetch sql data into arrays
            $data['count_products']= $this->prayers_model->count_prayers($search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['prayers'] = $this->prayers_model->get_prayers($search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['prayers'] = $this->prayers_model->get_prayers($search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['prayers'] = $this->prayers_model->get_prayers('', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['prayers'] = $this->prayers_model->get_prayers('', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }else{

            //clean filter data inside section
            $filter_session_data['prayers_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_products']= $this->prayers_model->count_prayers();
            $data['prayers'] = $this->prayers_model->get_prayers('', '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($search_string) && !isset($order)
         
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        // //load owner data
        // foreach ($data['prayers'] as $key => $prayer) {
        //     $data['prayers'][$key]['owner_data'] = $this->users_model->get_users_by_id($prayer['owner_id']);
        // }

        //load the view
        $data['category'] = $category;
        $data['category_name'] = $this->categories_model->getNameById($category);
        $data['main_content'] = 'admin/prayers/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('prayer_name', 'Prayer Name', 'required');
			$this->form_validation->set_rules('content_english', 'Content(English)', 'required');
            $this->form_validation->set_rules('content_transliterated', 'Content(Transliterated)', 'required');
            $this->form_validation->set_rules('content_hebrew', 'Content(Hebrew)', 'required');
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {
				$image_url_customer = "";
                $image_url_deliverer = "";
                $data_to_store = array(
                    'name' => $this->input->post('prayer_name'),
					'content_english' => $this->input->post('content_english'),
                    'content_transliterated' => $this->input->post('content_transliterated'),
                    'content_hebrew' => $this->input->post('content_hebrew'),
                    'published' => $this->input->post('published'),
                );
				
				//if the insert has returned true then we show the flash message
                $this->prayers_model->select_category($this->uri->segment(4));
				if($this->prayers_model->store_prayers($data_to_store)){
					$data['flash_message'] = TRUE; 
				}else{
					$data['flash_message'] = FALSE; 
				}
                //redirect('admin/stores/update/'.$id.'');
            }

        }
        //load the view
        $data['category'] = $this->uri->segment(4);
        $data['category_name'] = $this->categories_model->getNameById($data['category']);
        $data['main_content'] = 'admin/prayers/add';
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
            $this->form_validation->set_rules('prayer_name', 'Prayer Name', 'required');
            $this->form_validation->set_rules('content_english', 'Content(English)', 'required');
            $this->form_validation->set_rules('content_transliterated', 'Content(Transliterated)', 'required');
            $this->form_validation->set_rules('content_hebrew', 'Content(Hebrew)', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {    
                $data_to_store = array(
                    'name' => $this->input->post('prayer_name'),
                    'content_english' => $this->input->post('content_english'),
                    'content_transliterated' => $this->input->post('content_transliterated'),
                    'content_hebrew' => $this->input->post('content_hebrew'),
                    'published' => $this->input->post('published'),
                );
                
				//if the insert has returned true then we show the flash message
				if($this->prayers_model->update_prayers($id, $data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/prayers/update/'.$id.'');
            }//validation run
        }

        //if we are updating, and the data did not pass through the validation
        //the code below wel reload the current data

        //product data 
        $data['prayers'] = $this->prayers_model->get_prayers_by_id($id);
        $data['category'] = $data['prayers'][0]['category'];
        $data['category_name'] = $this->categories_model->getNameById($data['category']);
        //load the view
        $data['main_content'] = 'admin/prayers/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(4);
        $this->prayers_model->delete_prayers($id);
        redirect('admin/prayers/'.$_GET['category']);
    }//edit

}
?>