"use strict";$(document).ready(function(){$("body").on("click","a[href='']",function(a){a.preventDefault()}),$.stellar({responsive:!0}),$(".gallery__list").owlCarousel({items:1,nav:!0,navText:["<button class='gallery__arrow gallery__arrow--left'><span class='icon icon-arrow-left'></span></button>","<button class='gallery__arrow gallery__arrow--right'><span class='icon icon-arrow-right'></span></button>"],dots:!1,mouseDrag:!1}),$(".banner").sullivanBanner({items:".banner__item"}),$(".place").place({items:".place__item"}),$(document).on("click","#mainmenu-access",function(a){a.preventDefault(),$("body").toggleClass("mainmenu-active")})}),function(a){a.fn.sullivanBanner=function(t){t=a.extend({},t);var e=a(this),n=e.find(".banner__covers"),r=e.find(t.items),i=0;return e.on("mouseleave",function(){r.attr("data-state","none"),n.find(".banner__cover").attr("data-state","none")}),r.each(function(){var t=a(this);if(!t.hasClass("ignore")){var e=t.find(".banner__content").clone();n.append(a("<figure class='banner__cover'>").css({"background-image":t.css("background-image")}).attr("data-index",i).attr("data-state","none").append(e.removeClass("banner__content").addClass("banner__content-cover"))),t.on("mouseenter",function(t){var e=a(t.target),i=e.attr("data-index");r.attr("data-state","hidden"),e.attr("data-state","active"),n.find("[data-index='"+i+"']").attr("data-state","active")})}t.attr("data-index",i++).attr("data-state","none"),t.on("mouseleave",function(a){r.attr("data-state","none"),n.find(".banner__cover").attr("data-state","none")})})},a.fn.place=function(t){t=a.extend({},t);var e=a(this);return e.find(t.items).each(function(){var n=a(this);n.on("click",function(n){n.preventDefault();var r=a(this);e.find(t.items).removeClass("place__item--active"),r.addClass("place__item--active"),e.find(".place__desc-title").text(r.attr("data-title")),e.find(".place__desc-text").text(r.attr("data-desc")),e.find(".place__image").fadeOut(400,function(){e.find(".place__image").attr("src",r.attr("data-image"))}).fadeIn(400)})})}}(jQuery);