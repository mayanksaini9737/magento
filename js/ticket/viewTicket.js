var j = jQuery.noConflict();

j(document).ready(function () {
    CKEDITOR.replace('comment', {
        toolbar: [
          { name: "basicstyles", items: ["Bold", "Italic", "Underline"] },
          { name: "styles", items: ["Format"] },
          {
            name: "paragraph",
            items: ["NumberedList", "BulletedList", "-", "Blockquote"],
          },
          { name: "links", items: ["Link", "Unlink"] },
          { name: "insert", items: ["Image"] },
          { name: "tools", items: ["Maximize"] },
          { name: "editing", items: ["Undo", "Redo"] },
        ],
      });

    j("body").on("change", ".editable", function (e) {
        e.preventDefault();
        var id = j(this).data('id');
        var column = j(this).attr('name');
        var value = j(this).val();
        
        new Ajax.Request(updateUrl, {
            method: "post",
            parameters: {ticket_id: id, field: column, val: value},
            onSuccess: function (response) {
                var jsonResponse = JSON.parse(response.responseText);
                if (jsonResponse.colorCode){
                    j('.status').css('background-color', jsonResponse.colorCode);
                }
            },
        });
    });

});
