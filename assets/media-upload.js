jQuery(document).ready(function($) {
    
    // Media Upload for Panels
    $('.seminario-upload-image-btn').click(function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = $('#' + button.data('target'));
        var previewDiv = $('#' + button.data('target') + '_preview');
        
        var mediaUploader = wp.media({
            title: 'Selecionar Imagem',
            button: {
                text: 'Usar esta imagem'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
            
            if (previewDiv.length) {
                previewDiv.html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto; border-radius: 8px; margin-top: 10px;">');
            }
        });
        
        mediaUploader.open();
    });
    
    // Remove Image
    $('.seminario-remove-image-btn').click(function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = $('#' + button.data('target'));
        var previewDiv = $('#' + button.data('target') + '_preview');
        
        targetInput.val('');
        if (previewDiv.length) {
            previewDiv.html('');
        }
    });
    
    // Update preview on page load
    $('.seminario-image-input').each(function() {
        var input = $(this);
        var previewDiv = $('#' + input.attr('id') + '_preview');
        
        if (input.val() && previewDiv.length) {
            previewDiv.html('<img src="' + input.val() + '" style="max-width: 150px; height: auto; border-radius: 8px; margin-top: 10px;">');
        }
    });
    
    // Update preview when input changes
    $('.seminario-image-input').on('input change', function() {
        var input = $(this);
        var previewDiv = $('#' + input.attr('id') + '_preview');
        
        if (input.val() && previewDiv.length) {
            previewDiv.html('<img src="' + input.val() + '" style="max-width: 150px; height: auto; border-radius: 8px; margin-top: 10px;">');
        } else if (previewDiv.length) {
            previewDiv.html('');
        }
    });
    
});