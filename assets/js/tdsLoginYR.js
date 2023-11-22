jQuery(document).ready(function () {

    let $body = jQuery('body');


    $body.on('click', '#tds_register_button_yr', function( e ) {

        e.preventDefault();


        var $registerEmail = jQuery( '#tds_register_email_yr' ),
            $registerUser = jQuery( '#tds_register_user_yr' ),
            $registerPass = jQuery( '#tds_register_pass_yr' ),
            $registerRetypePass = jQuery( '#tds_register_retype_pass_yr' ),
            $registerRole = jQuery( '#tds_register_role_yr' ),
            $registerRegion = jQuery( '#tds_register_region_yr' ),
            $registerPhoneCountry = jQuery( '.iti__selected-flag' );
            $registerPhone = jQuery( '#tds_cwn_register_phoneTelCode' );
            $registerCaptchaEl = jQuery( '#gRecaptchaResponseR' );


        let registerPhoneCountryVal = $registerPhoneCountry.attr('title');
        let parts = registerPhoneCountryVal.split(": ");
        let phone_country = parts[0];
        let phone_code = parts[1];
        let phone_intl = registerPhoneCountryVal;
        // console.log( 'CountryCode: ' +  registerPhoneCountryVal );

        if ( $registerEmail.length && $registerUser.length && $registerPass.length && $registerRetypePass.length && $registerRole.length ) {
            var registerEmailVal = $registerEmail.val().trim(),
                registerUserVal = $registerUser.val().trim(),
                registerPassVal = $registerPass.val().trim(),
                registerRetypePassVal = $registerRetypePass.val().trim(),
                registerRoleVal = $registerRole.find('option:selected').val(),
                registerRegionVal = $registerRegion.find('option:selected').val(),
                registerPhoneVal = $registerPhone.val().trim();
                captchaKey = $registerCaptchaEl.attr('data-sitekey'),
                captchaToken = '';



            if ( !tdLogin.email_pattern.test( registerEmailVal )) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( window.td_email_incorrect );
                return;
            }

            if ( registerUserVal === '' ) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( window.td_user_incorrect );
                return;
            }

            if ( registerPassVal === '' ) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( window.td_pass_empty );
                return;
            }

            if ( !tdLogin.pass_pattern.test( registerPassVal )) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( window.td_pass_pattern_incorrect );
                return;
            }

            if ( registerPassVal !== registerRetypePassVal) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( window.td_retype_pass_incorrect );
                return;
            }

            if ( registerRoleVal === '' ) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( 'Please select a role.' );
                return;
            }

            if ( registerRegionVal === '' ) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( 'Please select a region.' );
                return;
            }



            if ( registerPhoneVal === '' ) {
                tdLogin.addRemoveClass( ['.td_display_err', 1, 'tds-s-notif-error'] );
                tdLogin.addRemoveClass( ['.td_display_err', 0, 'tds-s-notif-success'] );
                tdLogin.showHideMsg( 'Please select a phone number.' );
                return;
            }

            console.log('Region ' + registerRegionVal);

            if ( $registerCaptchaEl.length ){ //google recaptcha v3
                grecaptcha.ready(function() {
                    grecaptcha.execute(captchaKey, {action: 'submit'}).then(function(token) {
                        captchaToken = token;
                        tdLogin.doCustomAction( 'td_mod_subscription_register', registerEmailVal, registerUserVal, registerPassVal, registerRetypePassVal, captchaToken );
                    });
                });
            } else { //google recaptcha is disabled from panel
                //call ajax
                registerAction( registerEmailVal, registerUserVal, registerPassVal, registerRetypePassVal, registerRoleVal, registerRegionVal, registerPhoneVal, phone_country, phone_code, phone_intl );
            }

        }

    });



});



function registerAction( sent_email, sent_user, sent_pass, sent_retype_pass, sent_role, sent_region, sent_phone, phone_country, phone_code, phone_intl, sent_captcha ) {

    var data = {
        action: 'td_mod_subscription_register_yr',
        email: sent_email,
        user: sent_user,
        pass: sent_pass,
        retype_pass: sent_retype_pass,
        role: sent_role,
        region: sent_region,
        phone_country: phone_country,
        phone_code: phone_code,
        phone_intl: phone_intl,
        phone: sent_phone,
        captcha: sent_captcha
    };


    var $tdsRegisterDiv = jQuery('#tds-register-div'),
        $tdsRegisterButton = $tdsRegisterDiv.closest('.tds-block-inner').find('#tds_register_button_yr');

    $tdsRegisterDiv.find('.td_display_err').hide();
    $tdsRegisterButton.prop('disabled', true).addClass('tds-s-btn-saving');


    jQuery.ajax({
        type: 'POST',
        url: td_ajax_url,
        data: data,
        success: function (data, textStatus, XMLHttpRequest) {
            var td_data_object = jQuery.parseJSON(data),
                $tdsRegisterDiv = jQuery('#tds-register-div'),
                $tdsRegisterButton = $tdsRegisterDiv.closest('.tds-block-inner').find('#tds_register_button_yr');

            $tdsRegisterButton.removeClass('tds-s-btn-saving');

            if( 1 === td_data_object[1] ) {

                tdLogin.addRemoveClass(['.td_display_err', 1, 'td_display_msg_ok']);
                tdLogin.addRemoveClass(['.td_display_err', 1, 'tds-s-notif-success']);
                tdLogin.addRemoveClass(['.td_display_err', 0, 'tds-s-notif-error']);

                var urlParams = new URLSearchParams(new URL(window.location.href).search.slice(1));
                if (urlParams.has('ref_url')) {

                    var $tdsContinueSubscription = $tdsRegisterDiv.find('#tds-continue-subscription'),
                        $tdsRegisterButton = $tdsRegisterDiv.closest('.tds-block-inner').find('#tds_register_button_yr'),
                        $tdsPageSwitch = $tdsRegisterDiv.closest('.tds-block-inner').find('.tds-s-cal-page-switch');

                    if ($tdsContinueSubscription.length) {
                        $tdsContinueSubscription.show().attr('href', window.atob(urlParams.get('ref_url').replace('=', '')));
                    }
                    if ($tdsRegisterButton.length) {
                        $tdsRegisterButton.hide();
                    }
                    if ($tdsPageSwitch.length) {
                        $tdsPageSwitch.hide();
                    }

                } else {

                    var $tdsRegisterButton = $tdsRegisterDiv.closest('.tds-block-inner').find('#tds_register_button_yr'),
                        $tdsPageSwitch = $tdsRegisterDiv.closest('.tds-block-inner').find('.tds-s-cal-page-switch'),
                        $tdsMyAccount = $tdsRegisterDiv.closest('.tds-block-inner').find('#tds-my-account');

                    if ($tdsRegisterButton.length) {
                        $tdsRegisterButton.hide();
                    }
                    if ($tdsPageSwitch.length) {
                        $tdsPageSwitch.hide();
                    }
                    if ($tdsMyAccount.length) {
                        $tdsMyAccount.show();
                    }

                }

                $tdsRegisterDiv.find('.tds-s-form-content .tds-s-fc-inner').hide();

            } else {
                tdLogin.addRemoveClass(['.td_display_err', 0, 'td_display_msg_ok']);
                tdLogin.addRemoveClass(['.td_display_err', 0, 'tds-s-notif-success']);
                tdLogin.addRemoveClass(['.td_display_err', 1, 'tds-s-notif-error']);
            }
            tdLogin.showHideMsg(td_data_object[2]);
        },
        error: function (MLHttpRequest, textStatus, errorThrown) {
            //console.log(errorThrown);
        }
    });

}