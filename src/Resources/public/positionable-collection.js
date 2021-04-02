$('body').on('focusin', 'input[positionable="positionable"]', function() {
    $(this).data('val', $(this).val());
});

$('body').on('change', 'input[positionable="positionable"]', function () {
    var prev = $(this).data('val');
    var add = prev > $(this).val() ? -1 : 1;

    while($('input[positionable="positionable"][value="' + $(this).val() + '"]').length > 0) {
        $(this).val(parseInt($(this).val()) + add);
    }

    $(this).attr('value', parseInt($(this).val()));

    $collection = $('.positionable-collection')

    $collection.find('#Page_components > .form-group').sort(function(a, b) {
        return $(a).find('input[positionable="positionable"]').val() - $(b).find('input[positionable="positionable"]').val();
    }).appendTo($collection.find('#Page_components'));
});
