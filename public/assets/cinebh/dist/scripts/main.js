"use strict";function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}!function(e){e.fn.customerPopup=function(e,t,a,o){e.preventDefault(),t=t||"500",a=a||"400";var r=o?"yes":"no",n="undefined"!=typeof this.attr("title")?this.attr("title"):"Social Share",l="width="+t+",height="+a+",resizable="+r;window.open(this.attr("href"),n,l).focus()}}(jQuery),$(document).ready(function(){$("body").on("click","a[href='']",function(e){e.preventDefault()}),$(".banner__items").owlCarousel({items:1,nav:!1,navText:["<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>","<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"],dots:!0,mouseDrag:!1,lazyLoad:!1,loop:!0,center:!0,autoplay:!0,autoplayTimeout:7e3,animateOut:"fadeOut"}),$(".gallery__list").owlCarousel(_defineProperty({items:1,autoWidth:!0,nav:!0,navText:["<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left4'></span></button>","<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right4'></span></button>"],dots:!1,mouseDrag:!1,loop:!0,center:!0,autoplay:!1,autoplayTimeout:7e3,lazyLoad:!1},"loop",!0)),$("#mainmenu-access, .mainmenu-close").on("click",function(e){e.preventDefault(),$("body").toggleClass("mainmenu-active")}),$(".customer-share").on("click",function(e){$(this).customerPopup(e)}),$(".mainmenu-bgc").perfectScrollbar()});