/*!
  * Scout Checkr.
  * Copyright 2022 Spiral Scout (https://spiralscout.com/)
  * Licensed under MIT
  */

var countdown;

var redirectResponce;

jQuery(document).ready(function () {
    if (!jQuery('.spiral-status-step.show').length) {
        jQuery('.spiral-status-step').first().addClass('show');
    }
    var configStepIndex  = jQuery('.grafana-config-step').index();

    function skipAppearance() {
        if (jQuery('.spiral-status-step.show').index() < configStepIndex) {
            jQuery('.skip-instructions').removeClass('hide');
        } else {
            jQuery('.skip-instructions').addClass('hide');
        }
        if (jQuery('.spiral-status-step.show').index() === configStepIndex) {
            jQuery('.spiral-status-button.next').addClass('hide');
        } else {
            jQuery('.spiral-status-button.next').removeClass('hide');
        }
    }

    jQuery('body').on('click', '.skip-instructions', function () {
        jQuery('.skip-instructions').addClass('hide');
        jQuery('.spiral-status-step.show').removeClass('show');
        jQuery('.spiral-status-step').eq(configStepIndex).addClass('show');
    });

    jQuery('body').on('click', '.spiral-status-button', function () {
        let currentStep = jQuery('.spiral-status-step.show');
        jQuery('.spiral-status-button.prev').removeClass('hide');

        if (jQuery(this).hasClass('next')) {
            if (currentStep.next().length) {
                currentStep.removeClass('show');
                currentStep.next().addClass('show');
            }
            if (currentStep.next().next().length === 0) {
                jQuery('.spiral-status-button.next').addClass('hide');
            }
        }
        if (jQuery(this).hasClass('prev')) {
            jQuery('.spiral-status-button.next').removeClass('hide');
            if (currentStep.prev().length) {
                currentStep.removeClass('show');
                currentStep.prev().addClass('show');
            }
            if (currentStep.prev().prev().length === 0) {
                jQuery('.spiral-status-button.prev').addClass('hide');
            }
        }

        skipAppearance();
    });
});

(function (jQuery) {
    jQuery(document).ready(function () {
        jQuery('.input-wrapper input').on('change, keydown, focusout, blur, input', function () {
            if (jQuery(this).val() !== '') {
                jQuery(this).parents('.input-error').removeClass('input-error');
            }
        });

        jQuery('#grafana-submit').click(function (e) {
            e.preventDefault();

            let inputs = jQuery('#spiral-status-form .input-wrapper input');
            let errorLog = false;

            inputs.each(function () {
                if (jQuery(this).val() === '') {
                    jQuery(this).parents('.input-wrapper').addClass('input-error');
                    errorLog = true;
                }
            });

            if (errorLog) {
                return;
            }

            jQuery('.spiral-status-wrapper').addClass('preloading');
            jQuery('.alert-danger').addClass('hide');

            sendAjax();
        });
    });
})(jQuery)


function sendAjax(){
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'wp_status_install',
            api_grafana_dashboard_title: jQuery('#api-grafana-dashboard-title').val(),
            api_grafana: jQuery('#api-grafana').val(),
            api_grafana_dashboard_url: jQuery('#api-grafana-dashboard-url').val(),
            wpnonce_field: jQuery('#wordpress_plugin_grafana_settings_nonce').val(),
        },
        method: 'POST', //Post method
        success: function (response) {
            console.log(response);
            let status = ['error', 'fill_all_fields'];
            if (!status.includes(response)){
                jQuery('.spiral-status-step').removeClass('show');
                jQuery('.spiral-status-buttons').addClass('hide');
                jQuery('.spiral-status-step').last().addClass('show');
                jQuery('#grafana-panel-link').attr('href', response);
                jQuery('#grafana-panel-link').text(response);
                jQuery('.spiral-status-link').html('<h3>If connecting is successful, you\'ll now be redirected to your Grafana Dashboard page and can start to monitor your site’s health. <br><br>If nothing happened after <span id="redirect-countdown">5 seconds</span>, you can follow your link to Grafana Dashboard:<br><a id="grafana-panel-link" href="' + response + '" target="_blank">' + response + '</a><br><br> For further questions please contact <a href="mailto:team@spiralscout.com">team@spiralscout.com</a> for additional help and support.</h3>');
                jQuery('#spiral-status-form input').attr('disabled', 'disabled');

                countdown = 5;

                redirectResponce = response;

                window.setTimeout('redirectCountdown()', 1000);
            }
            if(response === 'error'){
                jQuery('.alert-danger').removeClass('hide');
            }
            jQuery('.spiral-status-wrapper').removeClass('preloading');
        },
        error: function (error) {
            jQuery('.spiral-status-wrapper').removeClass('preloading');
            jQuery('.alert-danger').removeClass('hide');

        }
    })
}

function redirectCountdown() {
    countdown--;

    if (countdown < 0) {
        window.location.href = redirectResponce;
    } else {
        if (countdown == 1) {
            jQuery('#redirect-countdown').html(countdown + ' second');
        } else {
            jQuery('#redirect-countdown').html(countdown + ' seconds');
        }

        window.setTimeout('redirectCountdown()', 1000);
    }
}
