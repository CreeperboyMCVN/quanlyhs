var username;
var secert;
var view;
var dat;
var max;
var curr_page = 1;
var search = '';
var fromDate;
var toDate;
var content;


$(document).ready(function($) {
    username = getCookie('qlhs_user_name');
    secert = getCookie('qlhs_user_token');
    view = $('.user-view').val();
    max = $('.max-filter').val();

    getData();
    
})

$('.close-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').hide();
    $('.filter-overlay').hide();
});

$('.close-popup').click(function (e) { 
    e.preventDefault();
    $('.popup').hide();
});

$('.reset-filter').click(function (e) { 
    setTimeout(() => {
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
        $('.filter-popup').hide();
        $('.filter-overlay').hide();
    }, 100);
    
});

$('.open-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').show();
    $('.filter-overlay').show();
});

$('.download-btn').click(function (e) { 
    e.preventDefault();

    arr = [];

    if (content.length < 1) return;

    content.forEach(v => {
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
    updatePageInfo();
    $('.table-wrapper').html(table());
    $('.pages').html(page());
});

$('.pages').on('click', '.three-dot', function (e) {
    $(this).html("<input type='number' class='set-page' />");
    $(this).removeClass('three-dot');
});

$('.pages').on('focusout', '.set-page', function (e) {
    curr_page = $(this).val();
    updatePageInfo();
    $('.table-wrapper').html(table());
    $('.pages').html(page());
});

$('.confirm-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').hide();
    $('.filter-overlay').hide();
    max = Math.round($('.max-filter').val());
    $('.max-filter').val(max);
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

$("#file").on("change", function (e) {
    var file = $(this)[0].files[0];
    var upload = new Upload(file);

    // maby check size or type here with upload.getSize() and upload.getType()

    // execute upload
    upload.doUpload();
});

$('.manual-import-btn').click(function (e) { 
    e.preventDefault();
    manualImport();
});

//functions

function updatePageInfo() {
    $('.page-info').html(`Đang hiển thị phần tử ${curr_page*max-max+1 < 0 ? 0 : curr_page*max-max+1} đến 
    ${content.length >= curr_page*max ? curr_page*max : content.length} 
    trên tổng số ${content.length}`);
}

function getData() {
    $('.page-info').html('');
    let data = {
        secert: secert,
        username: username,
        type: view,
        search: search
    }

    $.post("data", data,
        function (data, textStatus, jqXHR) {
            dat = data;
            content = dat.data;
            content = filterByDate(dat.datefield);
            $('.table-wrapper').html(table());
            $('.pages').html(page());
            if (data.data.length > 0) {
                $("#field").html(getFields());
            }
            updatePageInfo();
        }
    );
}

function table() {
    validPage();
    res = '<table class="data-table">';
    i=0;

    let header = dat.header;
    let mainDateField = dat.datefield;

    res += '<tr class="table-header">';
    header.forEach(e => {
        res += `<th>${e}</th>`;
    })
    res += '</tr>';
    //filter

    if (content.length == 0) {
        res += '<tr class="table-even">'
            + `<td colspan=${header.length} style="text-align: center;">Không có dữ liệu...</td>`
            + '</tr></table>';
        return res;
    }
    content.forEach(element => {
        
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
        
    });
    res += '</table>'
    return res;
}

function page() {
    validPage();
    res = "<ul class='page'>";
    max_page = Math.ceil(content.length / max);
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
    max_page = Math.ceil(content.length / max);
    if (curr_page < 1 || curr_page == null) {
        curr_page = 1;
    }
    if (curr_page > max_page) {
        curr_page = max_page;
    }
}

function getFields() {
    if (content.length < 1) return '';
    keys = Object.keys(content[0]);
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
        return (d >= date1) && (d <= date2);
    }
    return true;

}

function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

function popup(title, message) {
    $('.popup-title').html(title);
    $('.popup-message').html(message);
    $('.popup').show();
}

function filterByDate(field) {
    let data = content;
    let filteredData = [];
    data.forEach((v) => {
        if (isInDate(v[field])) {
            filteredData.push(v);
        }
    })
    return filteredData;
}

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
            }
        }
    );
}