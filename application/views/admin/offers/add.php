    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            Add New Offer
          </h3>
        </div>
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> New offer has been added successfully.';
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
      $attributes = array('class' => '', 'id' => '', 'role' => 'form');
      echo form_open_multipart('admin/offers/add/' . $this->uri->segment(4), $attributes);
      ?>
        <div class="grid-header">
          <h2 class="grid-title">Offer Information</h2>
        </div>
        <div class="grid-body">
          <div class="form-group">
            <label for="inputError" class="form-label">Photo</label>
            <div class="controls">
              <input type="file" accept="image/*" id="" name="photo"></input>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Name</label>
            <div class="controls">
              <input type="text" class="form-contr ol" id="" name="name" style="width:400px; height:30px" value="<?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('name'); ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Price</label>
            <div class="controls">
              <input type="text" class="form-control" id="" name="price" style="width:400px; height:30px" value="<?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('price'); ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Offer Time</label>
            <div class="input-group">
              <div class="input-append success date col-md-10 col-lg-6 no-padding" style="width:140px; height:30px">
                <input type="text" class="form-control" name="available_date" value="<?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('available_date'); ?>" >
                <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> 
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Offers Available</label>
            <div class="controls">
              <input type="text" class="form-control" id="" name="quantity" style="width:400px; height:30px" value="<?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('quantity'); ?>" >
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Category</label>
            <div class="controls">
              <select name="categories_id" id="categories_id">
              <?php if (isset($categories)) foreach ($categories as $code => $category) {
                    echo '<option value="' . $category['categories_id'] . '">' . $category['name'] . '</option>';
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
                    echo '<option value="' . $sub_category['categories_id'] . '">' . $sub_category['name'] . '</option>';
                }
              ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="inputError" class="form-label">Description</label>
            <div class="controls">
              <textarea name="description" rows="7" cols="400" style="width:400px"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('description'); ?></textarea>
            </div>
          </div>
          <div class="form-actions box-footer">
            <button class="btn btn-primary" type="submit">Save changes</button>
          </div>
        </div>
      <?php echo form_close(); ?>
            </div>
          </div>
        </div>
    </div>
     