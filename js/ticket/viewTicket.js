var j = jQuery.noConflict();

j(document).ready(function () {
  if (j.find('.comment-section').length){
    
  CKEDITOR.replace("comment", {
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
}


  j("body").on("change", ".editable", function (e) {
    e.preventDefault();
    var id = j(this).data("id");
    var updateUrl = j(this).data("url");
    var column = j(this).attr("name");
    var value = j(this).val();

    new Ajax.Request(updateUrl, {
      method: "post",
      parameters: { ticket_id: id, field: column, val: value },
      onSuccess: function (response) {
        var jsonResponse = JSON.parse(response.responseText);
        if (jsonResponse.colorCode) {
          j(".status").css("background-color", jsonResponse.colorCode);
        }
      },
    });
  });

  j("body").on("click", ".reply-btn", function () {
    var button = j(this);
    addReplyRow(button);
  });

  j("body").on("click", ".save-reply-btn", function () {
    saveReply(j(this));
  });

  j("body").on("click", ".cancel-reply-btn", function () {
    cancelReply(j(this));
  });

  j("body").on("click", ".complete-btn", function () {
    completeReply(j(this));
  });
  
  j("body").on("click", ".add-question-btn", function () {
    addQuestion(j(this));
  });
  
  j("body").on("click", ".save-question-btn", function () {
    saveQuestion(j(this));
  });

  j("body").on("click", ".lock-btn", function () {
    lockLevel(j(this));
  });

  var counter = 1;
  function addReplyRow(button) {
    var commentCell = button.closest("tr");
    var commentId = button.data('commentid');
    var level = button.data('level');
    var ticketId = button.data('ticketid');
    var saveUrl = button.data('url');
    counter++

    var replyRowHtml =
      '<tr>' +
      '<td class="reply-td">' +
      '<textarea id="textarea-'+counter+'" class="reply-textarea"></textarea>' +
      '<button class="save-reply-btn"'+
       'data-commentid="'+ commentId +'"'+
       'data-level="'+ level +'"' +  
       'data-url="'+ saveUrl +'"' +  
       'data-ticketid="'+ ticketId +'" >Save</button>' +
      '<button class="cancel-reply-btn">Cancel</button>' +
      '</td>' +
      '</tr>';
      commentCell.find('.complete-btn').hide();
      commentCell.append(replyRowHtml);


      CKEDITOR.replace('textarea-'+counter, {
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

  }

  function saveReply(button) {
    var replyRow = button.closest(".reply-td");
    // var textarea = replyRow.find(".reply-textarea");
    // var replyTextDefalut = textarea.val().trim();  
    var textareaId = replyRow.find("textarea").attr("id");
    replyText = CKEDITOR.instances[textareaId].getData().trim();

    if (replyText === "") {
      alert("Please enter a reply."); 
      return;
    }

    console.log(replyText);

    var commentId = button.data('commentid');
    var level = button.data('level');
    var ticketId = button.data('ticketid');
    var saveUrl = button.data('url');
    var newLevel = level + 1;

    new Ajax.Request(saveUrl, {
      method: "post",
      parameters: { parentId: commentId, level:newLevel, id: ticketId, reply:replyText },
      onSuccess: function (response) {
        j('.comment-table').html(response.responseText);
      },
    });

    // replyRow.append(replyText); 

    // textarea.remove();
    // replyRow.find(".save-reply-btn").remove();
    // replyRow.find(".cancel-reply-btn").remove();
  }

  function cancelReply(button) {
    var replyTrLength = button.closest('.reply-td').parent().siblings('tr').length;
    if (!replyTrLength){
      button.closest(".reply-td").parent().parent().find('.complete-btn').show();
    }
    var replyRow = button.closest(".reply-td");
    replyRow.parent().remove();
  }

  function completeReply(button) {
    var commentId = button.data('commentid');
    var completeUrl = button.data('url');
    button.siblings('button').hide();

    new Ajax.Request(completeUrl, {
      method: "post",
      parameters: { comment_id:commentId},
      onSuccess: function (response) {
      },
    });
    button.remove();
  }

  function addQuestion(button){
    var level = button.data('level');
    var ticketId = button.data('ticketid');
    var saveUrl = button.data('url');
    var colspanLevel = level - 1;
    counter++
    
    var commentCell = button.closest("tr");
    if (level == 1){
      var replyRowHtml =
        '<tr>' +
        '<td class="reply-td">' +
        '<textarea id="textarea-'+counter+'" class="reply-textarea"></textarea>' +
        '<button class="save-question-btn"'+
         'data-level="'+ level +'"' +  
         'data-url="'+ saveUrl +'"' +  
         'data-ticketid="'+ ticketId +'" >Save</button>' +
        '<button class="cancel-reply-btn">Cancel</button>' +
        '</td>' +
        '</tr>';
      } else {
        var replyRowHtml =
        '<tr>' +
        '<td colspan="'+ colspanLevel +'"></td>' +
        '<td class="reply-td">' +
        '<textarea id="textarea-'+counter+'"  class="reply-textarea"></textarea>' +
        '<button class="save-question-btn"'+
        'data-level="'+ level +'"' +  
        'data-url="'+ saveUrl +'"' +  
        'data-ticketid="'+ ticketId +'" >Save</button>' +
        '<button class="cancel-reply-btn">Cancel</button>' +
        '</td>' +
        '</tr>';
    }

    commentCell.before(replyRowHtml);
    CKEDITOR.replace('textarea-'+counter, {
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
  }

  function saveQuestion(button) {
    var replyTd = button.closest(".reply-td");
    // var textarea = replyTd.find(".reply-textarea");
    // var replyText = textarea.val().trim();  
    var textareaId = replyTd.find(".reply-textarea").attr('id');
    var replyText = CKEDITOR.instances[textareaId].getData().trim();
    console.log(replyText)

    if (replyText === "") {
      alert("Please enter a reply."); 
      return;
    }

    var level = button.data('level');
    var ticketId = button.data('ticketid');
    var saveUrl = button.data('url');
    
    new Ajax.Request(saveUrl, {
      method: "post",
      parameters: { level:level, ticketId: ticketId, reply:replyText },
        onSuccess: function (response) {
          var responseData = JSON.parse(response.responseText);

          var comment_id = responseData.comment_id;
          var reply_url = responseData.reply_url;
          var complete_url = responseData.complete_url;

          replyTd.append(replyText); 
            
          // textarea.remove();
          CKEDITOR.instances[textareaId].destroy(true);
          replyTd.find('textarea').remove();
          replyTd.find("button").remove();

      
          var btns = '<button class="reply-btn" data-commentid="' + comment_id + '" data-level="' + level + '" data-ticketid="' + ticketId + '" data-url="' + reply_url + '">Add Reply</button>' +
            '<button class="complete-btn"  data-commentid="' + comment_id + '" data-ticketid="' + ticketId + '" data-url="' + complete_url + '">Complete</button>';

          replyTd.append(btns);
      },
    });
  }

  function lockLevel(button){
    var level = button.data('level');
    var ticketId = button.data('ticketid');
    var lockUrl = button.data('url');
    var complete = j('.selectCompleted').val();

    new Ajax.Request(lockUrl, {
      method: "post",
      parameters: { level:level, id:ticketId, filter:complete },
      onSuccess: function (response) {
        j('.comment-table').html(response.responseText);
      },
    });
  }

});
