jQuery(document).ready(function ($) {
    function initMediaUploader(button, input, container) {
        var mediaUploader;

        button.on('click', function (e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Select Image'
                },
                multiple: false
            });

            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var thumbnailUrl = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
                
                input.val(attachment.id);
                container.html('<img src="' + thumbnailUrl + '" style="max-width: 100px;" />');
            });

            mediaUploader.open();
        });
    }

    var galleryButton = $('.hero-image-button');
    var galleryButtonInput = $('.hero-image-input');
    var galleryButtonContainer = $('.hero-image-container');

    initMediaUploader(galleryButton, galleryButtonInput, galleryButtonContainer);
});
