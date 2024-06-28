var j = jQuery.noConflict();

j(document).ready(function() {

    j("body").on("click", ".loadButton", function () {
        var button = j(this);
        var url = button.data('url');
        var productId = j('.productOptions').val();
        new Ajax.Request(url, {
            method: "post",
            parameters: { id: productId},
            onSuccess: function (response) {
                j('.item-details').html(response.responseText);
            },
        });
    });

    j("body").on("click", ".similarItems", function () {
        var button = j(this);
        var url = button.data('url');
        var productId = button.data('id');

        new Ajax.Request(url, {
            method: "post",
            parameters: { id: productId},
            onSuccess: function (response) {
                j('.product-grid').html(response.responseText);
            },
        });
    });

});