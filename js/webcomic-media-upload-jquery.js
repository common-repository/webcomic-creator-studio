jQuery(document).ready(function () {

   jQuery('#webcomic-upload-bg').click(function () {
       return openMediaUpload("bg");
    });
	jQuery('#webcomic-upload-char').click(function () {
       return openMediaUpload("char");
    });

    window.send_to_editor = function (html) {
       tb_remove();
    };

});