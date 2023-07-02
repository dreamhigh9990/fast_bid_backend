
    <div class="page-content">
      <div class="content">
        <div class="page-title">
          <h3>Update Chapter</h3>
        </div>
      
              <?php
              //flash messages
              if($this->session->flashdata('flash_message')){
                if($this->session->flashdata('flash_message') == 'updated')
                {
                  echo '<div class="alert alert-success">';
                    echo '<a class="close" data-dismiss="alert">×</a>';
                    echo '<strong>Well done!</strong> The chapter info has been updated successfully.';
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
                  echo form_open_multipart('admin/chapters/update/'.$this->uri->segment(4).'', $attributes);
                  ?>
                    <div class="form-group">
                      <label for="inputError" class="form-label">Name</label>
                      <div class="controls">
                        <input type="text" class="form-control" id="" name="name" style="width:400px" value="<?php echo htmlspecialchars($chapters[0]['name']); ?>" >
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
     