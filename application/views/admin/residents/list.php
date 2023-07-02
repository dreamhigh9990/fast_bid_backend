<div class="page-content">
  <div class="content">
    <div clas="page-title">
      <h3>
        Residents
      </h3>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-body">
            <table class="table" id="residents-table">
              <thead>
              <tr>
                <th style="width:5%">#</th>
                <th style="width:8%">First Name</th>
                <th style="width:20%">Last Name</th>
                <th style="width:20%">Gender</th>
                <th style="width:8%">DOB</th>
                <th style="width:10%">Room</th>
                <th style="width:10%">Doctor</th>
                <th style="width:10%">Photo</th>
                <th>Actions</th>
              </tr>
              </thead>
              <tbody>
              <?php
              if (count($residents)) {
                $num = 1;
                foreach ($residents as $row) {
                  echo '<tr id="residents-' . $row['residents_id'] . '">';
                  echo '<td>' . $num . '</td>';
                  echo '<td>' . $row['first_name'] . '</td>';
                  echo '<td>' . $row['last_name'] . '</td>';
                  echo '<td>' . $row['gender'] . '</td>';
                  echo '<td>' . $row['dob'] . '</td>';
                  echo '<td>' . $row['room'] . '</td>';
                  echo '<td>' . $row['doctor'] . '</td>';
                  echo '<td>';
                  if (isset($row['photo']) && $row['photo'] != "") {
                    echo '<img width="40px" height="40px" src="' . base_url() . 'upload/residents/photo/' . $row['photo'] . '">';
                  }
                  echo '</td>';
                  echo '<td>';
                  echo '<a href="javascript:;" rel="' . $row['residents_id'] . '" class="ajaxDelete"><img src="'
                    . base_url() . 'assets/img/icon/remove.png" class="dimage"></a>';
                  echo '</td>';
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
