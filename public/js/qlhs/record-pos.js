let record;
let callback;
let content;
let secert = getCookie('qlhs_user_token');
let username = getCookie('qlhs_user_name');
let mouseX;
let mouseY;

$('.record-option').on('input', function (e) {
    options(e);
});

$(document).ready(function () {
    getAllRecord();    
});

function getAllRecord() {
    let sendData = {
        'username': username,
        'secert': secert,
        'type': 'none'
    }
    $.post("record", sendData,
        function (data, textStatus, jqXHR) {
            if (data.code == 0) {
                record = data.data;
            } else {
                record = [];
            }
            callback = data;
            table();
        }
    );
}
function getDetailRecord() {
    let dateStart = $('#date-start').val();
    let dateEnd = $('#date-end').val();
    let clas = $('#class').val();
    let sendData = {
        username: username,
        secert: secert,
        type: 'detail'
    }
    if (dateStart == null && dateEnd == null) {
        if (clas == "") {
            popup("Thông báo", "Vui lòng nhập đủ thông tin");
        } else {
            sendData.class = clas;
        }
    } else {
        sendData.dateStart = dateStart;
        sendData.dateEnd = dateEnd;
        if (clas != "") {
            sendData.class = clas;
        }
    }
    //console.log(sendData);
    $.post("record", sendData,
        function (data, textStatus, jqXHR) {
            if (data.code == 0) {
                record = data.data;
            } else {
                record = [];
            }
            callback = data;
            table();
        }
    );
}

$(document).mousemove(function (e) { 
    // values: e.clientX, e.clientY, e.pageX, e.pageY
    mouseX = e.clientX;
    mouseY = e.clientY;
});

function setCurrentDay() {
    var today = new Date();
    $('#date-start').val(formatDate(today));
    $('#date-end').val(formatDate(today));
}

function setCurrentWeek() {
    var today = new Date();
    var firstday = new Date(today.setDate(today.getDate() - today.getDay()));
    $('#date-start').val(formatDate(firstday));
    var lastday = new Date(firstday.setDate(firstday.getDate() + 6));
    $('#date-end').val(formatDate(lastday));
}

function setCurrentMonth() {
    var today = new Date();
    var month = today.getMonth();
    var year = today.getFullYear();
    var firstDay = new Date(year, month, 1);
    var lastDay = new Date(year, month + 1, 0);
    $('#date-start').val(formatDate(firstDay));
    $('#date-end').val(formatDate(lastDay));
}

function formatDate(date = new Date()) {
    var year = date.toLocaleString('default', {year: 'numeric'});
    var month = date.toLocaleString('default', {month: '2-digit'});
    var day = date.toLocaleString('default', {day: '2-digit'})
    return `${year}-${month}-${day}`;
}

function options(e) {
    let all = $('.record-option.none').prop('checked');
    let classes = $(e.currentTarget).attr('class').split(' ');
    if (all) {
        $('.record-option:not(.none)').prop('checked', false)
        $('.options').html('');
        getAllRecord();
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
        <input type='date' name='date_start' class='option-form' id='date-start'>
        <label for='date_end'>Đến</label>
        <input type='date' name='date_end' class='option-form' id='date-end'>
        <label>Chọn nhanh thời gian</label>
        <button class='quick-select-today' type='button' onclick='setCurrentDay()'>Hôm nay</button>
        <button class='quick-select-week' type='button' onclick='setCurrentWeek()'>Tuần này</button>
        <button class='quick-select-month' type='button' onclick='setCurrentMonth()'>Tháng này</button>
        `;
    }

    if ($('.class').prop('checked')) {
        html += `
        <label for='class'>Lớp</label>
        <input type='text' name='class' class='option-form' placeholder='Tên lớp' id='class'>
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
        <div class='right-btn-container'><button class='confirm' type="button" onclick="getDetailRecord()" style='clear: both'>Xác nhận</button></div>
        `;
    }

    $('.options').html(html);
}

function table() {
    let html = '';
    if (callback.code == 0) {
        // table header
        html += '<tr>';
        callback.tableHeader.forEach((v) => {
            html += `<th>${v}</th>`;
        });
        html += '</tr>';
        // table content
        let count = 0;
        callback.data.forEach((v) => {
            if (count%2 ==0) {
                html += '<tr class="table-even">';
            } else {
                html += '<tr class="table-odd">';
            }
            
            new Map(Object.entries(v)).forEach((val,k) => {
                if (k == 'gender') {
                    html += `<td>${gender(val)}</td>`;
                } else if (k == 'violate') {
                    let text = '';
                    val.forEach((val1) => {
                        text += "<div>" + val1 + "</div>";
                    })
                    html += `<td><div class='violate-cell'>${text}</div></td>`
                } else {
                    html += `<td>${val}</td>`;
                }
            });
            count++;
            html += '</tr>'
        });
    }
    $('.preview-table').html(html);
}

function showHover(text) {
    $('.hover').show();
    $('.hover').html(`${text}`);
    $('.hover').css({top: mouseY  + $(document).scrollTop(), left: mouseX});
}

function hideHover() {
    $('.hover').hide();
}

$('.excel').click(function (e) { 
    e.preventDefault();
    arr = [];

    if (record.length < 1) return;

    arr.push(callback.tableHeader);

    record.forEach(v => {
        var vl = v['violate'];
        var vlText = '';
        vl.forEach(v1 => {
            vlText += v1 + '\n';
        });
        vlText = vlText.substring(0, vlText.length -1);
        v['violate'] = vlText;
        arr.push(Object.values(v));
    })

    let data = {
        filename: 'record',
        data: JSON.stringify(arr),
    }
    console.log(arr);
    $.post("./record-spr", data,
        function (data, textStatus, jqXHR) {
            console.log(data);
            if (data.code == 0) {
                if (!isMobile()) {
                    $('.download').attr('src', data.filename);
                } else {
                    window.location.href = data.filename;
                }
            } else alert(data.message);
        },
        "json"
    );
});

$('.email').click(function (e) { 
    e.preventDefault();
    showLoadingScreen();
    let dateStart = $('#date-start').val();
    let dateEnd = $('#date-end').val();
    let data = {
        username: username,
        token: secert,
        dateEnd: dateEnd,
        dateStart: dateStart
    }
    $.post("mail", data,
        function (dat, textStatus, jqXHR) {
            if (dat.code) {
                popup("Lỗi", dat.message);
            } else if (dat.status != 0) {
                popup("Lỗi", dat.message);
            } else {
                popup("Thành công", "Đã gửi mail cho giáo viên");
            }
            hideLoadingScreen();
        }
    );
});