// init variables

var dtTable;
var columns = new Array("source", "target", "direction", "image", "audio");
var placeholder = new Array("Enter source sentence", "Enter target sentence", "", "", "");
var inputType = new Array("textarea", "textarea", "select", "image", "audio");
var sentences_table = "sentences-table";
var selectOpt = new Array("EN->CN", "EN->GE", "CN->EN", "GE->EN");
;

// Set button class names
var savebutton = "ajaxSave";
var deletebutton = "ajaxDelete";
var editbutton = "ajaxEdit";
var updatebutton = "ajaxUpdate";
var cancelbutton = "cancel";

// var base_url = location.protocol + "//" + location.hostname + (location.port && ":" + location.port) + "/olining_admin/";
var saveImage = base_url + "assets/img/icon/save.png"
var editImage = base_url + "assets/img/icon/edit.png"
var deleteImage = base_url + "assets/img/icon/remove.png"
var cancelImage = base_url + "assets/img/icon/back.png"
var updateImage = base_url + "assets/img/icon/save.png"

// Set highlight animation delay (higher the value longer will be the animation)
var saveAnimationDelay = 2000;
var deleteAnimationDelay = 1000;

// 2 effects available available 1) slide 2) flash
var effect = "flash";

var trcopy;
var editing = 0;
var editingtrid = 0;
var inputs = ':checked,:selected,:text,textarea,select';

