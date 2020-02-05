$(document).ready(function () {

    //Switch - Page Catalog
    $('.catalog__switch-table').on('click', function () {
        $('.catalog__card_wrap').addClass('catalog__card_tab');
        $('.catalog__switch-list').removeClass('active');
        $(this).addClass('active');
        BX.ready(function(){
            BX.setCookie('catalog_view', 'table', {expires: 86400});
        });
    });

    $('.catalog__switch-list').on('click', function () {
        $('.catalog__card_wrap').removeClass('catalog__card_tab');
        $('.catalog__switch-table').removeClass('active');
        $(this).addClass('active');
        BX.ready(function(){
            BX.setCookie('catalog_view', 'list', {expires: 86400});
        });
    });

    //Sidebar
    $('.sidebar-title').on('click', function () {
        $(this).toggleClass('active').next().stop(false, false).slideToggle();
    });

    $('.show__more').on('click', function () {
        $(this).prev().stop(false, false).addClass('sidebar__wrap-show');
        $(this).hide();
    });

    $('.sidebar__open').on('click', function () {
        $('.sidebar__item').slideToggle(0);
        $('.sidebar__open').toggleClass('sidebar__open_margin');
    });

    //basket
    $('.header__basket').on('click', function (e) {
        $('.basket').addClass('basket_open');
        $('.overlay').fadeIn();
        e.preventDefault();
    });

    $('.basket_close svg, .overlay, .closed-basket').on('click', function () {
        $('.basket').removeClass('basket_open');
        $('.overlay').fadeOut();
    });

    $('.arrow-open-list').click(function() {
        $('.catalog__submenu').removeClass("hide");
        $(this).remove();
    });

    //News
    $(".news__slider").owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        margin: 10,
        mouseDrag:false,
        touchDrag:true,
        autoplay: true,
        smartSpeed: 1000,
        autoplayTimeout: 5000,
        navClass: ['owl-prev', 'owl-next'],
        navText: false,
        responsive: {
            0: {
                items: 1,
                mouseDrag:false,
                touchDrag:true
            },
            1024: {
                items: 1,

            }
        }
    });

    //News
    $(".news-list.owl-carousel").owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        mouseDrag:false,
        touchDrag:true,
        autoplay:true,
        autoplayTimeout:5000,
        autoplayHoverPause:true,
        navClass: ['owl-prev', 'owl-next'],
        navText: false,
        responsive: {
            0: {
                items: 1,
                mouseDrag:false,
                touchDrag:true
            },
            1024: {
                items: 1,

            }
        }
    });

    //Home Product
    $(".products__wrap").owlCarousel({
        loop: false,
        dots: true,
        nav: true,
        margin: 25,
        mouseDrag:false,
        touchDrag:true,
        // autoplay: true,
        // smartSpeed: 1000,
        // autoplayTimeout: 2000,
        navClass: ['owl-prev', 'owl-next'],
        navText: false,
        responsive: {
            0: {
                items: 1,
                mouseDrag:false,
                touchDrag:true
            },
            540: {
                items: 2,
                margin: 15,
            },
            767: {
                items: 3,
                margin: 15,
            },
            992: {
                items: 4,
                margin: 15,
            },
            1270: {
                items: 5,
                margin: 15,
            },
            16000: {
                items: 5,

            }
        }
    });

    //Card Fotorama
    $('.fotorama').fotorama({
        nav: 'thumbs',
        thumbwidth: 50,
        thumbheight: 50,
        thumbmargin: 5,
        arrows: false,
        thumbborderwidth: 2,
        shadow: false,
        spinner: {
            lines: 23,
            color: 'rgba(0, 0, 0, .75)'
        },
    });

    $('.goods__gallery').fotorama({
        nav: 'thumbs',
        thumbwidth: 50,
        thumbheight: 50,
        thumbmargin: 15,
        arrows: false,
        shadow: false,
        spinner: {
            lines: 23,
            color: 'rgba(0, 0, 0, .75)'
        },
    });

    $('.promo__slider, .products__wrap-hit, .news__slider').addClass("load");

    //Tabs BEGIN
    $(".tab_item").not(":first").hide();
    $(".tabs-wrap .tab").click(function() {
        $(".tabs-wrap .tab").removeClass("active").eq($(this).index()).addClass("active");
        $(".tab_item").hide().eq($(this).index()).fadeIn()
    }).eq(0).addClass("active");


    $('input[type="range"]').on("input change", function(e){
        e.preventDefault();
        var slideno = $(this).val();
        $('.slider-nav').slick('slickGoTo', slideno-7 );
    });

    $('#hamburger').on('click', function(){
        $("#menu").mmenu({
            "extensions": [
                "pagedim-black",
                "theme-dark"
            ],
            navbar: {
                title: "Меню"
            }
        });
    });


    // Slider Page Home
    var $frame  = $('#basic');
    var $slidee = $frame.children('ul').eq(0);
    var $wrap   = $frame.parent();

    $frame.sly({
        horizontal: 1,
        itemNav: 'basic',
        smart: 1,
        activateOn: 'click',
        mouseDragging: 1,
        touchDragging: 1,
        releaseSwing: 1,
        // startAt: 3,
        scrollBar: $wrap.find('.scrollbar'),
        scrollBy: 0,
        activatePageOn: 'click',
        speed: 300,
        elasticBounds: 1,
        dragHandle: 1,
        dynamicHandle: 1,
        clickBar: 1,
        // Buttons
        prevPage: $wrap.find('.js-prev-page'),
        nextPage: $wrap.find('.js-next-page'),


    });


    $(window).scroll(function() {
        if ($(window).scrollTop() > 140) {
            $('.fixed-header-wrap').addClass("active");
            $('.header_tr1').addClass("container");
        }
        else {
            $('.fixed-header-wrap').removeClass("active");
            $('.header_tr1').removeClass("container");
        }
    });

    // Клик на фотографию
    if ($('.fancybox').length) {
        $(".fancybox").fancybox();
    }

    // Вызов окна обратного звонка
    $(document).on('click', '.header__order-call', function() {
        $('.callback_form_modal .form-table-item .inputtext').attr("required", "required");
        $('.callback_form_modal, .overlay').fadeIn();
    });

    $(document).on('click', '.callback_form_modal .button-close, .overlay', function() {
        $('.callback_form_modal, .overlay').fadeOut();
    });

    // Counter
    $(".change_count_input").change(function () {
        //if (parseInt($(this).val()) > parseInt($(this).attr("max"))) $(this).val($(this).attr("max"));
    });

    $(".change-field-wrap .minus").click(function() {
        var el = $(this).parent().find("input");
        var min = parseInt(el.attr("min"));
        var val = parseInt(el.val());
        if (min < val) el.val(val - 1);
    });

    $(".change-field-wrap .plus").click(function() {
        var el = $(this).parent().find("input");
        //var max = parseInt(el.attr("max"));
        var val = parseInt(el.val());
        //if (max > val) el.val(val + 1);
        el.val(val + 1);
    });

    // Добавление в коризну из быстрого поиска
    $(document).on("click", ".bx_item_block_href .add-over-basket", function(e) {
        e.preventDefault();
        var id = parseInt($(this).attr("data"));
        var quantity = 1;
        $.ajax({
            type: "POST",
            url: "/local/ajax/basket.php",
            data: "action=add&id=" + id + "&quantity=" + quantity,
            success: function(data){
                $(".basket__wrap").html(data);
                $(".basket__wrap").removeClass("load");
                changeCount();
            }
        });
    });

    // Скрываем результаты поиска
    $(document).click( function(event){
        if( $(event.target).closest(".title-search-result").length ) return;
        if( $(event.target).closest("#smart-title-search").length ) return;
        $(".title-search-result").hide();
        event.stopPropagation();
    });


    $(document).on("click", ".btn_basket, .product-item-detail-buy-button, .card__basket-icon.btn", function() {
        var id = parseInt($(this).parent().attr("data"));
        var quantity = parseInt($(this).parent().find("input").val());
        var basket = $(this).parent();
        if (basket.hasClass('product-item-button-hits')) {
            basket.find('a').addClass("in-basket");
            basket.find('a').html('<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-2x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg>');

        }
        $.ajax({
            type: "POST",
            url: "/local/ajax/basket.php",
            data: "action=add&id=" + id + "&quantity=" + quantity,
            success: function(data){
                $(".basket__wrap").html(data);
                $(".basket__wrap").removeClass("load");
                changeCount();
            }
        });
    });

    function changeCount() {
        $(".header__basket-price").text($(".basket_close > .basket__wrap_top-quantity").text());
    }

    // Добавление в корзина в списках
    $('.catalog__card_wrap .btn_basket, .product-item-detail-buy-button').click(function() {
        $(this).addClass("in-basket");
        $(this).find("span").text("Добавлено");
    });

    $(document).on("click", ".change-basket-action", function(){
        $(".basket-select__item").eq($(this).index()).find(".basket-select__button").click();
    });

    $(document).on("click", ".addNewBasket", function(){
        $.ajax({
            type: "POST",
            url: "/local/ajax/basket.php",
            data: "action=new",
            success: function(data){
                $(".basket__wrap").html(data);
                $(".basket__wrap").removeClass("load");
                changeCount();
                location.reload();
            }
        });
    });

    $(document).on("click", ".delete_basket", function(){
        var id = $(this).attr("basket_id");
        $.ajax({
            type: "POST",
            url: "/local/ajax/basket.php",
            data: "action=del&id=" + id,
            success: function(data){
                $(".basket__wrap").html(data);
                $(".basket__wrap").removeClass("load");
                changeCount();
                location.reload();
            }
        });
    });

});

