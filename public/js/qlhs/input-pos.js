let content;
let secert = getCookie('qlhs_user_token');
let username = getCookie('qlhs_user_name');
let mouseX;
let mouseY;

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
    setTimeout(() => {$('.suggest-container').hide();}, 500);
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
            //console.log(data);
            let suggestContent = '';
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