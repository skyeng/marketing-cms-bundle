$('body').on('change', '.page-component-name-select', function (event) {
    $select = $(this);

    var idPrefix = $(this).attr('id');
    idPrefix = idPrefix.substr(0, idPrefix.length - 5); //name
    var namePrefix = $(this).attr('name');
    namePrefix = namePrefix.substr(0, namePrefix.length - 6); //[name]

    $parentNode = $(this).closest('.form-group').parent();
    $dataForm = $('#' + idPrefix).find('#' + idPrefix + '_data').closest('.form-group');

    if ($select.val() === '') {
        $dataForm.remove();
        return;
    }

    $.ajax({
        url : '/admin/page-component/form',
        type: 'GET',
        data : {'name': $select.val()},
        beforeSend: function () {
            $select.prop('disabled', true);
            $('.data-loader').remove();
            $parentNode.append('<i class="fa fa-spinner fa-spin data-loader" style="font-size: 2em;"></i>');
            $dataForm.remove();
        },
        success: function(html) {
            html = html.replaceAll('name=&quot;page_component', 'name=&quot;' + namePrefix);
            html = html.replaceAll('id=&quot;page_component_', 'id=&quot;' + idPrefix + '_');
            $('.data-loader').remove();
            $parentNode.append(html);
            $select.prop('disabled', false);
            $('.field-select').select2({
                theme: "bootstrap"
            });
            document.dispatchEvent(new Event('ea.collection.item-added'));
        },
        error: function (jqXHR, exception) {
            $('.data-loader').remove();
            $parentNode.append('' +
                '<p class="data-loader" style="font-size: 1.5em;">' +
                '<i class="fa fa-times"></i> ' +
                'Во время получения данных произошла ошибка' +
                '</p>'
            );
            $select.prop('disabled', false);
        }
    });
});
