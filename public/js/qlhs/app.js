var username;
var secert;
$(document).ready(function($) {
    username = $('#username').val();
    secert = $('#secert-key').val();
    
    
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