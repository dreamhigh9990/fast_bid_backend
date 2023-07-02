// Set highlight animation delay (higher the value longer will be the animation)
var saveAnimationDelay = 3000;
var deleteAnimationDelay = 1000;

// 2 effects available available 1) slide 2) flash
var questionsTable;
var question_images = [];
var thisDropzone;
var selectedQuestionsId;
var selectedRowId;
var IsEditingQuestion;
var trcopy;
var IsEditingAnswer = 0;
var editingtrid = 0;

function resetDropZone()
{
  question_images = [];
  $('#question-dropzone').empty();
  $('#question-dropzone').html('<div class="dz-default dz-message"><span>Drop files here to upload</span>');
  Dropzone.options.questionDropzone = {
    paramName: "file",
    maxFilesize: 10,
    url: base_url + 'admin/questions/upload_image',
    init: function() {
      var cd;
      thisDropzone = this;
      this.on("success", function(file, response) {
        $('.dz-progress').hide();
        $('.dz-size').hide();
        $('.dz-error-mark').hide();
        cd = response;
        question_images.push(response);
      });
      this.on('resetFiles', function() {
      });
      this.on("addedfile", function(file) {
        var removeButton = Dropzone.createElement('<a href="#">Remove file</a>');
        var _this = this;
        removeButton.addEventListener("click", function(e) {
          e.preventDefault();
          e.stopPropagation();
          _this.removeFile(file);
          var index = question_images.indexOf(file.name);
          if (index >= 0) {
            question_images.splice(index, 1);
          }
          if (cd !== undefined) {
            $.ajax({
              type: 'POST',
              url: base_url + 'admin/questions/delete_image',
              data: {image: cd}
            });
          }
        });
        file.previewElement.appendChild(removeButton);
      });
    }
  };
}

