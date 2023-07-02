    <div class="page-content">
      <div class="content">
        <div clas="page-title">
          <h3>
            Individual Sentences
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success pull-right">New</a>
          </h3>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="grid simple">
              <div class="grid-body no-border">
                <table class="table no-more-tables">
                  <thead>
                    <tr>
                      <th style="width:5%">#</th>
                      <th style="width:35%">Source</th>
                      <th style="width:35%">Target</th>
                      <th style="width:10%">Direction</th>
                      <th >Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
      			  if (count($isentences) == 0) {
      				  echo '<tr>';
      				  echo '<td class="crud-actions" colspan="5" style="text-align:center">';
      				  echo 'not any individual sentences';
      				  echo '</td>';
      				  echo '</tr>';
      			  } else {
      				  $num = 1;
      				  foreach($isentences as $row)
      				  {
        					echo '<tr>';
        					echo '<td>'.$num.'</td>';
                  $s_language_field = "";
                  switch ($row['s_language']) {
                    case 'en':
                      $s_language_field = 's_en';
                      break;
                    case 'zh-CN':
                      $s_language_field = 's_zh-CN';
                      break;
                    default:
                      $s_language_field = 's_de';
                      break;
                  }
                  $d_language_field = "";
                  switch ($row['d_language']) {
                    case 'en':
                      $d_language_field = 'd_en';
                      break;
                    case 'zh-CN':
                      $d_language_field = 'd_zh-CN';
                      break;
                    default:
                      $d_language_field = 'd_de';
                      break;
                  }
        					echo '<td style="overflow:hidden">'.$row[$s_language_field].'</td>';
                  echo '<td style="overflow:hidden">'.$row[$d_language_field].'</td>';
                  echo '<td style="overflow:hidden">'.$row['s_language'].'->'.$row['d_language'].'</td>';
                  echo '<td class="crud-actions">
        					  <a href="'.site_url("admin").'/isentences/update/'.$row['sentences_id'].'" class="btn btn-info">Edit</a>  
        					  <a href="'.site_url("admin").'/isentences/delete/'.$row['sentences_id'].'" class="btn btn-danger">Delete</a>
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

            <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>
          </div>
        </div>
      </div>
    </div>