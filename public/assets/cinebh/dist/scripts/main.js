"use strict";$(document).ready(function(){$("body").on("click","a[href='']",function(a){a.preventDefault()}),$(".banner__items").owlCarousel({items:1,nav:!1,navText:["<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>","<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"],dots:!0,mouseDrag:!1,lazyLoad:!0,loop:!0,center:!0,autoplay:!0,autoplayTimeout:7e3,animateOut:"fadeOut"}),$(".gallery__list").owlCarousel({items:1,autoWidth:!0,nav:!0,navText:["<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left4'></span></button>","<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right4'></span></button>"],dots:!1,mouseDrag:!1,loop:!0,center:!0,autoplay:!1,autoplayTimeout:7e3}),$("#mainmenu-access, .mainmenu-close").on("click",function(a){a.preventDefault(),$("body").toggleClass("mainmenu-active")})});