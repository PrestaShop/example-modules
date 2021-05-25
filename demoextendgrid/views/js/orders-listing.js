$(() => {
  $(document).on('click', '.grid-mark-row-link', (event) => {
    event.preventDefault();
    var $currentTarget = $(event.currentTarget)
    $.post($currentTarget.data('url')).then((data) => {
      // For example we mark the icon by green color when the ajax succeeds
      $currentTarget.find('i').addClass('text-success');
    });
  });
});
