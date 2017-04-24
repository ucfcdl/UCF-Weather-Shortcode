var settingsLogic = function() {
  var $transientExp = jQuery('#ucf_weather_transient_expiration'),
      $useTransient = jQuery('#ucf_weather_use_transient'),
      checked = $useTransient.prop('checked'),
      $wrapperTransientExp = $transientExp.parents('tr');

    // If use transient is true
    if (checked) {
      $wrapperTransientExp.show();
    } else {
      $wrapperTransientExp.hide();
    }

    $useTransient.on('change', function(e) {
      $wrapperTransientExp.toggle();
    });
};

if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(function() {
    settingsLogic();
  });
}
