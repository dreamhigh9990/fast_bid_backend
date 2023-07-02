
    <div class="page-content">
      <div class="content">
        <div class="page-title">
          <h3>Update Individual Sentence</h3>
        </div>
      
              <?php
              //flash messages
              if($this->session->flashdata('flash_message')){
                if($this->session->flashdata('flash_message') == 'updated')
                {
                  echo '<div class="alert alert-success">';
                    echo '<a class="close" data-dismiss="alert">×</a>';
                    echo '<strong>Well done!</strong> The sentence info has been updated successfully.';
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
              <?php
              //form data
              $attributes = array('class' => '', 'id' => '', 'role' => 'form');
              echo form_open_multipart('admin/isentences/update/'.$this->uri->segment(4).'', $attributes);
              ?>
                <div class="grid-body no-border"><br>
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
                          <textarea name="s_en" rows="3" style="width:100%"><?php echo $isentences[0]['s_en']; ?></textarea>
                        </div>
                        <br>
                        <label for="inputError" class="form-label">Chinese</label>
                        <div class="controls">
                          <textarea name="s_zh-CN" rows="3" style="width:100%"><?php echo $isentences[0]['s_zh-CN']; ?></textarea>
                        </div>
                        <br>
                        <label for="inputError" class="form-label">German</label>
                        <div class="controls">
                          <textarea name="s_de" rows="3" style="width:100%"><?php echo $isentences[0]['s_de']; ?></textarea>
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
                          <textarea name="d_en" rows="3" style="width:100%"><?php echo $isentences[0]['d_en']; ?></textarea>
                        </div>
                        <br>
                        <label for="inputError" class="form-label">Chinese</label>
                        <div class="controls">
                          <textarea name="d_zh-CN" rows="3" style="width:100%"><?php echo $isentences[0]['d_zh-CN']; ?></textarea>
                        </div>
                        <br>
                        <label for="inputError" class="form-label">German</label>
                        <div class="controls">
                          <textarea name="d_de" rows="3" style="width:100%"><?php echo $isentences[0]['d_de']; ?></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="inputError" class="form-label">Image</label>
                      <div class="controls">
                        <img src="<?php echo ($isentences[0]['image']) ? base_url() . 'upload/sentences/image/'.$isentences[0]['image'] : ''?>" width='100px' height='100px'/>
                        <input type="file" accept="image/*" id="" name="image"></input>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputError" class="form-label">Audio</label>
                      <video width="300" height="30" controls>
                        <source src="<?php echo base_url() . 'upload/sentences/audio/'.$isentences[0]['audio'] ?>" type="video/mp4" />
                      </video>
                      <div class="controls">
                        <input type="file" accept="audio/*" id="" name="audio"></input>
                      </div>
                    </div>
                  </div>
                  <div class="form-actions">
                    <button class="btn btn-success" type="submit">Save changes</button>
                  </div>
                </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
     