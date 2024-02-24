let content;
let secert = getCookie('qlhs_user_token');
let username = getCookie('qlhs_user_name');
let mouseX;
let mouseY;

$(document).ready(function () {
    let data = {
        "username": username,
        "secert": secert,
        "type": "violate"
    };
    $.post("data", data,
        function (data, textStatus, jqXHR) {
            let dat = data.data;
            let html = '';
            for (var i in dat) {
                html += `<option value="${dat[i]['id']}">${dat[i]['name']} (-${dat[i]['points']})</option>`;
            }
            $('#loai-vi-pham').html(html);
        }
    );
});

$('.submit-btn').click(function (e) { 
    e.preventDefault();
    showLoadingScreen();
    let studentid = $('#ma-hoc-sinh').val();
    let violateid = $('#loai-vi-pham').val();
    if (studentid == '' || violateid == '') {
        popup('Lỗi!', 'Vui lòng nhập đủ thông tin!');
        hideLoadingScreen();
        return;
    }
    let data = {
        'username': username,
        'secert': secert,
        'type': 'students',
        'search': 'id-' + studentid,
        'strict': true,
    }
    $.post("data", data,
        function (data, textStatus, jqXHR) {
            if (data.data.length == 0) {
                popup('Thông báo!', 'Học sinh với id đó không tồn tại!');
                hideLoadingScreen();
                return;
            } else {
                let sendData = {
                    'student_id': studentid,
                    'violate_id': violateid,
                    'supervisor': username,
                    'count': 1
                }
                data = {
                    "username": username,
                    "token": secert,
                    "view": "log",
                    "data": parseArray(sendData)
                }
                $.post("import", data,
                    function (data, textStatus, jqXHR) {
                        if (data.code == 0) {
                            popup('Thành công', 'Cập nhật thành công');
                        } else {
                            popup('Lỗi!', data.message);
                        }
                        $('#ma-hoc-sinh').val('');
                        hideLoadingScreen();
                        updateSuggest();
                    }
                );
            }
            
        }
    )
});

$(document).mousemove(function (e) { 
    // values: e.clientX, e.clientY, e.pageX, e.pageY
    mouseX = e.clientX;
    mouseY = e.clientY;
});

$('#ma-hoc-sinh').on('input', function () {
    if ($('#ma-hoc-sinh').val() == '') {
        $('.suggest-container').html('');
    } else {
        updateSuggest();
    }
});

$('#ma-hoc-sinh').on('focus', function () {
    $('.suggest-container').show();
    updateSuggest();
});

$('#ma-hoc-sinh').on('focusout', function () {
    setTimeout(() => {
        $('.suggest-container').hide();
        if (content.length == 1) {
            $('#ma-hoc-sinh').val(content[0]['id']);
        }
        updateSuggest();
    }, 200);
});

$('.suggest-container').on('click', '.suggest-option', function () {
    $('#ma-hoc-sinh').val($(this).attr('value'));
});

function updateSuggest() {
    $('.suggest-container').css('top', mouseY + 18);
    $('.suggest-container').css('left', mouseX);
    
    let data = {
        'username': username,
        'secert': secert,
        'search': $('#ma-hoc-sinh').val(),
        'global_search': true,
        'type': 'students'
    }
    $.post("data", data,
        function (data, textStatus, jqXHR) {
            let dat = data.data;
            content = dat;
            //console.log(data);
            let suggestContent = '';
            if (dat.length == 1) {
                $('.info').text(`Tên: ${dat[0]['name']}, Lớp: ${dat[0]['class']}, Giới tính: ${gender(dat[0]['gender'])}`);
            } else {
                $('.info').text('');
            }
            for (let i = 0; i < dat.length; i++) {
                if (i < 5) {
                    suggestContent += `<div class="suggest-option" value="${dat[i]['id']}">` + dat[i]['id'] + ' - ' + cutText(dat[i]['name'], 20)
                        + ' - ' + dat[i]['class'] + '</div>'; 
                }
            }
            $('.suggest-container').html(suggestContent);
        }
    );
}