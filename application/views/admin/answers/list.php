<div class="page-content">
  <div class="content">
    <div clas="page-title">
      <h3>
        Question and Answers
      </h3>
      <br>
      <h4>
        Question : <b><?php echo $question['question']?></b>
      </h4>
      <h4>
        Description : <b><?php echo $question['description']?></b>
      </h4>
      <h4>
        Q - Language : <b><?php echo $question['lang']?></b>
      </h4>
      <h4>
        A - Language : <b><?php echo $question['answer_lang']?></b>
      </h4>
      <h4>
        Asker : <b><?php echo $question['asker']?></b>
      </h4>
      <h4>
        Date : <b><?php echo $question['date']?></b>
      </h4>
      <br>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-body">
            <table class="table" id="answers-table">
              <thead>
              <tr>
                <th style="width:5%">#</th>
                <th style="width:40%">Answer</th>
                <th style="width:10%">Correct Answer</th>
                <th style="width:10%">Asker's Answer</th>
                <th style="width:10%">Answerer</th>
                <th style="width:10%">Like Count</th>
                <th style="width:10%">Date</th>
                <th>Actions</th>
              </tr>
              </thead>
              <tbody>
              <?php
              if (count($answers)) {
                $num = 1;
                foreach ($answers as $row) {
                  echo '<tr id="answers-' . $row['answers_id'] . '">';
                  echo '<td>' . $num . '</td>';
                  echo '<td class="answer">' . $row['answer'] . '</td>';
                  echo '<td class="is_correct_answer">' . (($row['is_correct_answer']) ? 'Yes' : 'No') . '</td>';
                  echo '<td>' . (($row['is_asker_answer']) ? 'Yes' : 'No') . '</td>';
                  echo '<td>' . $row['answerer'] . '</td>';
                  echo '<td>' . $row['liked_count'] . '</td>';
                  echo '<td>' . $row['date'] . '</td>';
                  echo '<td>';
                  echo '<a href="javascript:;" rel="' . $row['answers_id'] . '" class="ajaxEdit"><img src="' . base_url() . 'assets/img/icon/edit.png" class="eimage"></a>';
                  echo '<a href="javascript:;" rel="' . $row['answers_id'] . '" class="ajaxDelete"><img src="' . base_url() . 'assets/img/icon/remove.png" class="dimage"></a>';
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
  <div class="admin-bar" id="quick-access" style="display:">
    <div class="admin-bar-inner">
      <table class="table">
        <tbody>
        <tr>
          <td style="width:80%;border: none" class="ajaxReq">
            <textarea name="answer" rows="5" placeholder="Enter answer"></textarea>
          </td>
          <td style="width:10%;border: none" class="ajaxReq">
            <input id="is_correct_answer" type="checkbox">
            <label for="is_correct_answer">Correct?</label>
          </td>
          <td class="crud-actions" style="border: none">
            <button class="ajaxSave btn btn-primary btn-cons btn-add" type="button">Save</button>
            <button class="cancel btn btn-white btn-cons btn-cancel" type="button">Cancel</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

