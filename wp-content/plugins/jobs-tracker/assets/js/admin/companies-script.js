jQuery(document).ready(function($) {
  // Add click event listener to buttons with class "action" within an element with class "about-clients"
  $('.jobstracker .action').on('click', function() {
      // Set the value of an element with the id "id" based on the data-id attribute of the clicked button
      ID = $(this).attr('data-id');
      step = $(this).attr('data-bs-title');
      $('#ID').val(ID);
      $('#step').val(step);
      $('#jobstracker-content').submit();
  });
});