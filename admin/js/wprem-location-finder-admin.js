(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    jQuery(document).ready(function($) {

        $(document).on("click", ".wprem-location-marker", function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            wp.media.editor.send.attachment = function(props, attachment) {
                console.log("here: "+attachment.url);
                $('#wprem-locations-marker').val(attachment.url);
                $("#wprem-locations-marker-preview").html('<img src="'+attachment.url+'" style="max-width:40px" />');

                // image preview
                //$('.meta-image-preview').eq($(".ui-state-default").index(parent)).attr('src', attachment.url);
                wp.media.editor.send.attachment = send_attachment_bkp;
            }

            wp.media.editor.open();
            return false;
        });

        $(document).on("click", ".wprem-location-marker-remove", function(e) {
            e.preventDefault();
            $("#wprem-locations-marker-preview img").remove();
            $('#wprem-locations-marker').val('');
        });

        $('.wprem-radius-color').wpColorPicker();
    });

})(jQuery);