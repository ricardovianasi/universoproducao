"use strict";$(document).ready(function(){function t(){$("#mouse-bottom").animate({bottom:"0"},400).animate({bottom:"-5px"},800,t),$("#mouse-bottom").on("click",function(t){t.preventDefault(),$(window).scrollTo($("#news"),{duration:200})})}$("#banner-home").owlCarousel({navigation:!0,singleItem:!0,autoHeight:!1,items:1,center:!0,loop:!0,autoplay:!0,animateOut:"fadeOut",mouseDrag:!1,autoplaySpeed:8e3}),$(".channel-slider").owlCarousel({nav:!0,dots:!1,margin:4,navText:['<button><span class="icon icon-arrow-left4"></span></button>','<button><span class="icon icon-arrow-right4"></span></button>'],mouseDrag:!1,responsive:{0:{items:1},320:{items:2},768:{items:3},1024:{items:4},1280:{items:5},1600:{items:6}}}),$(".modal").modal(),t(),$(".channel-item").channelSlide()}),function(t,i){var n=function(t,n){this.element=t,this.$element=i(t),this.options=n};n.prototype={defaults:{},init:function(){this.config=i.extend({},this.defaults,this.options,this.$element.data());var t=this;i(this.config.open).on("click",function(i){i.preventDefault(),t.openModal()}),i(".modal-close").on("click",function(i){i.preventDefault(),t.closeModal()})},openModal:function(){var t=this;i("body").css({top:0,position:"fixed"}),t.$element.css({width:"100%",height:"100%",visibility:"visible",opacity:1})},closeModal:function(){var t=this;i("body").css({position:"relative"}),t.$element.css({width:"0",height:"0",visibility:"hidden",opacity:0})}},n.defaults=n.prototype.defaults,i.fn.modal=function(t){return this.each(function(){new n(this,t).init()})},t.Modal=Plugin}(window,jQuery),function(t,i){var n=function(t,n){this.slide=t,this.$slide=i(t),this.$content=this.$slide.find(".channel-content"),this.$trick=this.$slide.find(".trick"),this.options=n};n.prototype={defaults:{scaling:1.8},init:function(){this.config=i.extend({},this.defaults,this.options,this.$slide.data());var t=this;t.$slide.mouseover(function(){console.log("hover"),t.$slide.addClass("hover")})}},n.defaults=n.prototype.defaults,i.fn.channelSlide=function(t){return this.each(function(){new n(this,t).init()})},t.ChannelSlide=Plugin}(window,jQuery);