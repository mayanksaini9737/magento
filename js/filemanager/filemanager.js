var j = jQuery.noConflict();
j(document).ready(function () {

    j("body").on("change", ".path-value", function (e) {
        e.preventDefault();
        var path = j(this).val();
        var url = j(this).data('url');
        new Ajax.Request(url, {
            method: "post",
            parameters: { 'dir': path},
            onSuccess: function (response) {
                // document.body.innerHTML = response.responseText;
                console.log(j('#filemanagerGrid'));
                j('#filemanagerGrid').html(response.responseText);              
            },
        });
    });


    j("body").on("dblclick", ".editable", function () {
        var originalContent = j(this).text();
        var editUrl = j(this).data('url');
        var dirpath = j(this).data('dirname');
        var extension = j(this).data('extension');
        
        // console.log(dirpath);
        var input = j('<input type="text" value="' + originalContent + '" />');
        var saveButton = j('<button style="margin-left:5px" class="save-button">Save</button>');
        var cancelButton = j('<button style="margin-left:5px" class="cancel-button">Cancel</button>');

        j(this).html(input);
        j(this).append(saveButton);
        j(this).append(cancelButton);

        input.focus();

        saveButton.on("click", function () {
            var newContent = input.val();

            new Ajax.Request(editUrl, {
                method: "post",
                parameters: { value: newContent, directory:dirpath, oldname:originalContent, ext: extension },
                onSuccess: function (response) {
                    console.log(response);
                    input.parent().text(newContent);
                },
                onError: function () {
                    alert("An error occurred while renaming the file.");
                    input.parent().text(originalContent);
                }
            });
        });

        cancelButton.on("click", function () {
            input.parent().text(originalContent);
        });
    });     

    // j("body").on("click", ".delete", function (e) {
    //     e.preventDefault();
    //     var filename = j(this).data('filename');
    //     var url = j(this).data('url');
    //     new Ajax.Request(url, {
    //         method: "post",
    //         parameters: { 'filename': filename},
    //         onSuccess: function (response) {
    //           console.log(response);  
    //         },
    //     });
    // });

    // j("body").on("click", ".download", function (e) {
    //     e.preventDefault();
    //     var filename = j(this).data('filename');
    //     var fullpath = j(this).data('fullpath');
    //     var url = j(this).data('url');

    //     // console.log(fullpath);
    //     // console.log(filename);
    //     new Ajax.Request(url, {
    //         method: "post",
    //         parameters: { 'filename': filename, 'fullpath': fullpath},
    //         onSuccess: function (response) {
    //         //   window.location.reload();
    //         },
    //     });
    // });

});
