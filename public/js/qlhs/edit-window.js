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
    

$('.primary-form').on('focus', function () {
    $('.suggest-menu').show();
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