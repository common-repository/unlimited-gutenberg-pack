( function( $ ) {
    $( document ).ready( function() {
        var titleWrap = '.wp-block-gutenbergpack-tabbed-content-tab-title-wrap';
        var contentWrap = '.wp-block-gutenbergpack-tabbed-content-tab-content-wrap';
        $( document )
            .on( 'click', '.wp-block-gutenbergpack-tabbed-content-tab-title-wrap', function() {
                var parent = $(this)
                    .closest('.wp-block-gutenbergpack-tabbed-content');
                var contentWrapEl = parent
                    .find('.wp-block-gutenbergpack-tabbed-content-tab-content-wrap');
                var activeStyle = parent
                    .find('.wp-block-gutenbergpack-tabbed-content-tab-title-wrap.active')
                    .attr('style');
                var defaultStyle = parent
                    .find('.wp-block-gutenbergpack-tabbed-content-tab-title-wrap:not(.active)')
                    .attr('style');

                $(this).siblings(titleWrap).removeClass('active').attr('style', defaultStyle);
                $(this).addClass('active').attr('style', activeStyle);

                contentWrapEl.removeClass('active').addClass('gutenbergpack-hide');
                parent.find(contentWrap +':nth-of-type(' + ($(this).index() + 1) + ')')
                    .addClass('active')
                    .removeClass('gutenbergpack-hide');
            } );
    } );
}( jQuery ) );