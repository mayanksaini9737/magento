var j = jQuery.noConflict();

j(document).ready(function() {
    var ticketManagement = j('.ticket-management');
    var currentPage = ticketManagement.data('currentpage');
    var totalPages = ticketManagement.data('totalpages');
    var baseUrl = ticketManagement.data('baseurl');
    var filterId = ticketManagement.data('filterid');

    updatePagination(currentPage, totalPages, baseUrl, filterId);
    var modal = j('.filterModal');
    
    var filterLink = j('.add-filter-btn');
    var closeBtn = j('.close');

    filterLink.click(function (event) {
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

function updatePagination(currentPage, totalPages, baseUrl, filterId) {
    var paginationHtml = '';
    if (totalPages > 1) {
        paginationHtml += '<li><a href="javascript:void(0);" onclick="loadPage(1, \'' + baseUrl + '\' , \'' + filterId + '\')">First</a></li>';
        for (var i = 1; i <= totalPages; i++) {
            paginationHtml += '<li ' + (currentPage === i ? 'class="active"' : '') + '><a href="javascript:void(0);" onclick="loadPage(' + i + ', \'' + baseUrl + '\', \'' + filterId + '\')">' + i + '</a></li>';
        }
        paginationHtml += '<li><a href="javascript:void(0);" onclick="loadPage(' + totalPages + ', \'' + baseUrl + '\', \'' + filterId + '\')">Last</a></li>';
    }
    j('.pagination').html(paginationHtml);
}

function loadPage(page, baseUrl, filterId) {
    var redirectlink = baseUrl + '?page=' + page;
    if (filterId>0){
        redirectlink += '&filter_id=' + filterId;
    }
    window.location.href = redirectlink;
}