jQuery(function($){
    var page = 2;
    var loadmore = 'on';
    $(document).on('scroll resize', function() {
        if ($(window).scrollTop() + $(window).height() + 60 > $(document).height()) {
            if (loadmore == 'on') {
                loadmore = 'off';
                $('#lazyload')
                .append($('<table class="cal-table-list page" id="p' + page + '">')
                .load(eeCalTable.url + '?calpage=' + page + ' table.page > *', function() {
                    page++;
                    loadmore = 'on';
                }));
            }
        }
    });
    $(document).ajaxComplete(function( event, xhr, options ) {
        if (xhr.responseText.indexOf('class="event-row"') == -1) {
            loadmore = 'off';
        }
    });
});