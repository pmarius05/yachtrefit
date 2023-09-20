jQuery(document).ready(function($) {
    $('#td-cw-categories-to-contractors').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();


        $.ajax({
            type: 'POST',
            url: tdcwao_contractors_categories_vars.ajaxurl,
            data: {
                action: 'process_categories_to_contractors',
                data: formData,
                save_categories_to_contractors_nonce: tdcwao_contractors_categories_vars.nonce,
            },
            success: function(response) {
                $('#td-categories-to-contractors-response-message').html(response);
            }
        });
    });
});
