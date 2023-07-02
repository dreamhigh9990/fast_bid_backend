    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            Add New Individual Chapter
          </h3>
        </div>
        <?php
        //flash messages
        if(isset($flash_message)){
          if($flash_message == TRUE)
          {
            echo '<div class="alert alert-success">';
              echo '<a class="close" data-dismiss="alert">×</a>';
              echo '<strong>Well done!</strong> New individual chapter has been added successfully.';
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
      
      <?php
      $attributes = array('class' => '', 'id' => '', 'role' => 'form');
      echo form_open_multipart('admin/ichapters/add', $attributes);
      ?>
        <div class="row">
          <div class="col-md-6">
            <div class="grid simple">
              <div class="grid-header">
                <h2 class="grid-title">Chapter Information</h2>
              </div>
              <div class="grid-body">
                <div class="form-group">
                  <label for="inputError" class="form-label">Name</label>
                  <div class="controls">
                    <input type="text" class="form-control" id="" name="name" style="width:400px; height:30px" value="<?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('name'); ?>" >
                  </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Add</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
     