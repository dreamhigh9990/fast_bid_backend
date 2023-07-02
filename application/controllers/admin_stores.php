<?php
class Admin_stores extends CI_Controller {

    /**
    * name of the folder responsible for the views 
    * which are manipulated by this controller
    * @constant string
    */
    const VIEW_FOLDER = 'admin/stores';
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('stores_model');

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

        $config['base_url'] = base_url().'admin/stores';
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
            $data['count_products']= $this->stores_model->count_stores($search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['stores'] = $this->stores_model->get_stores($search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['stores'] = $this->stores_model->get_stores($search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['stores'] = $this->stores_model->get_stores('', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['stores'] = $this->stores_model->get_stores('', '', $order_type, $config['per_page'],$limit_end);        
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
            $data['count_products']= $this->stores_model->count_stores();
            $data['stores'] = $this->stores_model->get_stores('', '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($search_string) && !isset($order)
         
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load owner data
        foreach ($data['stores'] as $key => $store) {
            $data['stores'][$key]['owner_data'] = $this->users_model->get_users_by_id($store['owner_id']);
        }

        //load the view
        $data['main_content'] = 'admin/stores/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('gname', 'Name', 'required');
			$this->form_validation->set_rules('price', 'Price', 'required');
            $this->form_validation->set_rules('state', 'Current State', 'required');
            $this->form_validation->set_rules('flong', 'A Longitude', 'required');
            $this->form_validation->set_rules('flat', 'A Latitude', 'required');
            $this->form_validation->set_rules('fphone', 'A Phone number', 'required');
            $this->form_validation->set_rules('faddress', 'A Address', 'required');
            $this->form_validation->set_rules('fapt', 'A Apt', 'required');
            $this->form_validation->set_rules('fcity', 'A City', 'required');
            $this->form_validation->set_rules('fcountry', 'A Country', 'required');
            $this->form_validation->set_rules('fzip', 'A Zip', 'required');
            $this->form_validation->set_rules('tlong', 'B Longitude', 'required');
            $this->form_validation->set_rules('tlat', 'B Latitude', 'required');
            $this->form_validation->set_rules('tphone', 'B Phone number', 'required');
            $this->form_validation->set_rules('taddress', 'B Address', 'required');
            $this->form_validation->set_rules('tapt', 'B Apt', 'required');
            $this->form_validation->set_rules('tcity', 'B City', 'required');
            $this->form_validation->set_rules('tcountry', 'B Country', 'required');
            $this->form_validation->set_rules('tzip', 'B Zip', 'required');
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {
				$image_url_customer = "";
                $image_url_deliverer = "";
				if (isset($_FILES['customer_gallery'])) 
				{
					$config['upload_path']	= '../offers/upload/stores/';
					$config['overwrite']		= FALSE;
					$config['allowed_types']	= '*';
					$config['max_size']	= '50000';			
					$this->load->library('upload', $config);
					
					if (!$this->upload->do_upload("customer_gallery")) {
						$upload = FALSE;
					} else {
						$attache = $this->upload->data();
						$image_url_customer = $attache["file_name"];
						$upload = TRUE;
					}
				}
                if (isset($_FILES['deliverer_gallery'])) 
                {
                    $config['upload_path']  = '../offers/upload/stores/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload("deliverer_gallery")) {
                        $upload = FALSE;
                    } else {
                        $attache = $this->upload->data();
                        $image_url_deliverer = $attache["file_name"];
                        $upload = TRUE;
                    }
                }
                $data_to_store = array(
                    'gname' => $this->input->post('gname'),
					'price' => $this->input->post('price'),
                    'state' => $this->input->post('state'),
                    'flong' => $this->input->post('flong'),
                    'flat' => $this->input->post('flat'),
                    'fphone' => $this->input->post('fphone'),
                    'faddress' => $this->input->post('faddress'),
                    'fapt' => $this->input->post('fapt'),
                    'fcity' => $this->input->post('fcity'),
                    'fcountry' => $this->input->post('fcountry'),
                    'fzip' => $this->input->post('fzip'),
                    'tlong' => $this->input->post('tlong'),
                    'tlat' => $this->input->post('tlat'),
                    'tphone' => $this->input->post('tphone'),
                    'taddress' => $this->input->post('taddress'),
                    'tapt' => $this->input->post('tapt'),
                    'tcity' => $this->input->post('tcity'),
                    'tcountry' => $this->input->post('tcountry'),
                    'tzip' => $this->input->post('tzip'),
                    'feedback_customer' => $this->input->post('feedback_customer'),
                    'feedback_deliverer' => $this->input->post('feedback_deliverer'),
					'customer_gallery' => $image_url_customer,
                    'deliverer_gallery' => $image_url_deliverer,
                );
				
				if ($upload) {
					//if the insert has returned true then we show the flash message
					if($this->stores_model->store_stores($data_to_store)){
						$data['flash_message'] = TRUE; 
					}else{
						$data['flash_message'] = FALSE; 
					}
				} else {
					$data['flash_message'] = FALSE; 
				}
                //redirect('admin/stores/update/'.$id.'');
            }

        }
        //load the view
        $data['main_content'] = 'admin/stores/add';
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
            $this->form_validation->set_rules('gname', 'Name', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');
            $this->form_validation->set_rules('state', 'Current State', 'required');
            $this->form_validation->set_rules('flong', 'A Longitude', 'required');
            $this->form_validation->set_rules('flat', 'A Latitude', 'required');
            $this->form_validation->set_rules('fphone', 'A Phone number', 'required');
            $this->form_validation->set_rules('faddress', 'A Address', 'required');
            $this->form_validation->set_rules('fapt', 'A Apt', 'required');
            $this->form_validation->set_rules('fcity', 'A City', 'required');
            $this->form_validation->set_rules('fcountry', 'A Country', 'required');
            $this->form_validation->set_rules('fzip', 'A Zip', 'required');
            $this->form_validation->set_rules('tlong', 'B Longitude', 'required');
            $this->form_validation->set_rules('tlat', 'B Latitude', 'required');
            $this->form_validation->set_rules('tphone', 'B Phone number', 'required');
            $this->form_validation->set_rules('taddress', 'B Address', 'required');
            $this->form_validation->set_rules('tapt', 'B Apt', 'required');
            $this->form_validation->set_rules('tcity', 'B City', 'required');
            $this->form_validation->set_rules('tcountry', 'B Country', 'required');
            $this->form_validation->set_rules('tzip', 'B Zip', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
			$upload = FALSE;
            if ($this->form_validation->run())
            {    
                $image_url_customer = "";
                $image_url_deliverer = "";
                if (isset($_FILES['customer_gallery'])) 
                {
                    $config['upload_path']  = '../offers/upload/stores/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload("customer_gallery")) {
                        $upload = FALSE;
                    } else {
                        $attache = $this->upload->data();
                        $image_url_customer = $attache["file_name"];
                        $upload = TRUE;
                    }
                }
                if (isset($_FILES['deliverer_gallery'])) 
                {
                    $config['upload_path']  = '../offers/upload/stores/';
                    $config['overwrite']        = FALSE;
                    $config['allowed_types']    = '*';
                    $config['max_size'] = '50000';          
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload("deliverer_gallery")) {
                        $upload = FALSE;
                    } else {
                        $attache = $this->upload->data();
                        $image_url_deliverer = $attache["file_name"];
                        $upload = TRUE;
                    }
                }
                $data_to_store = array(
                    'gname' => $this->input->post('gname'),
                    'price' => $this->input->post('price'),
                    'state' => $this->input->post('state'),
                    'flong' => $this->input->post('flong'),
                    'flat' => $this->input->post('flat'),
                    'fphone' => $this->input->post('fphone'),
                    'faddress' => $this->input->post('faddress'),
                    'fapt' => $this->input->post('fapt'),
                    'fcity' => $this->input->post('fcity'),
                    'fcountry' => $this->input->post('fcountry'),
                    'fzip' => $this->input->post('fzip'),
                    'tlong' => $this->input->post('tlong'),
                    'tlat' => $this->input->post('tlat'),
                    'tphone' => $this->input->post('tphone'),
                    'taddress' => $this->input->post('taddress'),
                    'tapt' => $this->input->post('tapt'),
                    'tcity' => $this->input->post('tcity'),
                    'tcountry' => $this->input->post('tcountry'),
                    'tzip' => $this->input->post('tzip'),
                    'feedback_customer' => $this->input->post('feedback_customer'),
                    'feedback_deliverer' => $this->input->post('feedback_deliverer'),
                    'customer_gallery' => $image_url_customer,
                    'deliverer_gallery' => $image_url_deliverer,
                );
                
				//if the insert has returned true then we show the flash message
				if($this->stores_model->update_stores($id, $data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
				}
				redirect('admin/stores/update/'.$id.'');
            }//validation run
        }

        //if we are updating, and the data did not pass through the validation
        //the code below wel reload the current data

        //product data 
        $data['stores'] = $this->stores_model->get_stores_by_id($id);
        //load the view
        $data['main_content'] = 'admin/stores/edit';
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
        $this->stores_model->delete_stores($id);
        redirect('admin/stores');
    }//edit

}
?>