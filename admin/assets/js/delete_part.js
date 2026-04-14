$(document).ready(function () {
  $('.delete-part').click(function () {
    const id = $(this).attr('id');
    $.ajax({
      type: 'GET',
      url: 'processes/delete_part.php',
      data: { id: id },
      success: function () {}
    });

    $(this)
      .parents('.part-row')
      .animate({ backgroundColor: '#fbc7c7' }, 'fast')
      .animate({ opacity: 'hide' }, 'slow');
  });
});
