jQuery(document).ready(function($) {
    // Tab functionality
    $('.esm-tab').on('click', function(e) {
        e.preventDefault();
        var tabId = $(this).data('tab');
        
        // Update active tab
        $('.esm-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Show selected tab content
        $('.esm-tab-content').hide();
        $('#' + tabId).show();
    });
    
    // Show first tab by default
    $('.esm-tab:first').click();
    
    // Initialize media uploader
    var mediaUploader;
    
    // Handle image upload button click
    $('.esm-upload-image').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var imageField = button.closest('.esm-image-field');
        var imagePreview = imageField.find('.esm-image-preview');
        var imageIdInput = imageField.find('.esm-image-id');
        
        // If media uploader exists, open it
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        // Create new media uploader
        mediaUploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        // When image is selected
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            var imageUrl = attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
            
            // Update preview
            imagePreview.html('<img src="' + imageUrl + '" style="max-width: 200px;" />');
            
            // Update hidden input
            imageIdInput.val(attachment.id);
            
            // Show remove button
            if (!imageField.find('.esm-remove-image').length) {
                button.after('<button type="button" class="button esm-remove-image">Remove Image</button>');
            }
        });
        
        // Open media uploader
        mediaUploader.open();
    });
    
    // Handle remove image button click
    $(document).on('click', '.esm-remove-image', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var imageField = button.closest('.esm-image-field');
        var imagePreview = imageField.find('.esm-image-preview');
        var imageIdInput = imageField.find('.esm-image-id');
        
        // Clear preview
        imagePreview.empty();
        
        // Clear input
        imageIdInput.val('');
        
        // Remove button
        button.remove();
    });
});