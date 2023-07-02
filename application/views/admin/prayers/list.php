    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            <?php echo $category_name;?> 
            <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2).'/add/'.$this->uri->segment(3); ?>" class="btn btn-success pull-right">Add a new</a>
          </h3>
        </div>

        <div class="row">
          <div class="col-xs-12">
            <div class="grid simple">
              <div class="grid-body no-border">
               
                <?php
               
                $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
               
                //save the columns names in a array that we will use as filter         
                $options_prayers = array();    
                if ($prayers)
                {
                  foreach ($prayers as $array) {
                    foreach ($array as $key => $value) {
                      $options_prayers[$key] = $key;
                    }
                    break;
                  }
                }
                
                // echo form_open('admin/stores', $attributes);
         
                //   echo form_label('Search:', 'search_string');
                //   echo form_input('search_string', $search_string_selected);

                //   echo form_label('Order by:', 'order');
                //   echo form_dropdown('order', $options_stores, $order, 'class="span2"');

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
                    <th class="" style="width:10%">Name</th>
                    <th class="" style="width:10%">Category</th>
                    <th class="" style="width:15%">Content(English)</th>
                    <th class="" style="width:15%">Content(Transliterated)</th>
                    <th class="" style="width:15%">Content(Hebrew)</th>
                    <th class="" style="width:10%">Published</th>
                    <th class="">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
    			  if (count($prayers) == 0) {
    				  echo '<tr>';
    				  echo '<td class="crud-actions" colspan="17" style="text-align:center">';
    				  echo 'not any prayers';
    				  echo '</td>';
    				  echo '</tr>';
    			  } else {
    				  $num = 1;

    				  foreach($prayers as $row)
    				  {
      					echo '<tr>';
      					echo '<td>'.$num.'</td>';
      					echo '<td style="overflow:hidden">'.$row['name'].'</td>';
      					echo '<td style="overflow:hidden">'.$row['category'].'</td>';
                echo '<td style="overflow:hidden">'.((strlen($row['content_english']) > 30) ? substr($row['content_english'], 0, 30) : $row['content_english']) .'</td>';
                echo '<td style="overflow:hidden">'.((strlen($row['content_transliterated']) > 30) ? substr($row['content_transliterated'], 0, 30) : $row['content_transliterated']) .'</td>';
                echo '<td style="overflow:hidden">'.((strlen($row['content_hebrew']) > 30) ? substr($row['content_hebrew'], 0, 30) : $row['content_hebrew']) .'</td>';
                echo '<td style="overflow:hidden">'.(($row['published'] == 1) ? 'YES' : 'NO').'</td>';
      					echo '<td class="crud-actions">
      					  <a href="'.site_url("admin").'/prayers/update/'.$row['prayers_id'].'" class="btn btn-primary">Edit</a>  
                  <a href="'.site_url("admin").'/prayers/delete/'.$row['prayers_id'].'?category='.$this->uri->segment(3).'" class="btn btn-danger">Delete</a>
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
    </div>