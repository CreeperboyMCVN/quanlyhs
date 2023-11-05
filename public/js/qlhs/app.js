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


$('.close-popup').click(function (e) { 
    e.preventDefault();
    $('.popup').hide();
});

//functions


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
            if (typeof updateList === 'function') {
                updateList();
            }
        }
    );
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

function gender(num) {
    if (num == 0)
        return "Nam"
    else
        return "Nữ"
}

function cutText(text, length) {
    if (text == null) return '';
    if (text.length <= length) return text;
    text = text.substring(text.length - length, text.length);
    //last = text.firstIndexOf(' ');
    //text = text.substring(0, last);
    return "..." + text;
}

function getParam(param) {
    url = window.location.href;
    let params = url.split('?')[1].split("&");
    //console.log(params);
    for (let i = 0; i < params.length; i++) {
        let obj = params[i].split('=');
        if (obj[0] == param) return obj[1];
    }
    return "";
}