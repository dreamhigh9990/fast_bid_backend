  <div class="page-content">
    <div class="content">
      <div clas="page-title">
        <h3>
          Videos
        </h3>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="grid simple">
            <div class="grid-body">
              <table class="table" id="videos-table">
                <thead>
                  <tr>
                    <th style="width:5%">#</th>
                    <th style="width:10%">Thumbnail</th>
                    <th style="width:75%">Title</th>
                    <th >Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (count($videos)) {
                    $num = 1;
                    foreach($videos as $row)
                    {
                      echo '<tr id="video-'.$row['videos_id'].'">';
                      echo '<td>'.$num.'</td>';
                      echo '<td>';
                      if (isset($row['image']))
                        echo '<a href="http://www.youtube.com/watch?v='.$row['link'].'" target="_blank"><img width="120px" height="80px" src="'.$row['image'].'"></a>';
                      echo '</td>';
                      echo '<td>'.$row['title'].'</td>';
                      echo '<td><a href="javascript:;" rel="'.$row['videos_id'].'" class="ajaxDelete"><img src="'.base_url().'assets/img/icon/remove.png" class="dimage"></a></td>';
                      echo '</tr>';
                      $num++;
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="addVideoModal" tabindex="-1" role="dialog" aria-labelledby="addVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <br>
          <i class="icon-credit-card icon-7x"></i>
          <h4 id="addVideoModalLabel" class="semi-bold">Select a video file.</h4>
          <p class="no-margin">You can import a video by putting link or searching on youtube.</p>
          <br>
        </div>
        <div class="modal-body">
          <div class="row form-row">
            <div class="col-md-12">
              <input type="text" class="form-control" placeholder="Youtube Video Link" id="video_url">
            </div>
          </div>
          <div class="row form-row">
            <div class="col-md-10">
              <input type="text" class="form-control" placeholder="Search for youtube videos" id="search_video_key">
            </div>
            <div class="col-md-2">
              <button type="button" class="btn" id="search_video" style="float: right;"><i class="fa fa-search"></i>&nbsp;Search</button>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <ul class="thumbnails"></ul>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="save_video">Import Video</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
