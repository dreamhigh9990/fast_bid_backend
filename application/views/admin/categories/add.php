    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            Add New Category
          </h3>
        </div>
        <?php
        //flash messages
        if(isset($flash_message)){
          if($flash_message == TRUE)
          {
            echo '<div class="alert alert-success">';
              echo '<a class="close" data-dismiss="alert">×</a>';
              echo '<strong>Well done!</strong> New category has been added successfully.';
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
      
      echo form_open_multipart('admin/categories/add', $attributes);
      ?>
        <div class="grid-header">
          <h2 class="grid-title">Category Information</h2>
        </div>
        <div class="grid-body">
          <div class="form-group">
            <label for="inputError" class="form-label">Name</label>
            <div class="controls">
              <input type="text" class="form-control" id="" name="name" style="width:400px; height:30px" value="<?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('name'); ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Parent Category</label>
            <div class="controls">
                <select name="parent">
                    <option value="0" selected="selected"> None </option>
                    <?php foreach ($main_categories as $key => $main_category) {
                        echo '<option value="' . $main_category['categories_id'] . '">' . $main_category['title'] . '</option>';
                    }
                    ?>
                </select>
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Add</button>
          </div>
        </div>
      <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </section>
    </div>
     