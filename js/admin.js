/**
 * Admin JavaScript for Newspaper Web Theme Options
 */
(function($) {
    'use strict';

    // DOM Ready
    $(function() {
        // Initialize color pickers
        if ($.fn.wpColorPicker) {
            $('.color-picker').wpColorPicker();
        }

        // Initialize Select2
        if ($.fn.select2) {
            $('.newspaperweb-select2').select2({
                width: '350px'
            });
        }

        // Media uploader for logo
        $('#upload_logo_button').on('click', function(e) {
            e.preventDefault();
            
            var frame = wp.media({
                title: newspaperweb_admin.logo_title,
                multiple: false
            });

            frame.open().on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                
                // Set logo URL to hidden field
                $('#newspaperweb_site_logo').val(attachment.url);
                
                // Update preview
                $('.logo-preview-wrapper').html('<div class="logo-preview"><img src="' + attachment.url + '" alt="' + newspaperweb_admin.logo_alt + '" style="max-width: 200px; height: auto;" /></div>');
                
                // Add remove button if it doesn't exist
                if ($('#remove_logo_button').length === 0) {
                    $('.newspaperweb-logo-uploader').append('<input type="button" id="remove_logo_button" class="button" value="' + newspaperweb_admin.remove_logo + '" />');
                }
            });
        });

        // Media uploader for favicon
        $('#upload_favicon_button').on('click', function(e) {
            e.preventDefault();
            
            var frame = wp.media({
                title: newspaperweb_admin.favicon_title,
                multiple: false
            });

            frame.open().on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                
                // Set favicon URL to hidden field
                $('#newspaperweb_site_favicon').val(attachment.url);
                
                // Update preview
                $('.favicon-preview-wrapper').html('<div class="favicon-preview"><img src="' + attachment.url + '" alt="' + newspaperweb_admin.favicon_alt + '" style="max-width: 32px; height: auto;" /></div>');
                
                // Add remove button if it doesn't exist
                if ($('#remove_favicon_button').length === 0) {
                    $('.newspaperweb-favicon-uploader').append('<input type="button" id="remove_favicon_button" class="button" value="' + newspaperweb_admin.remove_favicon + '" />');
                }
            });
        });

        // Remove logo
        $(document).on('click', '#remove_logo_button', function(e) {
            e.preventDefault();
            
            $('#newspaperweb_site_logo').val('');
            $('.logo-preview-wrapper').empty();
            $(this).remove();
        });

        // Remove favicon
        $(document).on('click', '#remove_favicon_button', function(e) {
            e.preventDefault();
            
            $('#newspaperweb_site_favicon').val('');
            $('.favicon-preview-wrapper').empty();
            $(this).remove();
        });

        // Font preview on change
        $('.newspaperweb-select2').on('change', function() {
            var fontFamily = $(this).val();
            var $preview = $(this).siblings('.font-preview');
            
            if ($preview.length) {
                $preview.css('font-family', fontFamily);
            }
        });

        // Tabs functionality
        if ($.fn.tabs) {
            $('#newspaperweb-options-tabs').tabs();
        }
    });

})(jQuery); 