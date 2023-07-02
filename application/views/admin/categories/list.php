    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            <?php echo ucfirst($this->uri->segment(2));?> 
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success pull-right">Add a new</a>
          </h3>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="grid simple">
              <div class="grid-body no-border">
                <table class="table no-more-tables">
                  <thead>
                    <tr>
                      <th style="width:5%">#</th>
                      <th style="width:20%">Parent Category</th>
                      <th style="width:25%">Name</th>
                      <th >Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
      			  if (count($categories) == 0) {
      				  echo '<tr>';
      				  echo '<td class="crud-actions" colspan="5" style="text-align:center">';
      				  echo 'not any categories';
      				  echo '</td>';
      				  echo '</tr>';
      			  } else {
      				  $num = 1;
      				  foreach($categories as $row)
      				  {
      					echo '<tr>';
      					echo '<td>'.$num.'</td>';
      					echo '<td style="overflow:hidden">'.$row['parent_category'].'</td>';
      					echo '<td style="overflow:hidden">'.$row['name'].'</td>';
                echo '<td class="crud-actions">
      					  <a href="'.site_url("admin").'/categories/update/'.$row['categories_id'].'" class="btn btn-info">view & edit</a>  
      					  <a href="'.site_url("admin").'/categories/delete/'.$row['categories_id'].'" class="btn btn-danger">delete</a>
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