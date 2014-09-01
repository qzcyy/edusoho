define(function(require, exports, module) {

    var Notify = require('common/bootstrap-notify');
    var Uploader = require('upload');

    exports.run = function() {

        var $form = $("#school-form");
        var uploader = new Uploader({
            trigger: '#school-homepage-upload',
            name: 'homepagePicture',
            action: $('#school-homepage-upload').data('url'),
            data: {'_csrf_token': $('meta[name=csrf-token]').attr('content') },
            accept: 'image/*',
            error: function(file) {
                Notify.danger('上传图片失败，请重试！')
            },
            success: function(response) {
                response = $.parseJSON(response);
                $("#school-homepage-container").html('<img src="' + response.url + '?'+(new Date()).getTime()+'" style="max-width:400px;">');
                $form.find('[name=homepagePicture]').val(response.path);
                Notify.success('上传微信二维码成功！');
            }
        });

      
    }
});