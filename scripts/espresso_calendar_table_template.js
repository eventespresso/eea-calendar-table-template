jQuery(function($){
    var page = 2;
    var loadmore = true;
    $('.cal-table-list').append('<span class="load-more-datetimes"></span>');
    var trigger = $('.cal-table-list .load-more-datetimes');
    var scrollHandling = {
        allow: true,
        reallow: function() {
            scrollHandling.allow = true;
        },
        delay: 400
    };
    $(document).on('scroll resize', function() {
        if(loadmore & scrollHandling.allow){
            scrollHandling.allow = false;
            setTimeout(scrollHandling.reallow, scrollHandling.delay);
            var offset = $(trigger).offset().top - $(window).scrollTop();
            if (1000 > offset) {
                loadmore = false;
                $('#lazyload')
                .append($('<table class="cal-table-list page" id="p' + page + '">')
                .load(eeCalTable.url + '?calpage=' + page + ' table.page > *', function() {
                    page++;
                    loadmore = true;
                }));
            }
        }
    });
    $(document).ajaxComplete(function( event, xhr, options ) {
        if (xhr.responseText.indexOf('class="event-row"') == -1) {
            loadmore = false;
        }
    });
});