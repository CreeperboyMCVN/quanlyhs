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

function parseArray(array) {
    let string = '';
    if (typeof array != 'Map') {
        array = new Map(Object.entries(array));
    }
    array.forEach((v,k) => {
        string += k + '='+ v + '&';
    });
    return string.slice(0,-1);
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

$('.close-popup').click(function (e) { 
    e.preventDefault();
    $('.popup').hide();
});