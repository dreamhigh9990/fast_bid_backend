    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            Add New Individual Sentence
          </h3>
        </div>
        <?php
        //flash messages
        if(isset($flash_message)){
          if($flash_message == TRUE)
          {
            echo '<div class="alert alert-success">';
              echo '<a class="close" data-dismiss="alert">×</a>';
              echo '<strong>Well done!</strong> New individual sentence has been added successfully.';
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
      echo form_open_multipart('admin/isentences/add', $attributes);
      ?>
        <div class="row">
          <div class="col-md-12">
            <div class="grid simple">
              <div class="grid-header">
                <h3 class="grid-title">Sentence Information</h3>
              </div>
              <div class="grid-body">
                <div class="row">
                  <div class="col-md-6">
                    <h3>Input <span class="semi-bold">Source Sentences</span></h3>
                    <p>
                    Input original sentence with source language
                    </p>
                    <br>
                    <div class="form-group">
                      <label for="inputError" class="form-label">English</label>
                      <div class="controls">
                        <textarea name="s_en" rows="3" style="width:100%"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('english'); ?></textarea>
                      </div>
                      <br>
                      <label for="inputError" class="form-label">Chinese</label>
                      <div class="controls">
                        <textarea name="s_zh-CN" rows="3" style="width:100%"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('english'); ?></textarea>
                      </div>
                      <br>
                      <label for="inputError" class="form-label">German</label>
                      <div class="controls">
                        <textarea name="s_de" rows="3" style="width:100%"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('english'); ?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h3>Input <span class="semi-bold">Target Sentences</span></h3>
                    <p>
                    Input target sentence with destination language
                    </p>
                    <br>
                    <div class="form-group">
                      <label for="inputError" class="form-label">English</label>
                      <div class="controls">
                        <textarea name="d_en" rows="3" style="width:100%"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('english'); ?></textarea>
                      </div>
                      <br>
                      <label for="inputError" class="form-label">Chinese</label>
                      <div class="controls">
                        <textarea name="d_zh-CN" rows="3" style="width:100%"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('english'); ?></textarea>
                      </div>
                      <br>
                      <label for="inputError" class="form-label">German</label>
                      <div class="controls">
                        <textarea name="d_de" rows="3" style="width:100%"><?php if (isset($flash_message) && $flash_message == FALSE) echo set_value('english'); ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="inputError" class="form-label">Image</label>
                    <div class="controls">
                      <input type="file" accept="image/*" id="" name="image"></input>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="inputError" class="form-label">Audio</label>
                    <div class="controls">
                      <input type="file" accept="audio/*" id="" name="audio"></input>
                    </div>
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
     