$(document).ready(function () {

  $('#quick-access .btn-cancel').click(function () {
    $("#quick-access").css("bottom", "-135px");
  });
  $('#quick-access .btn-add').click(function () {
    // fnClickAddRow();
    // $("#quick-access").css("bottom","-95px");
  });

  var responsiveHelper = undefined;
  var breakpointDefinition = {
    tablet: 1024,
    phone: 480
  };

  dtTable = $('#sentences-table').dataTable({
    "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
    "aoColumnDefs": [
      {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6]}
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
    },
    "processing": true,
    "serverSide": true,
    "ajax": base_url + "admin_sentences/get_sentence_list"
  });

  $("#sentences-table_wrapper div.toolbar").html('<div class="table-tools-actions"><button class="btn btn-primary" style="margin-left:12px" id="addSentence">Add</button></div>');

  $('#addSentence').on("click", function () {
    $("." + cancelbutton).trigger("click");
    $("#quick-access").css("bottom", "0");
  });

  $('#sentences-table_wrapper .dataTables_filter input').addClass("input-medium ");
  $('#sentences-table_wrapper .dataTables_length select').addClass("select2-wrapper span12");


  var chapters_id = location.href.substr(location.href.lastIndexOf('/') + 1);
  // set images for edit and delete
  $(".eimage").attr("src", editImage);
  $(".dimage").attr("src", deleteImage);

  // Delete record
  $(document).on("click", "#sentences-table ." + deletebutton, function () {

    var id = $(this).attr("rel");
    if (id) {
      if (confirm("Do you really want to delete record ?")) {
        ajax_sentence_delete(id, $(this).parent().parent().index('tr') - 1);
      }
    }
  });

  // Add new record
  $("#quick-access ." + savebutton).on("click", function () {
    var validation = 1;

    var formData = new FormData();
    formData.append("chapters_id", chapters_id);
    if ($('input[name="image"]')[0].files[0] != undefined)
      formData.append("image", $('input[name="image"]')[0].files[0]);
    if ($('input[name="audio"]')[0].files[0] != undefined)
      formData.append("audio", $('input[name="audio"]')[0].files[0]);

    var source = $.trim($('textarea[name="source"]').val());
    if (source != '') {
      formData.append('source', source);
    } else {
      $('textarea[name="source"]').focus();
      return;
    }
    var target = $.trim($('textarea[name="target"]').val());
    if (target != '') {
      formData.append('target', target);
    } else {
      $('textarea[name="target"]').focus();
      return;
    }
    var direction = $.trim($('select[name="direction"]').val());
    if (direction != '') {
      formData.append('direction', direction);
    } else {
      $('select[name="direction"]').focus();
      return;
    }
    ajax_sentence_add(formData);
  });

  // Update record
  $(document).on("click", "#sentences-table ." + editbutton, function () {

    var id = $(this).attr("rel");
    if (id && editing == 0) {
      // hide editing row, for the time being
      // $("#"+sentences_table+" tbody tr:last-child").fadeOut("fast");
      $("#quick-access").css("bottom", "-115px");

      var html = "<td>" + $("#" + sentences_table + " tbody tr#sentence-" + id + " td:first-child").html() + "</td>";
      for (var i = 0; i < columns.length; i++) {
        // fetch value inside the TD and place as VALUE in input field
        var val = $("#" + sentences_table + " tbody tr#sentence-" + id + " td." + columns[i]).html();
        var input = createInput(i, val);
        html += '<td class="ajaxReq">' + input + '</td>';
      }
      html += '<td class="crud-actions"><a href="javascript:;" rel="' + id + '" class="' + updatebutton + '"><img src="' + updateImage + '" class="uimage"></a> <a href="javascript:;" rel="' + id + '" class="' + cancelbutton + '"><img src="' + cancelImage + '" class="cimage"></a></td>';
      // Before replacing the TR contents, make a copy so when user clicks on
      trcopy = $("#" + sentences_table + " tbody tr#sentence-" + id).html();
      $("#" + sentences_table + " tbody tr#sentence-" + id).html(html);

      // set editing flag
      editing = 1;
      editingtrid = id;
    }
  });

  $(document).on("click", "#sentences-table ." + cancelbutton, function () {
    var id = $(this).attr("rel");
    $("#" + sentences_table + " tbody tr#sentence-" + id).html(trcopy);
    $("#" + sentences_table + " tbody tr:last-child").fadeIn("fast");
    editing = 0;
    editingtrid = 0;
  });

  // Save button click on complete row update event
  $(document).on("click", "#sentences-table ." + updatebutton, function () {
    var id = $(this).attr("rel");
    var validation = 1;

    var formData = new FormData();
    formData.append("sentences_id", id);
    formData.append("chapters_id", chapters_id);
    if ($('input[name="image"]')[0].files[0] != undefined)
      formData.append("image", $('input[name="image"]')[0].files[0]);
    if ($('input[name="audio"]')[0].files[0] != undefined)
      formData.append("audio", $('input[name="audio"]')[0].files[0]);
    var source = $.trim($('textarea[name="source"]').val());
    if (source != '') {
      formData.append('source', source);
    } else {
      $('textarea[name="source"]').focus();
      return;
    }
    var target = $.trim($('textarea[name="target"]').val());
    if (target != '') {
      formData.append('target', target);
    } else {
      $('textarea[name="target"]').focus();
      return;
    }
    var direction = $.trim($('select[name="direction"]').val());
    if (direction != '') {
      formData.append('direction', direction);
    } else {
      $('select[name="direction"]').focus();
      return;
    }
    ajax_sentence_update(formData);
    editing = 0;
    editingtrid = 0;
  });

});

function createInput(i, str) {
  str = typeof str !== 'undefined' ? str : null;
  //alert(str);
  var input;
  if (inputType[i] == "text") {
    input = '<input type=' + inputType[i] + ' name=' + columns[i] + ' placeholder="' + placeholder[i] + '" value=' + str + ' >';
  } else if (inputType[i] == "textarea") {
    input = '<textarea name=' + columns[i] + ' placeholder="' + placeholder[i] + '">' + str + '</textarea>';
  } else if (inputType[i] == "select") {
    input = '<select name=' + columns[i] + ' value="' + str + '">';
    for (i = 0; i < selectOpt.length; i++) {
      var selected = "";
      if (str == selectOpt[i])
        selected = "selected";
      input += '<option value="' + selectOpt[i] + '" ' + selected + '>' + selectOpt[i] + '</option>';
    }
    input += '</select>';
  } else if (inputType[i] == "image") {
    input = '<input type="file" accept="image/*" name=' + columns[i] + ' placeholder="' + placeholder[i] + '" >';
  } else if (inputType[i] == "audio") {
    input = '<input type="file" accept="audio/*" name=' + columns[i] + ' placeholder="' + placeholder[i] + '" >';
  }
  return input;
}

