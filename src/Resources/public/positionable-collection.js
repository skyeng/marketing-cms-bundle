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

$('body').on('change', '.page-component-name-select', function (event) {
    var data = {'data-name': $(this).val()};

    var idPrefix = $(this).attr('id');
    idPrefix = idPrefix.substr(0, idPrefix.length - 5); //name
    var namePrefix = $(this).attr('name');
    namePrefix = namePrefix.substr(0, namePrefix.length - 6); //[name]

    $.ajax({
        url : '/admin/page-component/form',
        type: 'GET',
        data : data,
        success: function(html) {
            html = html.replaceAll('name="page_component', 'name="' + namePrefix);
            html = html.replaceAll('id="page_component_', 'id="' + idPrefix + '_');

            $dataObj = $('#' + idPrefix).find('#' + idPrefix + '_data');
            $dataObj.closest('.form-widget-compound').replaceWith(html);
        }
    });
});
