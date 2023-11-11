$('.record-option').on('input', function (e) {
    options(e);
});

function options(e) {
    let all = $('.record-option.none').prop('checked');
    let classes = $(e.currentTarget).attr('class').split(' ');
    if (all) {
        $('.record-option:not(.none)').prop('checked', false)
        $('.options').html('');
    }
    if (classes[1] != 'none') {
        if ($('.record-option.none').prop('checked')) {
            $('.record-option.none').prop('checked', false);
        }
        if (classes[1] == 'class') {
            $('.record-option.student').prop('checked', false);
            $('.record-option.violate').prop('checked', false);
        }
        if (classes[1] == 'student') {
            $('.record-option.class').prop('checked', false);
            $('.record-option.violate').prop('checked', false);
        }
        if (classes[1] == 'violate') {
            $('.record-option.student').prop('checked', false);
            $('.record-option.class').prop('checked', false);
        }
    }
    let html = '';
    if ($('.date').prop('checked')) {
        html += `
        <label for='date_start'>Từ ngày</label>
        <input type='date' name='date_start' class='option-form'>
        <label for='date_end'>Đến</label>
        <input type='date' name='date_end' class='option-form'>
        <label>Chọn nhanh thời gian</label>
        <button class='quick-select-today'>Hôm nay</button>
        <button class='quick-select-week'>Tuần này</button>
        <button class='quick-select-month'>Tháng này</button>
        `;
    }

    if ($('.class').prop('checked')) {
        html += `
        <label for='class'>Lớp</label>
        <input type='text' name='class' class='option-form' placeholder='Tên lớp'>
        `;
    }

    if ($('.student').prop('checked')) {
        html += `
        <label for='student'>Học sinh</label>
        <input type='text' name='student' class='option-form' placeholder='Mã học sinh'>
        `;
    }

    if ($('.violate').prop('checked')) {
        html += `
        <label for='violate'>Vi phạm</label>
        <input type='text' name='violate' class='option-form' placeholder='Mã vi phạm'>
        `;
    }


    if (html != '') {
        html += `
        <button class='confirm right-button'>Xác nhận</button>
        `;
    }

    $('.options').html(html);
}