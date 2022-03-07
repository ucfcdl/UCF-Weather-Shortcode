const settingsLogic = function () {
  const $transientExp = jQuery('#ucf_weather_transient_expiration'),
    $useTransient = jQuery('#ucf_weather_use_transient'),
    $wrapperTransientExp = $transientExp.parents('tr'),
    checked = $useTransient.prop('checked');

  // If use transient is true
  if (checked) {
    $wrapperTransientExp.show();
  } else {
    $wrapperTransientExp.hide();
  }

  $useTransient.on('change', () => {
    $wrapperTransientExp.toggle();
  });
};

if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(() => {
    settingsLogic();
  });
}
