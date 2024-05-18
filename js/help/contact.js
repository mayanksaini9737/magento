var j = jQuery.noConflict();

j(document).ready(function () {
  j("body").on("click", ".edit-row", function (e) {
    var editButton = j(this);
    var editUrl = editButton.data("edit-url");
    var contactId = editButton.data("contact-id");

    var row = editButton.closest("tr");

    var editableCells = row.find("td.editable");

    editableCells.each(function () {
    
      let originalValue = j(this).text().trim();
      j(this).html(
        '<input type="text" class="edit-input" value="' +
          originalValue +
          '" data-original="' +
          originalValue +
          '">'
      );
    });

    editButton.hide();

    var saveButton = j(
      '<a href="#" data-edit-url="' +
        editUrl +
        '" data-contact-id="' +
        contactId +
        '" class="save-button">Save</a>'
    );
    var cancelButton = j(
      '<a href="#" style="padding-left:5px" data-edit-url="' +
        editUrl +
        '" data-contact-id="' +
        contactId +
        '"  class="cancel-button">Cancel</a>'
    );
    var buttonContainer = j("<div>")
      .addClass("button-container")
      .append(saveButton, cancelButton);

    var cell = editButton.closest("td");
    cell.empty().append(buttonContainer);

    editableCells.first().find(".edit-input").focus();
  });

  j("body").on("click", ".cancel-button", function (e) {
    e.preventDefault();
    var cancelButton = j(this);
    var editUrl = cancelButton.data("edit-url");
    var contactId = cancelButton.data("contact-id");

    var row = cancelButton.closest("tr");

    var editableCells = row.find("td.editable");

    editableCells.each(function () {
      var originalValue = j(this).find(".edit-input").data("original");
      j(this).text(originalValue);
    });

    var cell = cancelButton.closest("td");
    var a = document.createElement("a"); // Create a new anchor element
    a.innerText = "Edit";
    a.setAttribute("href", "#");
    a.setAttribute("class", "edit-row");
    a.setAttribute("data-edit-url", editUrl);
    a.setAttribute("data-contact-id", contactId);
    cell.empty().html(a);
  });

  j("body").on("click", ".save-button", function (e) {
    e.preventDefault();
    var saveButton = j(this);
    var editUrl = saveButton.data("edit-url");
    var contactId = saveButton.data("contact-id");

    var row = saveButton.closest("tr");

    var editableCells = row.find("td.editable");
    var editedData = {};

    var fields = ['name', 'number', 'city'];
    var count = 0;

    editableCells.each(function () {
        let field = fields[count];
        let value = j(this).find(".edit-input").val().trim();
        editedData[field] = value;
        j(this).text(value);
        count++;
    });

    var params = { edited_data: editedData, contact_id: contactId };
    var data = JSON.stringify(params);
    new Ajax.Request(editUrl, {
      method: "post",
      parameters: { edited_data: data },
      onSuccess: function (response) {
        console.log(response);
      },
    });

    var cell = saveButton.closest("td");
    var a = new Element("a");
    a.innerText = "Edit";
    a.setAttribute("href", "#");
    a.setAttribute("class", "edit-row");
    a.setAttribute("data-edit-url", editUrl);
    a.setAttribute("data-contact-id", contactId);
    cell.empty().html(a);
  });

});
