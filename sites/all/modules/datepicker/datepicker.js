(function ($) {

Drupal.behaviors.datepicker = {
 attach: function() {
   for (var id in Drupal.settings.datePopup) {
     $('#'+ id).each(function() {
       datePopup = Drupal.settings.datePopup[id];
       switch (datePopup.func) {
         case 'datepicker-inline':
           $(this).wrap('<div id="' + id + '-wrapper" />');
           $(this).datepicker(datePopup.settings);
           $(this).attr('readonly','true');
           break;
       }
     });
   }
 }
}

})(jQuery);
