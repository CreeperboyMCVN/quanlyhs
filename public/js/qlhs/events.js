$('.download').click(function (e) { 
    e.preventDefault();
    let dat = {
        filename: 'test',
        data: '[12,34,56]'
    }
    $.post("./spreadsheet", dat,
        function (data, textStatus, jqXHR) {
            if (data.code == 0) {
                $('iframe').attr('src', data.filename);
            } else alert(data.message);
        },
        "json"
    );
});