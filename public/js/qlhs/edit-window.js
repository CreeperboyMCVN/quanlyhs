$('.primary-form').on('input', function () {
    search = $('.primary-form').attr('name') + '-' + $('.primary-form').val();
    getData();
    setTimeout(() => {
        let res = '';
        for (let i = 0; i<content.length; i++) {
            if (i<5) res += `<div class="suggest-option" value="${content[i].id}">` + 
            cutText(content[i].name, 20) + ' - ' + content[i].id + 
            ' - ' + content[i].class + '</div>';
        }
        $('.suggest-menu').html(res);
        if (content.length >0) applyValue(content[0]['id']) 
            else
            applyValue('');
    }, 500);
});

$(document).ready(function () {
    let primVal = getParam($('.primary-form').attr('name'));
    if (primVal != '') {
        setTimeout(() => {
            getData();
            $('.primary-form').val(primVal);
            applyValue(primVal);
        }, 1000);
    }
});

let mouseX = 0;
let mouseY = 0;
    
$(this).mousemove(function (e) { 
    // values: e.clientX, e.clientY, e.pageX, e.pageY
    mouseX = e.clientX;
    mouseY = e.clientY;
});

$('.primary-form').on('focus', function () {
    getData();
    setTimeout(() => {
        let res = '';
        for (let i = 0; i<content.length; i++) {
            if (i<5) res += `<div class="suggest-option" value="${content[i].id}">` + 
            cutText(content[i].name, 20) + ' - ' + content[i].id + 
            ' - ' + content[i].class + '</div>';
        }
        $('.suggest-menu').html(res);
        if (content.length >0) applyValue(content[0]['id']) 
            else
            applyValue('');
    }, 500);
    $('.suggest-menu').show();
    $('.suggest-menu').css('left', mouseX);
    $('.suggest-menu').css('top', mouseY+5);
});

$('.primary-form').on('focusout', function () {
    setTimeout(() => {
        $('.suggest-menu').hide()
    }, 500);
});

$('.suggest-menu').on('click', '.suggest-option', function () {
    $('.primary-form').val($(this).attr('value'));
    applyValue($('.primary-form').val());
});

function applyValue(primary) {
    if (content.length > 0) {
        for (let i =0; i < content.length; i++) {
            if (content[i][$('.primary-form').attr('name')] == primary) {
                br = false;
                $('.edit-form').each(function (index, element) {
                    // element == this
                    if (!$(this).hasClass('primary-form')) {
                        $(this).val(content[i][$(this).attr('name')]);
                        br = true;
                    }
                });
                if (br) break;
            } else {
                $('.edit-form').each(function (index, element) {
                    // element == this
                    if (!$(this).hasClass('primary-form')) {
                        $(this).val('');
                    }
                });
            }
        }

    } else {
        $('.edit-form').each(function (index, element) {
            // element == this
            if (!$(this).hasClass('primary-form')) {
                $(this).val('');
            }
        });
    }
}

function edit(action) {
    let data;
    let dat = '';
    $('.edit-form').each(function (index, element) {
        // element == this
        dat += $(this).attr('name') + '=' + $(this).val() + '&';
    });
    dat = dat.substring(0, dat.length-1);
    switch (action) {
        case "edit":
            data = {
                token: secert,
                username: username,
                view: view,
                data: dat,
                action: action,
                primary: $('.primary-form').attr('name')+'-'+$('.primary-form').val(),
            }
            break;
        case 'delete':
            data = {
                token: secert,
                username: username,
                view: view,
                data: dat,
                action: action,
                primary: $('.primary-form').attr('name')+'-'+$('.primary-form').val(),
            }
            break;
    }
    $.post("edit", data,
        function (data, textStatus, jqXHR) {
            console.log(data);
            if (data.code == 0) {
                popup('Thành công!', data.message);
            } else {
                popup('Thất bại!', data.message);
            }
        }
    );
}

$('.edit-btn').on('click', function (e) {
    e.preventDefault();
    edit('edit');
});

$('.delete-btn').on('click', function (e) {
    e.preventDefault();
    edit('delete');
});