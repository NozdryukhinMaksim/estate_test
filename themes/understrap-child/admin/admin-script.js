jQuery(document).ready(function($) {
    // Добавление изображения в галерею
    $('.add-gallery-image').click(function() {
        var frame = wp.media({
            title: 'Выберите изображение',
            multiple: true,
            library: {
                type: 'image'
            },
            button: {
                text: 'Добавить'
            }
        });

        frame.on('select', function() {
            var attachmentIds = [];

            frame.state().get('selection').forEach(function(attachment) {
                attachmentIds.push(attachment.id);
            });

            $('.gallery-thumbnails').empty();

            attachmentIds.forEach(function(attachmentId) {
                var attachment = wp.media.attachment(attachmentId);
                attachment.fetch();
                attachment.url = attachment.attributes.url;

                $('.gallery-thumbnails').append('<li><img src="' + attachment.url + '"><a href="#" class="remove-image">Удалить</a></li>');
            });

            $('#gallery_images').val(attachmentIds.join(','));
        });

        frame.open();
    });

    // Удаление изображения из галереи
    $(document).on('click', '.remove-image', function(e) {
        e.preventDefault();

        var imageItem = $(this).parent('li');
        var attachmentId = imageItem.index();

        imageItem.remove();

        var attachmentIds = [];
        $('.gallery-thumbnails li').each(function() {
            attachmentIds.push($(this).index());
        });

        $('#gallery_images').val(attachmentIds.join(','));
    });
});