function ajax_sentence_delete(id, curRow) {
  $.ajax({
    type: "POST",
    url: base_url + "admin_sentences/delete",
    data: {id: id},
    success: function (id) {
      dtTable.fnDeleteRow(curRow);
    }
  });
}

function ajax_sentence_update(params) {
  $.ajax({
    type: "POST",
    url: base_url + "admin_sentences/update",
    data: params,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      $("." + cancelbutton).trigger("click");
      if (response['success'] == 1) {
        var html = "<td>" + $("#" + sentences_table + " tbody tr#sentence-" + response['id'] + " td:first-child").html() + "</td>";
        for (var i = 0; i < columns.length; i++) {
          html += '<td class="' + columns[i] + '">';
          if (columns[i] == "image") {
            if (response[columns[i]] != undefined && response[columns[i]] != '')
              html += '<img width="120px" height="80px" src="' + response[columns[i]] + '">';
          } else if (columns[i] == "audio") {
            if (response[columns[i]] != undefined && response[columns[i]] != '')
              html += '<video width="250px" height="30" controls><source src="' + response[columns[i]] + '" type="video/mp4"/></video>';
          } else {
            html += response[columns[i]];
          }
          html += '</td>';
        }
        html += '<td class="crud-actions"><a href="javascript:;" rel="' + response["id"] + '" class="' + editbutton + '"><img src="' + editImage + '" class="eimage"></a> <a href="javascript:;" rel="' + response["id"] + '" class="' + deletebutton + '"><img src="' + deleteImage + '" class="dimage"></a></td>';
        // Append new row as a second last row of a table
        $("#" + sentences_table + " tr#sentence-" + response['id']).html(html);

        if (effect == "slide") {
          // Little hack to animate TR element smoothly, wrap it in div and replace then again replace with td and tr's ;)
          $("#" + sentences_table + " tr#sentence-" + response['id']).find('td')
            .wrapInner('<div style="display: none;" />')
            .parent()
            .find('td > div')
            .slideDown(700, function () {
              var $set = $(this);
              $set.replaceWith($set.contents());
            });
        }
        else if (effect == "flash")
          $("#" + sentences_table + " tr#sentence-" + response['id']).effect("highlight", {color: '#acfdaa'}, 100);
        else
          $("#" + sentences_table + " tr#sentence-" + response['id']).effect("highlight", {color: '#acfdaa'}, 1000);

        // Blank input fields
        $(document).find("#" + sentences_table).find(inputs).filter(function () {
          // check if input element is blank ??
          this.value = "";
          $(this).removeClass("success").removeClass("error");
        });
      }

    }
  });
}

function ajax_sentence_add(params) {
  $.ajax({
    type: "POST",
    url: base_url + "admin_sentences/add",
    data: params,
    dataType: "json",
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      var rowsTotal = dtTable.fnGetData().length;
      var rowData = [];
      rowData.push(parseInt(rowsTotal + 1));
      for (var i = 0; i < columns.length; i++) {
        if (columns[i] == "image") {
          if (response[columns[i]] != undefined && response[columns[i]] != '')
            rowData.push('<img width="120px" height="80px" src="' + response[columns[i]] + '">');
          else
            rowData.push("");
        } else if (columns[i] == "audio") {
          if (response[columns[i]] != undefined && response[columns[i]] != '')
            rowData.push('<video width="250px" height="30px" controls><source src="' + response[columns[i]] + '" type="video/mp4"/></video>');
          else
            rowData.push("");
        } else {
          rowData.push(response[columns[i]]);
        }
      }
      rowData.push('<a href="javascript:;" rel="' + response["id"] + '" class="ajaxEdit"><img src="' + editImage + '" class="eimage"></a> <a href="javascript:;" rel="' + response["id"] + '" class="' + deletebutton + '"><img src="' + deleteImage + '" class="dimage"></a>');
      if (response['success'] == 1) {
        dtTable.fnAddData(rowData);
        $('#quick-access textarea[name="source"]').val("");
        $('#quick-access textarea[name="target"]').val("");
        $('#quick-access input[name="image"]').val("");
        $('#quick-access input[name="audio"]').val("");
      }
    }
  });
}
