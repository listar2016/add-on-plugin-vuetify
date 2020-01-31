jQuery( window ).on( 'elementor:init', function() {
    elementor.channels.editor.on('reset_rating', function(e){
        let confirmReset = confirm("Are you sure you want to reset ratings");
        if (!confirmReset) {
            return;
        }
        var widgetId = jQuery("[data-setting='eeao_widget_id']").children(":selected").val();
        widgetId = parseInt(widgetId);
        elementorCommon.ajax.addRequest( 'eeao_post_rating_reset', {
            data: {
                widget_id: widgetId
            },
            success: function(res){
                var message = `<div class='${res.success ? "reset-rating-message message-success": "reset-rating-message message-error"}'>${res.message}</div>`
                jQuery('.elementor-control-eeao_reset_rating_button').children('.elementor-control-content').append(message);
                if(res.success){
                    jQuery("input[data-setting='eeao_reset_rating_hidden']").trigger('input');   
                }
                setTimeout(function(){
                    jQuery('.reset-rating-message').remove();
                }, 4000)
            }
        }, true);
    });
});
