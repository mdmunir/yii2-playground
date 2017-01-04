(function () {
    var jcrop_api;
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery('#cx').val('');
                $('#btn-submit').hide();
                if (jcrop_api) {
                    jcrop_api.destroy();
                }
                $('#img-content').attr('src', e.target.result);
                var img = new Image();
                img.src = e.target.result;
                setTimeout(function () {
                    if (img.width < 600 || img.height < 450) {
                        alert('Image to small');
                        return;
                    }
                    var ratio = img.width / $('#img-content').width();
                    var minW = 600.0 / ratio;
                    var minH = 450.0 / ratio;
                    jcrop_api = $.Jcrop('#img-content', {
                        aspectRatio: 4.0 / 3,
                        onSelect: updateCoords,
                        minSize: [minW, minH],
                        setSelect: [0, 0, minW, minH],
                    });
                    $('#er').val(ratio);
                    $('#btn-submit').show();
                }, 100);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#inp-image").change(function () {
        readURL(this);
    });

    $('#select-file').click(function () {
        $("#inp-image").trigger('click');
    });

    function updateCoords(c) {
        jQuery('#cx').val(c.x);
        jQuery('#cy').val(c.y);
        jQuery('#cw').val(c.w);
        jQuery('#ch').val(c.h);
        $('#btn-submit').show();
    }
})();
