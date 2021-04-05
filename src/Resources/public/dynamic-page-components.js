$('body').on('change', '.page-component-name-select', function (event) {
    var data = {'name': $(this).val()};

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