$(document).ready(function () {

  resetDropZone();

  var responsiveHelper = undefined;
  var breakpointDefinition = {
    tablet: 1024,
    phone: 480
  };

  questionsTable = $('#questions-table').dataTable({
    "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
    "aoColumnDefs": [
      {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8]}
    ],
    "oLanguage": {
      "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries",
      "oPaginate": {
        "sFirst": " ",
        "sPrevious": " ",
        "sNext": " ",
        "sLast": " ",
      },
      "sLengthMenu": "Rows per page: _MENU_",
      "sInfoFiltered": "(filtered from _MAX_ total records)"
    }
  });
  $('#questions-table_wrapper .dataTables_filter input').addClass("input-medium ");
  $('#questions-table_wrapper .dataTables_length select').addClass("select2-wrapper span12");
  $("#questions-table_wrapper div.toolbar").html('<div class="table-tools-actions"><button class="btn btn-primary" style="margin-left:12px" id="addQuestion" data-toggle="modal" data-target="#addQuestionModal">Add</button></div>');

  $('#addQuestion').on("click", function (e) {
    resetDropZone();
    question_images = [];
    $('#answerPanel').show();
    IsEditingQuestion = false;
    $('select#question_lang option[value="zh-CN"]').attr('selected', 'selected');
    $('select#answer_lang option[value=en]').attr('selected', 'selected');
    $('#question').val('');
    $('#q_description').val('');
    $('#q_description').val('');
    $('#answer').val('');
  });

  // set images for edit and delete
  $(".dimage").attr("src", deleteImage);

  // Delete record
  $(document).on("click", "#questions-table .ajaxDelete", function (e) {
    var id = $(this).attr("rel");
    if (id) {
      if (confirm("Do you really want to delete record ?")) {
        ajax_question_delete(id, $(this).parent().parent().index('tr') - 1);
      }
    }
  });

  // Edit record
  $(document).on("click", "#questions-table .ajaxEdit", function (e) {
    var id = $(this).attr("rel");
    $('#answerPanel').hide();
    selectedQuestionsId = id;
    selectedRowId = $(this).parent().parent().children('td').first().html();
    IsEditingQuestion = true;
    ajax_question_read(id);
  });

  // Delete record
  $(document).on("click", "#answers-table .ajaxDelete", function (e) {
    var id = $(this).attr("rel");
    if (id) {
      if (confirm("Do you really want to delete this answer ?")) {
        ajax_answer_delete(id, $(this).parent().parent().index('tr') - 1);
      }
    }
  });

  $(document).on("click", "#answers-table .ajaxEdit", function () {
    var id = $(this).attr("rel");
    if (id && IsEditingAnswer == 0) {
      var html = "<td>" + $("#answers-table tbody tr#answers-" + id + " td:first-child").html() + "</td>";
      html += '<td class="ajaxReq"><textarea name="answer">' + $("#answers-table tbody tr#answers-" + id + " td.answer").html() + '</textarea></td>';
      html += '<td class="ajaxReq"><input type="checkbox" name="is_correct_answer"' + (($("#answers-table tbody tr#answers-" + id + " td.is_correct_answer").html() == 'Yes') ? 'checked' : '') + '></td>';
      html += '<td></td><td></td><td></td><td></td>';
      html += '<td class="crud-actions"><a href="javascript:;" rel="' + id + '" class="ajaxUpdate"><img src="' + base_url + "assets/img/icon/save.png" + '" class="uimage"></a> <a href="javascript:;" rel="' + id + '" class="cancel"><img src="' + base_url + "assets/img/icon/back.png" + '" class="cimage"></a></td>';
      // Before replacing the TR contents, make a copy so when user clicks on
      trcopy = $("#answers-table tbody tr#answers-" + id).html();
      $("#answers-table tbody tr#answers-" + id).html(html);

      // set editing flag
      IsEditingAnswer = 1;
      editingtrid = id;
    }
  });

  $(document).on("click", "#answers-table .cancel", function () {
    var id = $(this).attr("rel");
    $("#answers-table tbody tr#answers-" + id).html(trcopy);
    $("#answers-table tbody tr:last-child").fadeIn("fast");
    IsEditingAnswer = 0;
    editingtrid = 0;
  });

  // Save button click on complete row update event
  $(document).on("click", "#answers-table .ajaxUpdate", function () {
    var id = $(this).attr("rel");
    var validation = 1;

    var params = {};
    params['answers_id'] = id;
    var answer = $.trim($('textarea[name="answer"]').val());
    if (answer != '') {
      params['answer'] = answer;
    } else {
      $('textarea[name="answer"]').focus();
      return;
    }
    if ($('input[name="is_correct_answer"]').is(":checked"))
      params['is_correct_answer'] = 1;
    else
      params['is_correct_answer'] = 0;

    ajax_answer_update(params);
  });

  $(document).on("click", "#save_question", function () {
    var data = {};
    data['lang'] = $('#question_lang').val();
    data['target'] = $('#answer_lang').val();
    data['question'] = $('#question').val();
    data['description'] = $('#q_description').val();
    data['images'] = question_images.toString();

    if (IsEditingQuestion) {
      data['questions_id'] = selectedQuestionsId;
      ajax_question_update(data);
    } else {
      data['answer'] = $('#answer').val();
      ajax_question_add(data);
    }
  });

  var html = '';
  isoLangs.forEach(function (one) {
    html += '<option value="' + one.code + '">' + one.nativeName + '</option>';
  });
  $('select#question_lang').html(html);
  $('select#answer_lang').html(html);
  $('select#question_lang option[value="zh-CN"]').attr('selected', 'selected');
  $('select#answer_lang option[value=en]').attr('selected', 'selected');

});

function ajax_question_read(id) {
  $.ajax({
    type: "POST",
    url: base_url + "admin/questions/get_question_info",
    data: {id: id},
    success: function (result) {
      question = $.parseJSON(result);

      $('select#question_lang option[value="'+question.lang+'"]').attr('selected', 'selected');
      $('select#answer_lang option[value="'+question.answer_lang+'"]').attr('selected', 'selected');
      $('#question').val(question.question);
      $('#q_description').val(question.description);

      resetDropZone();
      if (question.images) {
        images = question.images.split(",");
        for (var i = 0; i < images.length; i++) {
          var mockFile = { name: images[i], size: 0 };
          thisDropzone.emit("addedfile", mockFile);
          thisDropzone.options.thumbnail.call(thisDropzone, mockFile, base_url + "upload/questions/images/" + images[i]);
          thisDropzone.emit("complete", mockFile);
        }
        question_images = images;
      }
    }
  });
}

function ajax_question_delete(id, curRow) {
  $.ajax({
    type: "POST",
    url: base_url + "admin/questions/delete",
    data: {id: id},
    success: function (result) {
      questionsTable.fnDeleteRow(curRow);
    }
  });
}

