var j = jQuery.noConflict();

j(document).ready(function () {
    j('#ticket-form').submit(function (event) {
        event.preventDefault(); 

        var formData = j(this).serialize(); 
        
        new Ajax.Request(submitUrl, {
            method: "post",
            parameters: formData,
            onSuccess: function (response) {
                j('#create-ticket-modal').hide(); 
                ticketForm[0].reset();
            },
        });
    });

    var modal = j('#create-ticket-modal');
    var ticketLink = j('.create-ticket-link');
    var closeBtn = j('.close');

    ticketLink.click(function (event) {
        event.preventDefault();
        modal.show();
    });

    closeBtn.click(function () {
        modal.hide();
    });

    j(window).click(function (event) {
        if (event.target === modal[0]) {
            modal.hide();
        }
    });
});
