var base_url = location.protocol + "//" + location.hostname + (location.port && ":" + location.port);
var Admin = {

  toggleLoginRecovery: function(){
    var is_login_visible = $('#modal-login').is(':visible');
    (is_login_visible ? $('#modal-login') : $('#modal-recovery')).slideUp(300, function(){
      (is_login_visible ? $('#modal-recovery') : $('#modal-login')).slideDown(300, function(){
        $(this).find('input:text:first').focus();
      });
    });
  }
   
};

$(function(){

  $('.toggle-login-recovery').click(function(e){
    Admin.toggleLoginRecovery();
    e.preventDefault();
  });

  $("#type").on('change', function() {
    if ($(this).val() == 1) {
      $("#store_information").show(500);
    } else {
      $("#store_information").hide(300);
    }
  });

  $("#categories_id").on('change', function() {
    $("#sub_categories_id > option").remove();
    var categories_id = $("#categories_id").val();
    $.ajax({
      type: "GET",
      url: base_url + "/offers_admin/admin/offers/get_sub_categories",
      data: {categories_id:categories_id},
      success: function(sub_categories)
      {
        $.each(sub_categories, function(id, category)
        {
          var opt = $('<option />');
          opt.val(category.categories_id);
          opt.text(category.name);
          $('#sub_categories_id').append(opt);
        })
      }
    })
  });

  function addSentence() {

  }

  function editSentence(sentence_id) {
    
  }

  function saveSentence(sentence_id) {

  }

});
