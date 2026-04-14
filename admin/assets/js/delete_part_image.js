document.addEventListener('DOMContentLoaded', function () {
  var buttons = document.querySelectorAll('.delete-part-image');

  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      var imageId = this.getAttribute('data-image-id');
      if (!imageId) {
        return;
      }

      if (!confirm('Delete this image?')) {
        return;
      }

      var card = this.closest('.part-image-card');

      fetch('processes/delete_part_image.php?id=' + encodeURIComponent(imageId), {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data && data.success) {
            if (card) {
              card.remove();
            }
            return;
          }

          alert((data && data.message) ? data.message : 'Failed to delete image.');
        })
        .catch(function () {
          alert('Failed to delete image. Please try again.');
        });
    });
  });
});
