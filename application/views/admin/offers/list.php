    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            <?php echo ucfirst($this->uri->segment(2));?> 
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add/<?php echo $this->uri->segment(3);?>" class="btn btn-success pull-right">Add a new</a>
          </h3>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="grid simple">
              <div class="grid-body no-border">
             
              <?php
             
              $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
             
              //save the columns names in a array that we will use as filter         
              $options_offers = array();    
              if ($offers)
              {
                foreach ($offers as $array) {
                  foreach ($array as $key => $value) {
                    $options_offers[$key] = $key;
                  }
                  break;
                }
              }
              
              // echo form_open('admin/offers', $attributes);
       
              //   echo form_label('Search:', 'search_string');
              //   echo form_input('search_string', $search_string_selected);

              //   echo form_label('Order by:', 'order');
              //   echo form_dropdown('order', $options_offers, $order, 'class="span2"');

              //   $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              //   $options_order_type = array('Asc' => 'Asc', 'Desc' => 'Desc');
              //   echo form_dropdown('order_type', $options_order_type, $order_type_selected, 'class="span1"');

              //   echo form_submit($data_submit);

              // echo form_close();
              ?>

            <table class="table no-more-tables">
              <thead>
                <tr>
                  <th class="" style="width:5%">#</th>
                  <th class="" style="width:10%">Photo</th>
                  <th class="" style="width:10%">Name</th>
                  <th class="" style="width:10%">Price</th>
                  <th class="" style="width:10%">Time Left</th>
                  <th class="" style="width:5%">Rating</th>
                  <th class="" style="width:25%">Description</th>
                  <th class="">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
  			  if (count($offers) == 0) {
  				  echo '<tr>';
  				  echo '<td class="crud-actions" colspan="17" style="text-align:center">';
  				  echo 'This store has no offers';
  				  echo '</td>';
  				  echo '</tr>';
  			  } else {
  				  $num = 1;

  				  foreach($offers as $row)
  				  {
    					echo '<tr>';
    					echo '<td>'.$num.'</td>';
              echo '<td style="overflow:hidden">'.'<img src="'.base_url().'../offers/upload/offers/photo/'.$row['photo'].'" width="100px" height="100px" /></td>';
    					echo '<td style="overflow:hidden">'.$row['name'].'</td>';
    					echo '<td style="overflow:hidden">'.$row['price'].'</td>';
              echo '<td style="overflow:hidden">'.$row['available_date'].'</td>';
              echo '<td style="overflow:hidden">'.$row['rating'].'</td>';
              echo '<td style="overflow:hidden">'.((strlen($row['description']) > 200) ? substr($row['description'], 0, 200)."..." : $row['description']).'</td>';
    					echo '<td class="crud-actions">
    					  <a href="'.site_url("admin").'/offers/update/'.$row['offers_id'].'" class="btn btn-info">View & Edit</a>  
                <a href="'.site_url("admin").'/offers/delete/'.$row['offers_id'].'/'.$this->uri->segment(3).'" class="btn btn-danger">Delete</a>  
    					</td>';
    					echo '</tr>';
    					$num++;
  				  }
  			  }
                ?>      
              </tbody>
            </table>
          </div>
        </div>
          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
        </div>
      </div>
    </div>