// Set highlight animation delay (higher the value longer will be the animation)
var saveAnimationDelay = 3000;
var deleteAnimationDelay = 1000;

// 2 effects available available 1) slide 2) flash
var videoTable;

$(document).ready(function () {

    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };

    videoTable = $('#videos-table').dataTable({
        "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
        "aoColumnDefs": [
            {"bSortable": false, "aTargets": [0, 1, 2, 3]}
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
    $('#videos-table_wrapper .dataTables_filter input').addClass("input-medium ");
    $('#videos-table_wrapper .dataTables_length select').addClass("select2-wrapper span12");
    $("#videos-table_wrapper div.toolbar").html('<div class="table-tools-actions"><button class="btn btn-primary" style="margin-left:12px" id="addVideo" data-toggle="modal" data-target="#addVideoModal">Add</button></div>');

    $('#addVideo').on("click", function (e) {
        // $('#video_url').val("");
        // $('#search_video_key').val("");
        // $('ul.thumbnails').html("");
    });

    // set images for edit and delete
    $(".dimage").attr("src", deleteImage);

    // Delete record
    $(document).on("click", "#videos-table .ajaxDelete", function (e) {
        var id = $(this).attr("rel");
        if (id) {
            if (confirm("Do you really want to delete record ?")) {
                ajax_video_delete(id, $(this).parent().parent().index('tr') - 1);
            }
        }
    });

    $(document).on("click", ".one-video", function () {
        $('.one-video').removeClass('selected');
        $(this).addClass('selected');
        $('#video_url').val("http://www.youtube.com/watch?v=" + $(this).attr('video_id'));
    });

    $(document).on("dblclick", ".one-video", function () {
        $('.one-video').removeClass('selected');
        $(this).addClass('selected');
        ajax_video_add("http://www.youtube.com/watch?v=" + $(this).attr('video_id'));
    });

    $(document).on("click", "#search_video", function () {
        search_video();
    });

    $(document).on("keypress", "#search_video_key", function (e) {
        if (e.which == 13)
            search_video();
    });

    $(document).on("click", "#save_video", function () {
        var url = $.trim($('#video_url').val());
        if (url != "") {
            ajax_video_add(url);
        }
    });

    $('.controller .reload').click(function () {
        var el = $(this).parent().parent().parent();
        blockUI(el);
        window.setTimeout(function () {
            unblockUI(el);
        }, 1000);
    });

});

function search_video() {
    var keywords = $('#search_video_key').val();
    if ($.trim(keywords) != "") {
        ajax_video_search(keywords);
    }
}

function blockUI(el) {
    $(el).block({
        message: '<div class="loading-animator"></div>',
        css: {
            border: 'none',
            padding: '2px',
            backgroundColor: 'none'
        },
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.3,
            cursor: 'wait'
        }
    });
}

// wrapper function to  un-block element(finish loading)
function unblockUI(el) {
    $(el).unblock();
}

function ajax_video_search(keywords) {
    var el = $('#addVideoModal ul.thumbnails');
    blockUI(el);
    $.ajax({
        type: "POST",
        url: base_url + "admin_videos/search",
        data: {keywords: keywords, max_count: 12},
        dataType: 'json',
        success: function (videos) {
            var html = '';
            videos.forEach(function (video) {
                html += '<li class="span2 one-video" video_id="' + video['videoId'] + '">';
                html += '<div class="thumbnail">';
                html += '<div class="photo">';
                html += '<a href="javascript:;" target="_blank">';
                html += '<img class="lazy" src="' + video['thumbnail']['url'] + '" alt="' + video['title'] + '" style="display: inline;">';
                html += '</a></div>';
                html += '<div class="caption">';
                html += '<h5 class="index-thumbnail-title" rel="tooltip">';
                html += '<a href="http://www.youtube.com/watch?v=' + video['videoId'] + '" target="_blank">';
                html += video['title'];
                html += '</a></h5>';
                html += '<div class="clearfix"></div>';
                html += '</div></div></li>';
            });
            $('ul.thumbnails').html(html);
            unblockUI(el);
        },
        error: function (response) {
            alert(response['error']);
            unblockUI(el);
        }
    });
}

function ajax_video_delete(id, curRow) {
    $.ajax({
        type: "POST",
        url: base_url + "admin_videos/delete",
        data: {id: id},
        success: function (result) {
            videoTable.fnDeleteRow(curRow);
        }
    });
}

function ajax_video_add(url) {
    var el = $('#addVideoModal ul.thumbnails');
    blockUI(el);
    $.ajax({
        type: "POST",
        url: base_url + "admin_videos/add",
        data: {url: url},
        dataType: "json",
        success: function (response) {
            $('#addVideoModal').modal('hide');
            var rowsTotal = videoTable.fnGetData().length;
            var rowData = [];
            rowData.push(parseInt(rowsTotal + 1));
            rowData.push('<a href="http://www.youtube.com/watch?v=' + response['link'] + '" target="_blank"><img width="120px" height="80px" src="' + response['image'] + '"></a>');
            rowData.push(response['title']);
            rowData.push('<a href="javascript:;" rel="' + response['new_id'] + '" class="ajaxDelete"><img src="' + base_url + 'assets/img/icon/remove.png" class="dimage"></a>');
            videoTable.fnAddData(rowData);
            unblockUI(el);
        }
    });
}
