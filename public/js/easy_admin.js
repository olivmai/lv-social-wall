$( function () {
   // open all play actions in new tab / window
   $('a.action-play_event').attr('target', '_blank');

   // custom datepicker
   //const flatpickr = require('flatpickr');
   //flatpickr(".custom-datepicker");
   //$('.custom-datepicker.hidden').hide();
   $(".custom-datepicker").flatpickr({
      'locale': 'fr',
      'enableTime': true,
      'time_24hr': true,
      'allowInput': true
   });

   $('.nullable-control>label>input').each(function () {
      var checkbox = $(this);
      var dateField = checkbox.parent().parent().siblings('.custom-datepicker.hidden');
      if (checkbox.is(':checked')) {
         dateField.val('');
         dateField.slideToggle();
      }
   });

   // override enable / disable date field with checkbox
   $('.nullable-control>label>input').on('change', function () {
      var checkbox = $(this);
      var dateField = checkbox.parent().parent().siblings('.custom-datepicker.hidden');
      if (checkbox.is(':checked')) {
         dateField.val('');
      }
      dateField.slideToggle();
   });
});