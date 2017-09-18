$(document).ready(function() {
    $('#banner-home').owlCarousel({
        navigation: true,
        singleItem: true,
        autoHeight: false,
        items: 1,
        center: true,
        loop: true,
        autoplay: true,
        animateOut: 'fadeOut',
        mouseDrag: false,
        autoplaySpeed: 6000
    });

    $('.project--caroucel .owl-carousel').imagesLoaded( function() {
        $('.project--caroucel .owl-carousel').owlCarousel({
            singleItem: true,
            autoHeight: true,
            autoWidth: true,
            items: 1,
            center: true,
            loop: true,
            autoplay: true,
            mouseDrag: true,
            nav: true,
            margin: 10,
            navText: [
                '<button><span class="icon icon-arrow-left4"></span></button>',
                '<button><span class="icon icon-arrow-right4"></span></button>'
            ],
            responsive: {
                0: {
                    stagePadding: 30,
                    autoWidth: false
                },
                480: {
                    stagePadding: 0,
                    autoWidth: true
                }
            }
        });
    });

    $('.channel-slider--home').owlCarousel({
        nav: true,
        dots: false,
        margin: 8,
        navText: [
            '<button><span class="icon icon-arrow-left4"></span></button>',
            '<button><span class="icon icon-arrow-right4"></span></button>'
        ],
        mouseDrag: false,
        responsive: {
            0: {
                items: 1,
            },
            320: {
                items: 2
            },
            768: {
                items: 3
            },
            1024: {
                items: 4
            },
            1280: {
                items: 5
            },
            1600: {
                items: 6
            }
        }
    });

    $('.channel-slider').owlCarousel({
        nav: true,
        dots: false,
        margin: 8,
        navText: [
            '<button><span class="icon icon-arrow-left4"></span></button>',
            '<button><span class="icon icon-arrow-right4"></span></button>'
        ],
        mouseDrag: false,
        responsive: {
            0: {
                items: 1,
            },
            320: {
                items: 2
            },
            768: {
                items: 3
            },
            1024: {
                items: 5
            }
        }
    });

    $('#search-btn').on('click', function (e) {
        e.preventDefault();
        console.log('hello');
        $('.search').toggleClass('search-active');
    });

    $('#manu-btn').on('click', function(e) {
       e.preventDefault();
       $('.mainmenu').toggleClass('mainmenu-active');
    });

    $('#user-button').fancybox({
        arrows: false,
        margin: 0,
        smallBtn: false,
        toolbar: false
    });

    function mouse_bottom() {
        $('#mouse-bottom').animate({
            'bottom': '0'
        }, 400).animate({
            'bottom': '-5px'
        }, 800, mouse_bottom);

        $('#mouse-bottom').on('click', function(e) {
            e.preventDefault();
            $(window).scrollTo( $('#news'), {duration: 200});
        });
    }
    mouse_bottom();
});