function ajax_answer_delete(id, curRow) {
  $.ajax({
    type: "POST",
    url: base_url + "admin_questions/delete_answer",
    data: {id: id},
    success: function (result) {
      $("#answers-table tbody tr#answers-" + id).remove();
    }
  });
}

function ajax_question_add(params) {
  $.ajax({
    type: "POST",
    dataType: 'json',
    url: base_url + "admin/questions/add",
    data: params,
    success: function (response) {
      console.log(response);
      $('#addQuestionModal').modal('hide');
      var rowsTotal = questionsTable.fnGetData().length;
      var rowData = [];
      rowData.push(parseInt(rowsTotal + 1));
      rowData.push(params['lang']);
      rowData.push(params['question']);
      rowData.push(params['description']);
      rowData.push(params['target']);
      var imageTag = "";
      if (params['images']) {
        images = params['images'].split(",");
        for (var i = 0; i < images.length; i++) {
          imageTag += '<img width="40px" height="40px" src="' + base_url + "upload/questions/images/" + images[i] + '">';
        }
      }
      rowData.push(imageTag);
      rowData.push('Admin');
      rowData.push((new Date()).toISOString().substring(0, 19).replace('T', ' '));
      rowData.push('<a href="javascript:;" rel="' + response + '" class="ajaxEdit" data-toggle="modal" data-target="#addQuestionModal"><img src="' + base_url + 'assets/img/icon/edit.png" class="eimage"></a>' +
      '<a href="javascript:;" rel="' + response + '" class="ajaxAnswers"><img src="' + base_url + 'assets/img/icon/online-2x.png" class="uimage"></a>' + 
      '<a href="javascript:;" rel="' + response + '" class="ajaxDelete"><img src="' + base_url + 'assets/img/icon/remove.png" class="dimage"></a>');
      questionsTable.fnAddData(rowData);
    }
  });
}

function ajax_question_update(params) {
  $.ajax({
    type: "POST",
    dataType: 'json',
    url: base_url + "admin/questions/update",
    data: params,
    success: function (response) {
      console.log(response);
      $('#addQuestionModal').modal('hide');
      var rowData = [];
      rowData.push(selectedRowId);
      rowData.push(params['lang']);
      rowData.push(params['question']);
      rowData.push(params['description']);
      rowData.push(params['target']);
      var imageTag = "";
      if (params['images']) {
        images = params['images'].split(",");
        for (var i = 0; i < images.length; i++) {
          imageTag += '<img width="40px" height="40px" src="' + base_url + "upload/questions/images/" + images[i] + '">';
        }
      }
      rowData.push(imageTag);
      rowData.push('Admin');
      rowData.push((new Date()).toISOString().substring(0, 19).replace('T', ' '));
      rowData.push('<a href="javascript:;" rel="' + params['questions_id'] + '" class="ajaxEdit" data-toggle="modal" data-target="#addQuestionModal"><img src="' + base_url + 'assets/img/icon/edit.png" class="eimage"></a>' +
      '<a href="javascript:;" rel="' + params['questions_id'] + '" class="ajaxAnswers"><img src="' + base_url + 'assets/img/icon/online-2x.png" class="uimage"></a>' + 
      '<a href="javascript:;" rel="' + params['questions_id'] + '" class="ajaxDelete"><img src="' + base_url + 'assets/img/icon/remove.png" class="dimage"></a>');
      questionsTable.fnUpdate(rowData, parseInt(selectedRowId) - 1);
    }
  });
}

function ajax_answer_update(params) {
  $.ajax({
    type: "POST",
    dataType: 'json',
    url: base_url + "admin_questions/update_answer",
    data: params,
    success: function (response) {
      console.log(response);
      console.log(editingtrid);
      $("#answers-table tbody tr#answers-" + editingtrid).html(trcopy);
      $("#answers-table tbody tr:last-child").fadeIn("fast");
      $("#answers-table tbody tr#answers-" + editingtrid + " .answer").html(params['answer']);
      $("#answers-table tbody tr#answers-" + editingtrid + " .is_correct_answer").html(params['is_correct_answer'] == 1 ? "Yes" : "No");
      IsEditingAnswer = 0;
      editingtrid = 0;
    }
  });
}
