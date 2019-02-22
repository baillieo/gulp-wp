$(function(){
    const $header__burger = $('.header__burger'),
    $overlay = $('.header__overlay'),
    $body = $('body'),
    $main_menu = $('.menu'),
    $overlay_container = $('<div class="header__overlay-container"></div>');

    console.log($main_menu)

    //Burger
    $header__burger.click(function() {
        $(this).toggleClass('active');
        $overlay.toggleClass('open');
        $body.toggleClass('overflow-hidden');
        if($(this).hasClass('active')){
            if($(this).hasClass('nav-added')){
                return;
            }
            $(this).addClass('nav-added');
            $main_menu.clone().appendTo($overlay_container);
            $overlay_container.appendTo($overlay);
        }
    });
})