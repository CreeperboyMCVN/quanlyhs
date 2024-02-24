//events for list window
$('.close-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').hide();
    $('.filter-overlay').hide();
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

$('.download-btn').click(function (e) { 
    e.preventDefault();

    arr = [];

    if (content.length < 1) return;

    arr.concat(dat.header);

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

$('.open-filter').click(function (e) { 
    e.preventDefault();
    $('.filter-popup').show();
    $('.filter-overlay').show();
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

// list window functions
function updatePageInfo() {
    $('.page-info').html(`Đang hiển thị phần tử ${curr_page*max-max+1 < 0 ? 0 : curr_page*max-max+1} đến 
    ${content.length >= curr_page*max ? curr_page*max : content.length} 
    trên tổng số ${content.length}`);
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
    res += `<th></th>`;
    res += '</tr>';
    //filter

    if (content.length == 0) {
        res += '<tr class="table-even">'
            + `<td colspan=${header.length+1} style="text-align: center;">Không có dữ liệu...</td>`
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
                let id = '';
                vals.forEach(e => {
                    
                    if (keys[j] ==  'id') {
                        id = e;
                    }
                    if (keys[j] == 'gender') {
                        res += `<td>${gender(e)}</td>`
                    } else if (keys[j] == 'password') {

                    } else if (keys[j] == 'status') {
                        res += `<td>${stat(e)}</td>`;
                    } else {
                        res += `<td>${e}</td>`;
                    }
                    
                    j++;
                });
                res += `<td><a href="${window.location.href + '&edit=&id='+id}"><i class="fa-solid fa-pencil"></i></a></td>`;
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

function updateList() {
    $('.table-wrapper').html(table());
            $('.pages').html(page());
            if (dat.data.length > 0) {
                $("#field").html(getFields());
            }
            updatePageInfo();
}