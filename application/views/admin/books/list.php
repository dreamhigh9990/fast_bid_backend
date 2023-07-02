    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            Books
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2)."/add/".$this->uri->segment(3);?>" class="btn btn-success pull-right">New</a>
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
                      <th style="width:75%">Name</th>
                      <th >Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
      			  if (count($books) == 0) {
      				  echo '<tr>';
      				  echo '<td class="crud-actions" colspan="5" style="text-align:center">';
      				  echo 'not any books';
      				  echo '</td>';
      				  echo '</tr>';
      			  } else {
      				  $num = 1;
      				  foreach($books as $row)
      				  {
      					echo '<tr>';
      					echo '<td>'.$num.'</td>';
      					echo '<td style="overflow:hidden">'.$row['name'].'</td>';
                echo '<td class="crud-actions">
      					  <a href="'.site_url("admin").'/books/update/'.$row['books_id'].'" class="btn btn-info">Edit</a>  
      					  <a href="'.site_url("admin").'/books/delete/'.$row['books_id'].'" class="btn btn-danger">Delete</a>
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