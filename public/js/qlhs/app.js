var username;
var secert;
var action;
var dat;
var max;
var curr_page = 1;
var search = '';
var fromDate;
var toDate;


$(document).ready(function($) {
    username = $('#username').val();
    secert = $('#secert-key').val();
    action = $('.action-data').val();
    max = $('.max-filter').val();

    getData();
    
})

$('.close-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').hide();
    $('.filter-overlay').hide();
});

$('.open-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').show();
    $('.filter-overlay').show();
});

$('.download-btn').click(function (e) { 
    e.preventDefault();

    arr = [];

    if (dat.data.length < 1) return;

    dat.data.forEach(v => {
        arr = arr.concat([Object.values(v)]);
    })

    let data = {
        filename: 'students',
        data: JSON.stringify(arr),
    }
    console.log(arr);
    $.post("./spreadsheet", data,
        function (data, textStatus, jqXHR) {
            if (data.code == 0) {
                $('.download').attr('src', data.filename);
            } else alert(data.message);
        },
        "json"
    );
});

$('.pages').on('click', '.page-select', function (e) {
    curr_page = e.target.textContent;
    $('.table-wrapper').html(table());
    $('.pages').html(page());
});

$('.pages').on('click', '.three-dot', function (e) {
    $(this).html("<input type='number' class='set-page' />");
    $(this).removeClass('three-dot');
});

$('.pages').on('focusout', '.set-page', function (e) {
    curr_page = $(this).val();
    $('.table-wrapper').html(table());
    $('.pages').html(page());
});

$('.confirm-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').hide();
    $('.filter-overlay').hide();
    max = $('.max-filter').val();
    let filtSearch = $('#search').val();
    if ((filtSearch != null) && (filtSearch != '')) {
        search = $('#field').val() + '-' + filtSearch;
    } else {
        search = '';
    }

    fromDate = $("#date-start").val();
    toDate = $("#date-end").val();

    getData();
    $('.table-wrapper').html("Đang tải dữ liệu...");
    $('.pages').html("");
});

//functions

function getData() {
    let data = {
        secert: secert,
        username: username,
        type: action,
        search: search
    }

    $.post("data", data,
        function (data, textStatus, jqXHR) {
            dat = data;
            $('.table-wrapper').html(table());
            $('.pages').html(page());
            if (data.data.length > 0) {
                $("#field").html(getFields());
            }
        }
    );
}

function table() {
    validPage();
    res = '<table class="data-table">';
    i=0;

    header = [];
    mainDateField = '';

    switch (action) {
        case 'students':
            header = ['Mã học sinh', 'Tên', 'Lớp', 'Ngày sinh', 'Giới tính'];
            mainDateField = 'dob';
            break;
    
        default:
            break;
    }
    res += '<tr class="table-header">';
    header.forEach(e => {
        res += `<th>${e}</th>`;
    })
    res += '</tr>';
    if (dat.data.length == 0) {
        res += '<tr class="table-even">'
            + `<td colspan=${header.length} style="text-align: center;">Không có dữ liệu...</td>`
            + '</tr></table>';
        return res;
    }
    dat.data.forEach(element => {
        if (isInDate(element[mainDateField])) {
            if (i+1 >= curr_page*max-max+1 && i+1 <= curr_page * max) {
                j=0;
                if (i%2 == 0) {
                    c = "table-even";
                } else {
                    c = "table-odd";
                }
                res += `<tr class="${c}">`;
                vals = Object.values(element);
                keys = Object.keys(element);
                vals.forEach(e => {
                    if (keys[j] == 'gender') {
                        if (e==0) {
                            res += `<td>Nam</td>`;
                        } else {
                            res += `<td>Nữ</td>`;
                        }
                    } else {
                        res += `<td>${e}</td>`;
                    }
                    j++;
                });
                res += '</tr>';

            }
            i++;
        }
    });
    res += '</table>'
    return res;
}

function page() {
    validPage();
    res = "<ul class='page'>";
    max_page = Math.ceil(dat.data.length / max);
    thrdt = false;
    for (let index = 1; index <= max_page; index++) {
        
        if (max_page > 10) {
            
            if (index <= 2) {
                if (index == curr_page) {
                    res += '<li><button class="page-select page-selected">' + index + '</button></li>';
                } else {
                    res += '<li><button class="page-select">' + index + '</button></li>';
                }
                continue;
            }

            if (index == curr_page && index <= max_page -2) {
                if (index != 3 && !thrdt) {
                    res += '<li><button class="three-dot">...</button></li>';
                }
                if (index == 3) {
                    thrdt = true;
                }
                res += '<li><button class="page-select page-selected">' + index + '</button></li>';
                if (index != max_page - 2) {
                    res += '<li><button class="three-dot">...</button></li>';
                }
            } else if (!thrdt) {
                thrdt = true;
                res += '<li><button class="three-dot">...</button></li>';
            }
            
            if (index > max_page - 2) {
                if (index == curr_page) {
                    res += '<li><button class="page-select page-selected">' + index + '</button></li>';
                } else {
                    res += '<li><button class="page-select">' + index + '</button></li>';
                }
                continue;
            }
            continue;
        }
        if (index == curr_page) {
            res += '<li><button class="page-select page-selected">' + index + '</button></li>';
        } else {
            res += '<li><button class="page-select">' + index + '</button></li>';
        }
    }
    res += '</ul>';
    return res;
}

function validPage() {
    max_page = Math.ceil(dat.data.length / max);
    if (curr_page < 1 || curr_page == null) {
        curr_page = 1;
    }
    if (curr_page > max_page) {
        curr_page = max_page;
    }
}

function getFields() {
    if (dat.data.length < 1) return '';
    keys = Object.keys(dat.data[0]);
    res = '';
    
    keys.forEach(v => {
        res += `<option value=${v}>${v}</option>`;
    })

    return res;
}

function isInDate(date) {
    let date1 = new Date(fromDate);
    let date2 = new Date(toDate);
    let d = new Date(date);
    if (!isNaN(date1.getTime()) && isNaN(date2.getTime())) {
        return d >= date1;
    }
    if (isNaN(date1.getTime()) && !isNaN(date2.getTime())) {
        return d <= date2;
    }
    if (!isNaN(date1.getTime()) && !isNaN(date2.getTime())) {
        return d >= date1 && d <= date2;
    }
    return true;

}