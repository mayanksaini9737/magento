var j = jQuery.noConflict();
j(document).ready(function () {

    j("body").on("click", "#save_report_button", function (e) {
        e.preventDefault();
        var url = 'http://localhost/magento/index.php/admin/reportmanager/saveReport';
        
        // var params = {filterArray: filterElement};
        var data = JSON.stringify(filterElement);
        console.log(filterElement);
        
        new Ajax.Request(url, {
            method: "post",
            parameters: { filterData: data },
            onSuccess: function (response) {
                console.log(response);
                var responseData = response.responseJSON;
                createTable(responseData);
            },
        });
    });
});

var filterElement = [];
(varienGrid.prototype.doFilter = function () {
    var filters = $$('#'+this.containerId+' .filter input', '#'+this.containerId+' .filter select');
    var elements = [];
    var addedKeys = new Set();
    for(var i in filters){
        if (filters[i].value && filters[i].value.length) {
            elements.push(filters[i]);

            var filterKey = filters[i].name; 
            var filterValue = filters[i].value;

            if (!addedKeys.has(filterKey)) {
                filterElement.push({
                    key: filterKey,
                    value: filterValue
                });
                addedKeys.add(filterKey); 
            }
        }
    }
    if (!this.doFilterCallback || (this.doFilterCallback && this.doFilterCallback())) {
        this.reload(this.addVarToUrl(this.filterVar, encode_base64(Form.serializeElements(elements))));
    }
});
  