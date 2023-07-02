<div class="page-content">
  <div class="content">
    <div class="page-title">
      <h3>
        Edit <?php echo $category_name;?>
      </h3>
    </div>
      <?php
      //flash messages
      if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> prayers has been updated successfully.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
      }
      ?>

      <div class="row">
        <div class="col-md-12">
          <div class="grid simple">
            <div class="grid-header">
              <h2 class="grid-title">Prayer Information</h2>
            </div>
      
      <?php
      //form data
      $attributes = array('class' => '', 'id' => '', 'role' => 'form');

      //form validation
      echo validation_errors();

      echo form_open_multipart('admin/prayers/update/'.$this->uri->segment(4).'', $attributes);
      ?>
            <div class="grid-body">
              <div class="control-group">
                <label for="inputError" class="control-label">Name</label>
                <div class="controls">
                  <input type="text" id="" name="prayer_name" style="width:400px; height:30px" value="<?php echo $prayers[0]['name']; ?>" >
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <div class="control-group">
                    <label for="inputError" class="control-label">Content(English)</label>
                    <div class="controls">
                      <textarea name="content_english" rows="30" style="width:100%"><?php echo $prayers[0]['content_english']; ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="control-group">
                    <label for="inputError" class="control-label">Content(Transliterated)</label>
                    <div class="controls">
                      <textarea name="content_transliterated" rows="30" style="width:100%"><?php echo $prayers[0]['content_transliterated']; ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="control-group">
                    <label for="inputError" class="control-label">Content(Hebrew)</label>
                    <div class="controls">
                      <textarea dir="rtl" name="content_hebrew" rows="30" style="width:100%"><?php echo $prayers[0]['content_hebrew']; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <input type="hidden" name="published" id="published" value="<?php echo $prayers[0]['published']; ?>">
                <button class="btn btn-primary" type="button" id="save_publish">Save and Publish</button>
                <button class="btn btn-warning" type="button" id="save_no_publish">Save and don't Publish</button>
                <button class="btn btn-danger" type="button" id="cancel">Cancel</button>
              </div>
            </div>
      <?php echo form_close(); ?>
          </div>
        </div>
      </div>

  </div>
</div>