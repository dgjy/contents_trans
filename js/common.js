$(function () {

    $('.copy').on('click',function(){

    var codeTag = $(this).next('pre').text().replace(/\\n/g, '<br/>');
    $('#copyCode').val(codeTag);
    $('#copyCode').select();
    document.execCommand('copy');
    var text = $('.text');
    alert ('복사되었습니다.');
    });
});