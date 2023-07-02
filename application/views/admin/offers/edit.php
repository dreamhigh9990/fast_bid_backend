    <div class="page-content">
      <div class="content">
        <div class="page-title">
          <h2>Update Offer</h2>
        </div>
      
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> The offer has been updated successfully.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> Change a few things up and try submitting again.';
          echo '</div>';          
        }
        echo validation_errors();
      }
      ?>
      
        <div class="row">
          <div class="col-md-6">
            <div class="grid simple">
      <?php
      //form data
      $attributes = array('class' => '', 'id' => '', 'role' => 'form');

      //form validation
      
      echo form_open_multipart('admin/offers/update/' . $this->uri->segment(4), $attributes);
      ?>
        <div class="grid-header">
          <h4 class="grid-title">Offer Information</h4>
        </div>
        <div class="grid-body">
          <div class="form-group">
            <label for="inputError" class="form-label">Photo</label>
            <img src="<?php echo base_url() . "../offers/upload/offers/photo/" . $offers[0]['photo'] ?>" width="150px" height="150px" />
            <div class="controls">
              <input type="file" accept="image/*" id="" name="photo"></input>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Name</label>
            <div class="controls">
              <input type="text" class="form-contr ol" id="" name="name" style="width:400px; height:30px" value="<?php echo $offers[0]['name']; ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Price</label>
            <div class="controls">
              <input type="price" class="form-control" id="" name="price" style="width:400px; height:30px" value="<?php echo $offers[0]['price']; ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Offer Time</label>
            <div class="input-group">
              <div class="input-append success date col-md-10 col-lg-6 no-padding" style="width:140px; height:30px">
                <input type="text" class="form-control" name="available_date" value="<?php echo $offers[0]['available_date']; ?>" >
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> 
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Offers Available</label>
            <div class="controls">
              <input type="text" class="form-control" id="" name="quantity" style="width:400px; height:30px" value="<?php echo $offers[0]['quantity']; ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Category</label>
            <div class="controls">
              <select name="categories_id" id="categories_id">
              <?php if (isset($categories)) foreach ($categories as $code => $category) {
                    echo '<option value="' . $category['categories_id'] . '" ' . ($offers[0]['parent_categories_id'] == $category['categories_id'] ? ' selected="selected"' : null) . '>' . $category['name'] . '</option>';
                }
              ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Sub Category</label>
            <div class="controls">
              <select name="sub_categories_id" id="sub_categories_id">
              <?php if (isset($sub_categories)) foreach ($sub_categories as $code => $sub_category) {
                    echo '<option value="' . $sub_category['categories_id'] . '" ' . ($offers[0]['categories_id'] == $sub_category['categories_id'] ? ' selected="selected"' : null) . '>' . $sub_category['name'] . '</option>';
                }
              ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Description</label>
            <div class="controls">
              <textarea name="description" rows="7" cols="400" style="width:400px"><?php echo $offers[0]['description']; ?></textarea>
            </div>
          </div>
          <div class="form-actions box-footer">
            <button class="btn btn-primary" type="submit">Save changes</button>
          </div>
        </div>
      <?php echo form_close(); ?>
            </div>
          </div>
          <div class="col-md-6">
            <div class="grid simple">
              <div class="grid-header">
                <h4 class="grid-title">Bought Users</h4>
              </div>
              <div class="grid-body">
                <table class="table no-more-tables">
                  <thead>
                    <tr>
                      <th class="" style="width:5%">#</th>
                      <th class="" style="width:10%">Name</th>
                      <th class="" style="width:10%">Email</th>
                      <th class="" style="width:10%">Date</th>
                      <th class="" style="width:10%">Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
              if (count($bought_users) == 0) {
                echo '<tr>';
                echo '<td class="crud-actions" colspan="17" style="text-align:center">';
                echo 'There is no users who bought this offer.';
                echo '</td>';
                echo '</tr>';
              } else {
                $num = 1;

                foreach($bought_users as $row)
                {
                  echo '<tr>';
                  echo '<td>'.$num.'</td>';
                  echo '<td style="overflow:hidden">'.$row['name'].'</td>';
                  echo '<td style="overflow:hidden">'.$row['email'].'</td>';
                  echo '<td style="overflow:hidden">'.$row['date'].'</td>';
                  echo '<td style="overflow:hidden">'.$row['quantity'].'</td>';
                  echo '</tr>';
                  $num++;
                }
              }
                    ?>      
                  </tbody>
                </table>
              </div>
            </div>
            <div class="grid simple">
              <div class="grid-header">
                <h4 class="grid-title">Pending Users</h4>
              </div>
              <div class="grid-body">
                <table class="table no-more-tables">
                  <thead>
                    <tr>
                      <th class="" style="width:5%">#</th>
                      <th class="" style="width:10%">Name</th>
                      <th class="" style="width:10%">Email</th>
                      <th class="" style="width:10%">Date</th>
                      <th class="" style="width:10%">Quantity</th>
                      <th class="" style="width:10%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
              if (count($pending_users) == 0) {
                echo '<tr>';
                echo '<td class="crud-actions" colspan="17" style="text-align:center">';
                echo 'There is no pending users who bought this offer.';
                echo '</td>';
                echo '</tr>';
              } else {
                $num = 1;

                foreach($pending_users as $row)
                {
                  echo '<tr>';
                  echo '<td>'.$num.'</td>';
                  echo '<td style="overflow:hidden">'.$row['name'].'</td>';
                  echo '<td style="overflow:hidden">'.$row['email'].'</td>';
                  echo '<td style="overflow:hidden">'.$row['date'].'</td>';
                  echo '<td style="overflow:hidden">'.$row['quantity'].'</td>';
                  echo '<td class="crud-actions">
                    <a href="'.site_url("admin").'/offers/complete/'.$row['offers_id'].'/'.$row['buys_id'].'/'.$row['quantity'].'" class="btn btn-info">Complete</a>  
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
          </div>
        </div>
    </div>
     