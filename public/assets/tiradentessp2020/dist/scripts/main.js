"use strict";!function(e){e.fn.customerPopup=function(e,a,t,o){e.preventDefault(),a=a||"500",t=t||"400"}}(jQuery),$(document).ready(function(){$("body").on("click","a[href='']",function(e){e.preventDefault()}),$(".banner-items").owlCarousel({items:1,nav:!1,navText:["<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>","<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"],dots:!0,mouseDrag:!1,lazyLoad:!1,loop:!1,center:!0,autoplay:!0,autoplayTimeout:7e3,animateOut:"fadeOut"}),$(".movie-carousel").owlCarousel({items:1,nav:!1,video:!0,dots:!0,mouseDrag:!1,lazyLoad:!1,loop:!0,center:!0,autoplay:!0,autoplayTimeout:7e3,animateOut:"fadeOut"}),$(".gallery-list").imagesLoaded(function(){$(".gallery-list").owlCarousel({responsiveClass:!0,responsive:{0:{items:1},728:{items:2},1024:{items:3},1900:{items:4}},autoWidth:!0,autoHeight:!0,nav:!0,margin:1,navText:["<button class='owl-prev'><span class='icon icon-arrow-left4'></span></button>","<button class='owl-next'><span class='icon icon-arrow-right4'></span></button>"],dots:!1,mouseDrag:!1,loop:!0,center:!0,autoplay:!1,autoplayTimeout:7e3,lazyLoad:!0})});var e=$(".events").owlCarousel({items:3,center:!1,autoWidth:!0,autoHeight:!0,nav:!0,navContainer:".timeline-navigation",navText:["<a href='' class='circle-button'><i class='icon icon-arrow-left4'></i></a>","<a href='' class='circle-button'><i class='icon icon-arrow-right4'></i></a>"],dots:!1,mouseDrag:!0,autoplay:!1,lazyLoad:!1,loop:!1,stageElement:"ol",itemElement:"li"});e.on("translate.owl.carousel",function(e){0==e.item.index?$(".timeline").removeClass("stage2").addClass("active stage1"):e.item.index>0&&$(".timeline").removeClass("stage1").addClass("active stage2")}),$("#timeline-active").on("click",function(e){e.preventDefault(),$(".timeline").toggleClass("active stage1")}),$(".fancybox").fancybox({padding:0,openEffect:"elastic",maxWidth:700,maxHeight:700,width:"100%",height:"100%",helpers:{overlay:{locked:!1},title:{type:"outside"}}}),$("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif'],a.winners").fancybox({padding:0,openEffect:"elastic",helpers:{overlay:{locked:!1}}}),$("#menu-button").on("click",function(e){e.preventDefault(),$(".menu").toggleClass("menu-open")})});