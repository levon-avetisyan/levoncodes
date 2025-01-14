(function($) {
    $(document).ready(function() {
        setupFade();
        setupClickToScroll();
        // setupPostAnimation();
        setupScrollToTop();
        // enableScrollAbortion();

        // Trigger window.scroll, this will initiate some of the scripts
        $('body').scroll();
    });

    // Allow user to cancel scroll animation by manually scrolling
    // function enableScrollAbortion() {
    //     var viewport = $('html, body');
    //     viewport.on('scroll mousedown DOMMouseScroll mousewheel keyup', function(e) {
    //         if ( e.which > 0 || e.type === 'mousedown' || e.type === 'mousewheel') {
    //             viewport.stop();
    //         }
    //     });
    // }

    function setupScrollToTop() {
        var scrollSpeed = 750;
        $('.trigger-scroll-to-top').click(function(e) {
            e.preventDefault();
            $('body').animate({
                scrollTop: 0
            }, scrollSpeed);
        });
    }

    function setupPostAnimation() {
        var posts = $('.post-wrapper .post');
        $('body').on('scroll resize', function() {

            var currScroll = $(window).scrollTop() > $(document).scrollTop() ? $(window).scrollTop() : $(document).scrollTop(),
                windowHeight = $(window).height(), // Needs to be here because window can resize
                overScroll = Math.ceil(windowHeight*.20),
                treshhold = (currScroll + windowHeight) - overScroll;

            posts.each(function() {

                var post = $(this),
                    postScroll = post.offset().top

                if(postScroll > treshhold) {
                    post.addClass('hidden');
                } else {
                    post.removeClass('hidden');
                }

            });

        });
    }

    function setupFade() {

        var posts = $('.post-wrapper .post').reverse(),
            stemWrapper = $('.stem-wrapper'),
            halfScreen = $('body').height() / 2;

        $('body').on('scroll resize', function() {

            delay(function() {

                var currScroll = $('body').scrollTop() > $(document).scrollTop() ? $(window).scrollTop() : $(document).scrollTop(),
                    scrollSplit = currScroll + halfScreen;

                posts.each(function() {

                    var post = $(this),
                        postOffset = post.offset().top;

                    if(scrollSplit > postOffset - 60) {

                        // Add active class to fade in
                        post.addClass('active');

                        // Get post color
                        var color = post.data('stem-color') ? post.data('stem-color') : null,
                            allColors = 'color-green color-yellow color-white';

                        stemWrapper.removeClass(allColors);

                        if(color !== null) {
                            stemWrapper.addClass('color-' + color);
                        }

                        return false;
                    }

                });
            }, 0);

        });

    }

    function setupClickToScroll(post) {

        var scrollSpeed = 750;

        $('.post-wrapper .post .stem-overlay .icon-process').click(function(e) {
            e.preventDefault();

            var icon = $(this),
                post = icon.closest('.post'),
                postTopOffset = post.offset().top,
                postHeight = post.height(),
                halfScreen = $(window).height() / 2,
                scrollTo = postTopOffset - halfScreen + (postHeight/2);

            $('html, body').animate({
                scrollTop: scrollTo
            }, scrollSpeed);
        });

    }

})(jQuery);

/*==========  Helpers  ==========*/

// Timeout function
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

$.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};