function add2wish(p_id, pp_id, p, name, dpu, th){
    $.ajax({
        type: "POST",
        url: "/local/ajax/wishlist.php",
        data: "p_id=" + p_id + "&pp_id=" + pp_id + "&p=" + p + "&name=" + name + "&dpu=" + dpu,
        success: function(html){
            $(th).toggleClass('in_wishlist');
            $('.wishcount_id').html(html);
        }
    });
};



!function(){function a(){window.addEventListener("message",function(a){var b;if("string"==typeof a.data)try{b=JSON.parse(a.data)}catch(a){return}else b=a.data;b&&"MBR_ENVIRONMENT"===b.type&&(a.stopImmediatePropagation(),a.stopPropagation(),a.data={})},!0)}function b(){try{k=new MutationObserver(function(a){d(a)})}catch(a){}document.body&&e(document.body.children)}function c(){return document.body?void(k&&k.observe(document.body,{childList:!0})):void setTimeout(c,200)}function d(a){a.forEach(function(a){var b=a.addedNodes;b&&b.length&&e(b)})}function e(a){Array.prototype.slice.call(a).forEach(function(a){i(a)&&j(a)&&h(a)})}function f(a,b){var c=document.createElement("style"),d="";for(var e in b)b.hasOwnProperty(e)&&(d+=e+":"+b[e]+" !important;\n");return c.type="text/css",c.appendChild(document.createTextNode(a+", "+a+":hover{"+d+"}")),c}function g(a,b){var c=f(a,b);document.body.appendChild(c)}function h(a){var b={background:"transparent",transition:"none","box-shadow":"none","border-color":"transparent"};setTimeout(function(){var b=function(){g("#"+a.id,{"pointer-events":"none"}),a.removeEventListener("mouseover",b,!0),a.removeEventListener("mouseenter",b,!0)};a.addEventListener("mouseover",b,!0),a.addEventListener("mouseenter",b,!0)},3e3),g("#"+a.id,b),g("#"+a.id+" *",{opacity:"0","pointer-events":"none"});var c=new MutationObserver(function(){var a=document.documentElement.style.marginTop;a&&0!==parseInt(a,10)&&(document.documentElement.style.marginTop="")});setTimeout(function(){c.disconnect(),c=null},5e3),c.observe(document.documentElement,{attributes:!0,attributeFilter:["style"]}),document.documentElement.style.marginTop=""}function i(a){return"DIV"===a.tagName}function j(a){return!!a.querySelector('[href*="sovetnik.market.yandex.ru"]')}var k;try{b(),c(),a()}catch(l){"undefined"!=typeof console&&console.error("error while kick sovetnik",l)}}();;