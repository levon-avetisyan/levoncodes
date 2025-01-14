$(document).ready(function () {
    // Fade
    $('body').scroll(function(){
        $(".fadeAway,.fsTitle,.fsRow").css("opacity", 1 - $('body').scrollTop() /350);
    });

    // Fixed Header

    $('body').on('scroll', function () {
        if ($('body').scrollTop() > 60) {
            $('.main-header').addClass('header-fixed');
        }
        if ($('body').scrollTop() < 61) {
            $('.main-header').removeClass('header-fixed');
        }
    });

    // Hamburger
    $('#hamburgerToggle').click(function () {
        $(this).toggleClass("cross");
    });

    // Navigation Link
    $('#logo').click(function () {
        $('body').animate({
            scrollTop: $("#hero").offset().top - 60
        }, 850);
    });

    $('#linkAboutDesktop, #linkAbout, #heroLinkAbout, #heroScroll, #heroBtnLink').click(function () {
        $('body').animate({
            scrollTop: $("#about").offset().top - 60
        }, 850);
    });

    $('#linkProcess').click(function () {
        $('body').animate({
            scrollTop: $("#process").offset().top - 60
        }, 850);
    });

    $('#linkWorksDesktop, #linkWorks', '#works').click(function () {
        $('body').animate({
            scrollTop: $("#works").offset().top - 60
        }, 850);
    });

    $('#linkContactDesktop, #linkContact').click(function () {
        $('body').animate({
            scrollTop: $("#contacts").offset().top - 60
        }, 850);
    });
    
    $('#linkAbout','#linkWorks, #linkContact, #linkProcess').click(function () {
        $('#hamburgerToggle').toggleClass("cross");
    });

    // Owl Carousel
    $('.owl-carousel').owlCarousel({
        autoplay: true,
        autoplaySpeed: 7000,
        margin: 60,
        loop: true,
        center: true,
        nav: false,
        responsive: {
            0: {
                items: 1,
                stagePadding: 30
            },
            600: {
                items: 1,
                stagePadding: 40
            },
            1000: {
                items: 1,
                stagePadding: 80
            },
            1240: {
                items: 1,
                stagePadding: 400
            }
        }
    });


    // ScrollReveal
    $('body').sr = ScrollReveal();
    sr.reveal('.card');


    // JqueryTilt
    $('.js-tilt').tilt({
        maxTilt: 4,
        perspective: 1400,   // Transform perspective, the lower the more extreme the tilt gets.
        easing: "cubic-bezier(.25, .8, .25, 1)",    // Easing on enter/exit.
        scale: 1,      // 2 = 200%, 1.5 = 150%, etc..
        speed: 160,    // Speed of the enter/exit transition.
        transition: false,   // Set a transition on enter/exit.
        disableAxis: null,   // What axis should be disabled. Can be X or Y.
        reset: true,   // If the tilt effect has to be reset on exit.
        glare: false,  // Enables glare effect
        maxGlare: 1       // From 0 - 1.
    });


    // hero mouse perspective
    var lFollowX = 0,
        lFollowY = 0,
        x = 0,
        y = 0,
        friction = 1 / 30;

    function moveBackground() {
        x += (lFollowX - x) * friction;
        y += (lFollowY - y) * friction;

        translate = 'translate(' + x + 'px, ' + y + 'px) scale(1.1)';

        $('.bg').css({
            '-webit-transform': translate,
            '-moz-transform': translate,
            'transform': translate
        });

        window.requestAnimationFrame(moveBackground);
    }

    $(window).on('mousemove click', function (e) {

        var lMouseX = Math.max(-100, Math.min(100, $(window).width() / 2 - e.clientX));
        var lMouseY = Math.max(-100, Math.min(100, $(window).height() / 2 - e.clientY));
        lFollowX = (20 * lMouseX) / 100; // 100 : 12 = lMouxeX : lFollow
        lFollowY = (10 * lMouseY) / 100;

    });

    moveBackground();


    // user agent class
    var retina = window.devicePixelRatio > 1;
    if (retina) {
        $('body').addClass('retina');
    }

    $.each($.browser, function (i) {
        $('body').addClass(i);
        return false;
    });

    var os = [
        'iphone',
        'ipad',
        'windows',
        'mac',
        'linux'
    ];

    var match = navigator.appVersion.toLowerCase().match(new RegExp(os.join('|')));
    if (match) {
        $('body').addClass(match[0]);
    };
    
});

