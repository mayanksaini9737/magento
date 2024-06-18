var j = jQuery.noConflict();
function updatePagination() {
    var paginationHtml = '';
    if (totalPages > 1) {
        paginationHtml += '<li><a href="javascript:void(0);" onclick="loadPage(1)">First</a></li>';
        for (var i = 1; i <= totalPages; i++) {
            paginationHtml += '<li ' + (currentPage === i ? 'class="active"' : '') + '><a href="javascript:void(0);" onclick="loadPage(' + i + ')">' + i + '</a></li>';
        }
        paginationHtml += '<li><a href="javascript:void(0);" onclick="loadPage(' + totalPages + ')">Last</a></li>';
    }
    j('.pagination').html(paginationHtml);
}

function loadPage(page) {
    window.location.href = baseUrl + '?page=' + page;
}

j(document).ready(function() {
    updatePagination();
});