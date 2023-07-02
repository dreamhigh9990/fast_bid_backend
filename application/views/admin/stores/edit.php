    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li>
          <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>">
            <?php echo ucfirst($this->uri->segment(2));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <a href="#">Update</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
      </div>

 
      <?php
      //flash messages
      if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> stores updated with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
      }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');

      //form validation
      echo validation_errors();

      echo form_open_multipart('admin/stores/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Name</label>
            <div class="controls">
              <input type="text" id="" name="gname" style="width:400px; height:30px" value="<?php echo $stores[0]['gname']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Price</label>
            <div class="controls">
              <input type="text" id="" name="price" style="width:400px; height:30px" value="<?php echo $stores[0]['price']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Current State</label>
            <div class="controls">
              <input type="text" id="" name="state" style="width:400px; height:30px" value="<?php echo $stores[0]['state']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Longitude</label>
            <div class="controls">
              <input type="text" id="" name="flong" style="width:400px; height:30px" value="<?php echo $stores[0]['flong']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Latitude</label>
            <div class="controls">
              <input type="text" id="" name="flat" style="width:400px; height:30px" value="<?php echo $stores[0]['flat']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Phone Number</label>
            <div class="controls">
              <input type="text" id="" name="fphone" style="width:400px; height:30px" value="<?php echo $stores[0]['fphone']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Address</label>
            <div class="controls">
              <input type="text" id="" name="faddress" style="width:400px; height:30px" value="<?php echo $stores[0]['faddress']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Apt</label>
            <div class="controls">
              <input type="text" id="" name="fapt" style="width:400px; height:30px" value="<?php echo $stores[0]['fapt']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A City</label>
            <div class="controls">
              <input type="text" id="" name="fcity" style="width:400px; height:30px" value="<?php echo $stores[0]['fcity']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Country</label>
            <div class="controls">
              <input type="text" id="" name="fcountry" style="width:400px; height:30px" value="<?php echo $stores[0]['fcountry']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">A Zip</label>
            <div class="controls">
              <input type="text" id="" name="fzip" style="width:400px; height:30px" value="<?php echo $stores[0]['fzip']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Longitude</label>
            <div class="controls">
              <input type="text" id="" name="tlong" style="width:400px; height:30px" value="<?php echo $stores[0]['tlong']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Latitude</label>
            <div class="controls">
              <input type="text" id="" name="tlat" style="width:400px; height:30px" value="<?php echo $stores[0]['tlat']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Phone Number</label>
            <div class="controls">
              <input type="text" id="" name="tphone" style="width:400px; height:30px" value="<?php echo $stores[0]['tphone']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Address</label>
            <div class="controls">
              <input type="text" id="" name="taddress" style="width:400px; height:30px" value="<?php echo $stores[0]['taddress']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Apt</label>
            <div class="controls">
              <input type="text" id="" name="tapt" style="width:400px; height:30px" value="<?php echo $stores[0]['tapt']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B City</label>
            <div class="controls">
              <input type="text" id="" name="tcity" style="width:400px; height:30px" value="<?php echo $stores[0]['tcity']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Country</label>
            <div class="controls">
              <input type="text" id="" name="tcountry" style="width:400px; height:30px" value="<?php echo $stores[0]['tcountry']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">B Zip</label>
            <div class="controls">
              <input type="text" id="" name="tzip" style="width:400px; height:30px" value="<?php echo $stores[0]['tzip']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Feedback on Customer</label>
            <div class="controls">
              <input type="text" id="" name="feedback_customer" style="width:400px; height:30px" value="<?php echo $stores[0]['feedback_customer']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Feedback on Deliverer</label>
            <div class="controls">
              <input type="text" id="" name="feedback_deliverer" style="width:400px; height:30px" value="<?php echo $stores[0]['feedback_deliverer']; ?>" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Customer's Gallery</label>
            <div class="controls">
              <img src="<?php echo base_url().'/upload/stores/'.$stores[0]['customer_gallery'] ?>" width="150px" height="150px" />
              <input type="file" accept="image/*" id="" name="customer_gallery"></input>
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Deliverer's Gallery</label>
            <div class="controls">
              <img src="<?php echo base_url().'/upload/stores/'.$stores[0]['deliverer_gallery'] ?>" width="150px" height="150px" />
              <input type="file" accept="image/*" id="" name="deliverer_gallery"></input>
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     