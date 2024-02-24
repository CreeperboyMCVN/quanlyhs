$("#file").on("change", function (e) {
    var file = $(this)[0].files[0];
    var upload = new Upload(file);
    showLoadingScreen();
    // maby check size or type here with upload.getSize() and upload.getType()

    // execute upload
    upload.doUpload();
});

$('.manual-import-btn').click(function (e) { 
    e.preventDefault();
    manualImport();
});

function manualImport() {
    let dat = '';
    $('.manual-form').each(function (index, element) {
        // element == this
        dat += $(this).attr('name') + '=' + $(this).val() + '&';
    });
    dat = dat.substring(0, dat.length-1);
    let data = {
        data: dat,
        token: secert,
        username: username,
        view: view
    }
    $.post("import", data,
        function (data, textStatus, jqXHR) {
            if (data.code != 0) {
                popup('Lỗi', data.message);
            } else {
                popup('Thành công', 'Thêm thành công');
                $('.manual-form').each(function (index, element) {
                    // element == this
                    $(this).val('');
                });
            }
        }
    );
}