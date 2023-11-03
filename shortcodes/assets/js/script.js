jQuery(document).ready(function() {
    console.log('ScripT file');

    // console.log('mVar ' + myJsVar);

    jQuery('#reply-message').on('click', function (){
        jQuery('.textarea-pop').css({
            "position" : "absolute",
            "width" : "100%",
            "transition" : "all 0.2s",
            "bottom" : "0"
        });
        jQuery("#reply-message").css({
            "display" : "none"
        });
        jQuery("#send-message").css({
            "display" : "flex"
        });
    });
    jQuery('.arrow-down').on('click', function (){
        jQuery('.textarea-pop').css({
            "position" : "relative",
            "width" : "100%",
            "transition" : "all 0.2s",
            "bottom" : "0px"
        });
        jQuery("#reply-message").css({
            "display" : "flex"
        });
        jQuery("#send-message").css({
            "display" : "none"
        });
    });

    jQuery("#send-the-message").on('click', function () {

        let message = document.getElementById('the-message-to-be-sent').value;
        let chat_id = document.getElementById('the-chat-id').value;
        let other_user = document.getElementById('the-other-user').value;
        let image_to_send = document.getElementById('file-input').value;
       console.log(chat_id);
       //  if (image_to_send.length == 0) {
       //      console.log('image is empty');
       //  }

        jQuery.ajax({
          url: my_ajax_object.ajax_url,
          type: "POST",
          data: {
              action: 'tdcwn_send_message',
              message: message,
              other_user: other_user,
              chat_id: chat_id,
              image: image_to_send
          },
          // success: function() {
          //     jQuery('.textarea-pop').css({
          //         "position" : "absolute",
          //         "width" : "100%",
          //         "transition" : "all 1s",
          //         "bottom" : "auto"
          //     });
          //     jQuery("#reply-message").css({
          //         "display" : "flex"
          //     });
          //     jQuery("#send-message").css({
          //         "display" : "none"
          //     });
          //
          //     jQuery(".tdcwn-message-success").css({
          //         "display" : "block"
          //     });
          //     setTimeout(function() {
          //         jQuery(".tdcwn-message-success").css({
          //             "display" : "none"
          //         });
          //     }, 5000);
          //
          //     console.log('Message sent! - file');
          //     // console.log(success);
          // },
            success: function(response) {
                alert(JSON.stringify(response));
            },
          error: function() {
              jQuery(".tdcwn-message-error").css({
                  "display" : "block"
              });
              setTimeout(function() {
                  jQuery(".tdcwn-message-error").css({
                      "display" : "none"
                  });
              }, 5000);
              console.log( 'An error occurred' );
          }
        });
    });


    jQuery(".toggle-arrow").on("click", function(){
       if (jQuery(this).hasClass('closed')) {
           jQuery(this).parent().css({
               "height" : "auto",
           });
           jQuery(this).removeClass("closed");
       }else {
           jQuery(this).parent().css({
               "height" : "32px",
               "overflow" : "hidden",
           })
           jQuery(this).addClass("closed");
       }
    });

});