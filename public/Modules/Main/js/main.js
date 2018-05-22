function getSlug(e) {
    var url = $(e).data('url');
    var slug = $(e).val();
    var type = $(e).data('type');
    var target_slug = $(e).data('target-slug');
    var target_seo_title = $(e).data('target-seo-title');

    axios.get(url, {
        params: {
            slug: slug,
            type: type
        }
    })
        .then(function (responce) {
            $('input[ name = "' + target_slug + '" ]').val(responce.data);
        })
        .catch(function (errors) {
            console.log(errors);
        });
    $('input[ name = "' + target_seo_title + '" ]').val(slug);
}

$('.select2').select2();
$('#lfm').filemanager('image', {prefix: '/media'});
$('.lfm').filemanager('image', {prefix: '/media'});

$(document).ready(function () {
    CKEDITOR.replaceAll('text-editor');
    CKEDITOR.config.filebrowserImageBrowseUrl = '/media?type=Images';
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.config.verify_html = false;
    CKEDITOR.dtd.$removeEmpty.i = 0;
});