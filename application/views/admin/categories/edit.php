
    <div class="page-content">
      <div class="content">
        <div class="page-title">
          <h3>Update <?php echo ucfirst($this->uri->segment(2));?></h3>
        </div>
      
              <?php
              //flash messages
              if($this->session->flashdata('flash_message')){
                if($this->session->flashdata('flash_message') == 'updated')
                {
                  echo '<div class="alert alert-success">';
                    echo '<a class="close" data-dismiss="alert">×</a>';
                    echo '<strong>Well done!</strong> The user info has been updated successfully.';
                  echo '</div>';       
                }else{
                  echo '<div class="alert alert-error">';
                    echo '<a class="close" data-dismiss="alert">×</a>';
                    echo '<strong>Oh snap!</strong> Change a few things up and try submitting again.';
                  echo '</div>';          
                }
              }
              echo validation_errors();
              ?>

        <div class="row">
          <div class="col-md-8">
            <div class="grid simple">
              <div class="grid-body no-border"><br>
                <div class="row">
                  <div class="col-md-12">
                  <?php
                  //form data
                  $attributes = array('class' => '', 'id' => '', 'role' => 'form');
                  echo form_open_multipart('admin/categories/update/'.$this->uri->segment(4).'', $attributes);
                  ?>
                    <div class="form-group">
                      <label for="inputError" class="form-label">Name</label>
                      <div class="controls">
                        <input type="text" class="form-control" id="" name="name" style="width:400px" value="<?php echo $categories[0]['name']; ?>" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputError" class="form-label">Parent Category</label>
                      <div class="controls">
                        <select name="parent">
                          <option value="0" <?php if ($categories[0]['parent'] == 0) echo 'selected="selected"'?>> None </option>
                          <?php foreach ($main_categories as $key => $main_category) {
                                echo '<option value="' . $main_category['categories_id'] . '" ' . ($categories[0]['parent'] == $main_category['categories_id'] ? 'selected="selected"' : null) . '>' . $main_category['title'] . '</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-actions">
                      <button class="btn btn-success" type="submit">Save changes</button>
                    </div>
                    <?php echo form_close(); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
     