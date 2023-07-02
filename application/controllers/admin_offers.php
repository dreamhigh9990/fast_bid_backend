<?php
class Admin_offers extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/offers';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('offers_model');
        $this->load->model('categories_model');
        $this->load->model('buys_model');

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

        //pagination settings
        $config['per_page'] = 15;

        $config['base_url'] = base_url().'admin/offers';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $stores_id = $this->uri->segment(3);
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
            $data['count_products']= $this->offers_model->count_offers_by_stores_id($stores_id, $search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['offers'] = $this->offers_model->get_offers_by_stores_id($stores_id, $search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['offers'] = $this->offers_model->get_offers_by_stores_id($stores_id, $search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['offers'] = $this->offers_model->get_offers_by_stores_id($stores_id, '', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['offers'] = $this->offers_model->get_offers_by_stores_id($stores_id, '', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }else{

            //clean filter data inside section
            $filter_session_data['stores_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_products']= $this->offers_model->count_offers_by_stores_id($stores_id);
            $data['offers'] = $this->offers_model->get_offers_by_stores_id($stores_id, '', '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($search_string) && !isset($order)
         
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/offers/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //load categories
        $data['categories'] = $this->categories_model->get_categories();
        $data['sub_categories'] = $this->categories_model->get_sub_categories_by_categories_id($data['categories'][0]['categories_id']);

        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('price', 'Price', 'required');
            $this->form_validation->set_rules('available_date', 'Offer Time', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');

            if (empty($_FILES['photo']['name']))
            {
                $this->form_validation->set_rules('photo', 'Image', 'required');
            }
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {
				if (isset($_FILES['photo'])) 
				{
					$config['upload_path']	= '../offers/upload/offers/photo/';
					$config['overwrite']		= FALSE;
					$config['allowed_types']	= '*';
					$config['max_size']	= '50000';			
					$this->load->library('upload', $config);
					
					if (!$this->upload->do_upload("photo")) {
						$upload = FALSE;
					} else {
						$attache = $this->upload->data();
						$upload = TRUE;
					}
				}
                $data_to_store = array(
                    'stores_id' => $this->uri->segment(4),
                    'name' => $this->input->post('name'),
					'price' => $this->input->post('price'),
                    'available_date' => date('Y-m-d', strtotime($this->input->post('available_date'))),
                    'quantity' => $this->input->post('quantity'),
                    'description' => $this->input->post('description'),
					'photo' => $attache["file_name"],
                    'categories_id'=> $this->input->post('sub_categories_id'),
                );
				
				if ($upload) {
					//if the insert has returned true then we show the flash message
					if($this->offers_model->store_offers($data_to_store)){
						$data['flash_message'] = TRUE; 
					}else{
						$data['flash_message'] = FALSE; 
					}
				} else {
					$data['flash_message'] = FALSE; 
				}
            } else {
                $data['flash_message'] = FALSE; 
            }
        }
        //load the view
        $data['main_content'] = 'admin/offers/add';
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
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');
            $this->form_validation->set_rules('available_date', 'Offer Time', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {    
                if (isset($_FILES['photo'])) 
                {
                    $config['upload_path']  = '../offers/upload/offers/photo/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload("photo")) {
                        $upload = FALSE;
                    } else {
                        $attache = $this->upload->data();
                        $upload = TRUE;
                    }
                }
                $data_to_store = array(
                    'name' => $this->input->post('name'),
                    'price' => $this->input->post('price'),
                    'available_date' => date('Y-m-d', strtotime($this->input->post('available_date'))),
                    'quantity' => $this->input->post('quantity'),
                    'description' => $this->input->post('description'),
                    'categories_id'=> $this->input->post('sub_categories_id'),
                );

                if ($upload == TRUE)
                    $data_to_store['photo'] = $attache["file_name"];

				//if the insert has returned true then we show the flash message
				if($this->offers_model->update_offers($id, $data_to_store) == TRUE){
					//$this->session->set_flashdata('flash_message', 'updated');
                    $data['flash_message'] = 'updated'; 
				}else{
					//$this->session->set_flashdata('flash_message', 'not_updated');
                    $data['flash_message'] = 'not_updated'; 
				}
				//redirect('admin/offers/update/'.$id.'');
            } else {
                $data['flash_message'] = FALSE; 
            }
        }

        //if we are updating, and the data did not pass through the validation
        //the code below wel reload the current data
        $data['categories'] = $this->categories_model->get_categories();
    
        //product data 
        $data['offers'] = $this->offers_model->get_offers_by_id($id);

        // get parent category
        $data['offers'][0]['parent_categories_id'] = $this->categories_model->get_parent_category($data['offers'][0]['categories_id']);

        // get sub categories
        $data['sub_categories'] = $this->categories_model->get_sub_categories_by_categories_id($data['offers'][0]['parent_categories_id']);

        // get bought users
        $data['bought_users'] = $this->buys_model->get_users_by_offers_id($id, 3);

        // get pending users
        $data['pending_users'] = $this->buys_model->get_users_by_offers_id($id, 2);

        //load the view
        $data['main_content'] = 'admin/offers/edit';
        $this->load->view('includes/template', $data);
    }//update

    public function complete()
    {
        $id = $this->uri->segment(4);
        $buys_id = $this->uri->segment(5);
        $quantity = $this->uri->segment(6);

        $this->buys_model->buy_offers($buys_id, $quantity);

        //if we are updating, and the data did not pass through the validation
        //the code below wel reload the current data
        $data['categories'] = $this->categories_model->get_categories();
    
        //product data 
        $data['offers'] = $this->offers_model->get_offers_by_id($id);

        // get parent category
        $data['offers'][0]['parent_categories_id'] = $this->categories_model->get_parent_category($data['offers'][0]['categories_id']);

        // get sub categories
        $data['sub_categories'] = $this->categories_model->get_sub_categories_by_categories_id($data['offers'][0]['parent_categories_id']);

        // get bought users
        $data['bought_users'] = $this->buys_model->get_users_by_offers_id($id, 3);

        // get pending users
        $data['pending_users'] = $this->buys_model->get_users_by_offers_id($id, 2);

        //load the view
        $data['main_content'] = 'admin/offers/edit';
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
        $this->offers_model->delete_offers($id);
        redirect('admin/offers/'.$this->uri->segment(5));
    }//edit

    public function get_sub_categories()
    {
        $categories_id = $this->input->get('categories_id');
        $sub_categories = $this->categories_model->get_sub_categories_by_categories_id($categories_id);
        header('Content-Type: application/json');
        echo json_encode($sub_categories);
    }
}
?>