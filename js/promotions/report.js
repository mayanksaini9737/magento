var j = jQuery.noConflict();
j(document).ready(function () {

    j("body").on("click", "#load-report", function (e) {
        e.preventDefault();
        var url = j(this).data('url');
        var active_tag = j('#tag_name').val();
        
        var params = {selectedValue: active_tag};
        var data = JSON.stringify(params);
        
        new Ajax.Request(url, {
            method: "post",
            parameters: { edited_data: data },
            onSuccess: function (response) {
                // let text = JSON.parse(response.responseText)
                j('report-container').update(response.responseText);
                // console.log(response);
                // var responseData = response.responseJSON;
                // createTable(responseData);

                // location.reload();
            },
        });
    });

    j("body").on("click", "#assign-tag", function (e) {
        e.preventDefault();
        var url = j(this).data('url');
        var sku = j('#sku').val();
        var active_tag = j('#tag_name').val();
        
        var params = {selectedValue: active_tag, selectedSku: sku};
        var data = JSON.stringify(params);
        
        new Ajax.Request(url, {
            method: "post",
            parameters: { edited_data: data },
            onSuccess: function (response) {
                console.log(response);
            },
        });
    });
});

function createTable(data) {
    var table = '<table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 16px; text-align: left;">';
    table += '<tr style="background-color: #f4f4f4; font-weight: bold;">';
    table += '<th style="padding: 12px 15px; border: 1px solid #ddd;">Name</th>';
    table += '<th style="padding: 12px 15px; border: 1px solid #ddd;">SKU</th>';
    table += '<th style="padding: 12px 15px; border: 1px solid #ddd;">Price</th>';
    table += '<th style="padding: 12px 15px; border: 1px solid #ddd;">Special Price</th>';
    table += '</tr>';
    
    if (data && data.length > 0) {
        data.forEach(function(item, index) {
            var rowStyle = index % 2 === 0 ? 'background-color: #f9f9f9;' : '';
            table += '<tr style="' + rowStyle + '">';
            table += '<td style="padding: 12px 15px; border: 1px solid #ddd;">' + item.name + '</td>';
            table += '<td style="padding: 12px 15px; border: 1px solid #ddd;">' + item.sku + '</td>';
            table += '<td style="padding: 12px 15px; border: 1px solid #ddd;">' + item.price + '</td>';
            table += '<td style="padding: 12px 15px; border: 1px solid #ddd;">' + item.special_price + '</td>';
            table += '</tr>';
        });
    } else {
        table += '<tr>';
        table += '<td colspan="4" style="padding: 12px 15px; border: 1px solid #ddd; text-align: center;">Products not available according to this Active Tag</td>';
    }
    
    table += '</table>';
    
    j('#report-container').html(table);
}


