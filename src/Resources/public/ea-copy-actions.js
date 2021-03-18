$('body').on('click', 'a.copy-action-ea', function (event) {
    event.preventDefault();

    var $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(this).attr('href')).select();
    document.execCommand('copy');
    $temp.remove();

    var $childrenIcon = $(this).children('.action-icon');

    $childrenIcon.removeClass('fa-copy').addClass('fa-check');

    setTimeout(function () {
        $childrenIcon.removeClass('fa-check').addClass('fa-copy');
    }, 1000);
});
