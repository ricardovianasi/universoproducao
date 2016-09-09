editor=window.top.tinyMCE.activeEditor;topWindow=window.top;var doksoft_maps_width=editor.getParam("doksoft_maps_width")||400;var doksoft_maps_height=editor.getParam("doksoft_maps_height")||320;var doksoft_maps_default_zoom=editor.getParam("doksoft_maps_default_zoom")||3;var doksoft_maps_default_x=editor.getParam("doksoft_maps_default_x")||35;var doksoft_maps_default_y=editor.getParam("doksoft_maps_default_y")||-93;var doksoft_maps_default_address=editor.getParam("doksoft_maps_default_address");var doksoft_maps_auto_scaling_on_search=editor.getParam("doksoft_maps_auto_scaling_on_search")==undefined?true:editor.getParam("doksoft_maps_auto_scaling_on_search");var weatherInCelsius=!editor.getParam("doksoft_maps_temperature_unit")||editor.getParam("doksoft_maps_temperature_unit")!="fahrenheit";if(!Array.prototype.indexOf){Array.prototype.indexOf=function(a){return jQuery.inArray(a,this);};}function getTemperatureUnit(){return weatherInCelsius?google.maps.weather.TemperatureUnit.CELSIUS:google.maps.weather.TemperatureUnit.FAHRENHEIT;}function tinymce3translate(c){var b="doksoft_maps."+c;var a=top.tinyMCE.activeEditor.translate(b);return b==a?top.tinyMCE.activeEditor.translate(c):a;}function translate(a){return(top.tinyMCE.majorVersion=="4"?top.tinyMCE.translate:tinymce3translate)(a);}layersDialog=(function(){return{selectedOpts:{},open:function(){$("#layers-form").dialog("open");},close:function(){$("#layers-form").dialog("close");},updateLayers:function(){this.trafficLayer.setMap(this.selectedOpts.traffic_layer?this.map:null);this.weatherLayer.setMap(this.selectedOpts.weather_layer?this.map:null);},updateCheckboxes:function(){$("#traffic_layer").attr("checked",!!this.selectedOpts.traffic_layer);$("#weather_layer").attr("checked",!!this.selectedOpts.weather_layer);},init:function(c){var b=this;this.map=c;this.trafficLayer=new google.maps.TrafficLayer();this.weatherLayer=new google.maps.weather.WeatherLayer({temperatureUnits:getTemperatureUnit()});var a={};a[translate("Ok")]=function(){b.selectedOpts.traffic_layer=$("#traffic_layer").is(":checked");b.selectedOpts.weather_layer=$("#weather_layer").is(":checked");b.updateLayers();b.close();};a[translate("Cancel")]=function(){b.close();};$("#layers-form").dialog({autoOpen:false,height:170,width:350,modal:true,buttons:a,open:function(){b.updateCheckboxes();}});}};})();settingsDialog=(function(){return{open:function(){$("#settings-form").dialog("open");},close:function(){$("#settings-form").dialog("close");},updateSettings:function(){var a=this.map;$("#settings-form form input").each(function(){a.set(this.name,$(this).is(":checked"));});},getSettings:function(){var a={},b=this.map;$("#settings-form form input").each(function(){a[this.name]=b.get(this.name);});return a;},setSettings:function(a){var b=this.map;$("#settings-form form input").each(function(){b.set(this.name,a[this.name]);});},updateSettingsCheckboxes:function(){var a=this.map;$("#settings-form form input").each(function(){$(this).attr("checked",a.get(this.name));});},init:function(c){var b=this;this.map=c;var a={};a[translate("Ok")]=function(){b.updateSettings();b.close();};a[translate("Cancel")]=function(){b.close();};$("#settings-form").dialog({autoOpen:false,width:350,modal:true,buttons:a,open:function(){b.updateSettingsCheckboxes();}});}};})();redRectangle=(function(){return{divs:null,updateSpinnersFromParams:function(){$("#map-width").val(doksoft_maps_width);$("#map-height").val(doksoft_maps_height);},updatePositions:function(){var e=$("#map-canvas").offset();var d=$("#map-canvas").outerWidth(),b=$("#map-canvas").outerHeight(),a=Math.round(d/2-doksoft_maps_width/2),c=Math.round(b/2-doksoft_maps_height/2);this.divs.each(function(g){var f=$(this);var h={top:c+"px",left:a+"px"};switch(g){case 0:h.width=doksoft_maps_width+"px";break;case 1:h.height=doksoft_maps_height+"px";break;case 2:h.top=(doksoft_maps_height+c)+"px";h.width=doksoft_maps_width+"px";break;case 3:h.height=doksoft_maps_height+"px";h.left=(doksoft_maps_width+a)+"px";break;}f.css(h);});},initRectDivs:function(){this.divs=$("<div/><div/><div/><div/>");this.divs.each(function(b){var a=$(this);var c={position:"absolute",zIndex:99,border:"1px solid #BB1111"};if(b%2){c=$.extend(c,{width:"1px",height:doksoft_maps_height+"px",borderWidth:"0px 0px 0px 1px"});}else{c=$.extend(c,{height:"1px",width:doksoft_maps_width+"px",borderWidth:"1px 0px 0px 0px"});}a.css(c);}).appendTo($("#map-canvas"));},initSpinners:function(){var a=this;var b=function(){doksoft_maps_width=+$("#map-width").val();doksoft_maps_height=+$("#map-height").val();a.updatePositions();};$("#map-width").spinner({min:10,max:screen.width,change:b,stop:b});$("#map-height").spinner({min:10,max:screen.height,change:b,stop:b});$("#map-width").val(doksoft_maps_width);$("#map-height").val(doksoft_maps_height);},init:function(){this.initRectDivs();this.initSpinners();var a=this;setTimeout(function(){a.updatePositions();},0);}};})();insertButtons=(function(){return{objects:{Marker:[],Circle:[],Polyline:[],Text:[],Polygon:[],Rectangle:[],TrafficLayer:[],WeatherLayer:[]},mode:null,deleteCurrentMarker:function(a){this.currentMarker&&this.currentMarker.setMap(null);
delete this.objects.Marker[this.objects.Marker.indexOf(this.currentMarker)];if(typeof(a)=="undefined"){this.hideColorChooser();}return false;},okCurrentMarker:function(){this.currentMarker&&this.currentMarker.setTitle(document.getElementById("area1").value);this.infoWindow&&this.infoWindow.close();this.hideColorChooser();return false;},cancelCurrentMarker:function(){this.infoWindow&&this.infoWindow.close();this.hideColorChooser();return false;},insertMarker:function(a,f,j,k,i){var b=this;var h={"red":"FF0000","yellow":"FFFF00","green":"008000","blue":"0000FF","black":"000000","white":"FFFFFF"};if(!i){i="FF0000";}else{i=h[i]||i.replace("#","");}var g=new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|"+i,new google.maps.Size(21,34),new google.maps.Point(0,0),new google.maps.Point(10,34));var c=new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",new google.maps.Size(40,37),new google.maps.Point(0,0),new google.maps.Point(12,35));var e=new google.maps.Marker({position:f,map:a,draggable:true,icon:g,shadow:c,title:j});var d=false;var l=function(){var o='<div style="width:250px;height:150px;overflow:hidden;">'+'<div id="content" style="position:relative; z-index:999;overflow:hidden;">'+"<label>"+translate("Text")+'</label><div style="margin:5px;">'+'<textarea id="area1" rows="5" style="width:99%;resize: none;">{text}</textarea>'+"</div>"+"<div>"+'<input onclick="return insertButtons.deleteCurrentMarker()" style="float:left;" type="button" value="'+translate("Delete")+'"/>'+'<input onclick="return insertButtons.okCurrentMarker()" style="float:right;" type="button" value="'+translate("OK")+'"/>'+'<input onclick="return insertButtons.cancelCurrentMarker()" style="float:right;" type="button" value="'+translate("Cancel")+'"/>'+'<div style="clear:both;"></div>'+"</div>"+"</div>";var n=this;b.currentObject=b.currentMarker=n;var m=n.getPosition();b.infoWindow.setContent(o.replace("{text}",this.getTitle()));b.infoWindow.open(a,this);if(d){b.showColorChooser(n.color);}};google.maps.event.addListener(e,"click",l);k&&l.apply(e);d=true;return e;},insertText:function(a,f,e,g,i,c){if(!c){c="red";}var b=this;var d=new MarkerWithLabel({position:e,draggable:true,raiseOnDrag:true,map:a,labelContent:f,labelAnchor:new google.maps.Point(22,0),labelClass:"labels",labelStyle:{opacity:1,minWidth:"200px",fontSize:i,color:c,textAlign:"left"},});var h=function(){var k='<div id="content" style="position:relative; z-index:999;width:400px;heigth:400px;overflow:hidden;">'+'<label>Text</label><div style="margin:5px;">'+'<textarea id="area2" rows="5" style="width:99%;resize: none;">{text}</textarea>'+"</div>"+"<div>"+'<input onclick="return insertButtons.deleteCurrentText();" style="float:left;" type="button" value="'+translate("Delete")+'"/>'+'<input onclick="return insertButtons.okCurrentText();" style="float:right;" type="button" value="'+translate("OK")+'"/>'+'<input onclick="return insertButtons.cancelCurrentText();" style="float:right;" type="button" value="'+translate("Cancel")+'"/>'+'<div style="clear:both;"></div>'+"</div>"+"</div>";b.currentMarker=this;var j=this.getPosition();b.infoWindow.setContent(k.replace("{text}",this.get("labelContent")));b.infoWindow.open(a,this);b.showFontChooser(parseInt(this.labelStyle.fontSize));};google.maps.event.addListener(d,"click",h);g&&h.apply(d);return d;},insertCircle:function(d,b,a,c){return new google.maps.Circle({radius:a,center:b,map:d,editable:true,draggable:true,strokeColor:c||"#ff0000"});},insertRectangle:function(c,b,a){return new google.maps.Rectangle({bounds:b,map:c,editable:true,draggable:true,strokeColor:a||"#ff0000"});},insertPolyFigure:function(d,c,b,a){return new google.maps[d]({path:b,map:c,editable:true,draggable:true,strokeColor:a||"#ff0000"});},insertPolygon:function(c,b,a){return this.insertPolyFigure("Polygon",c,b,a);},insertPolyline:function(c,b,a){return this.insertPolyFigure("Polyline",c,b,a);},finishInsertingShape:function(){this.clearActiveButtons();$("#finish_path").hide();$("#delete_path").hide();this.hideColorChooser();this.hideFontChooser();this.mode=null;this.map.setOptions({draggableCursor:"move"});},cancelInsertingShape:function(){this.deleteObject(this.currentObject.type,insertButtons.objects[this.currentObject.type].indexOf(this.currentObject));this.finishInsertingShape();},deleteCurrentText:function(){this.currentMarker&&this.currentMarker.setMap(null);delete this.objects.Text[this.objects.Text.indexOf(this.currentMarker)];this.hideFontChooser();this.hideColorChooser();return false;},okCurrentText:function(){this.currentMarker&&this.currentMarker.set("labelContent",$("#area2").val());this.infoWindow&&this.infoWindow.close();this.hideFontChooser();this.hideColorChooser();return false;},cancelCurrentText:function(){this.infoWindow&&this.infoWindow.close();this.hideFontChooser();this.hideColorChooser();return false;},deleteObject:function(b,a){a=isNaN(a)?0:a;if(!this.objects[b].length){return 0;
}this.objects[b][a].setMap(null);delete this.objects[b][a];this.objects[b].splice(a,1);},clearActiveButtons:function(){$("button.object").removeClass("active");},changeMarkerFont:function(a){var c=this.currentMarker||this.currentObject;var b=c.labelStyle;b.fontSize=a+"px";c.set("labelStyle",b);},showFontChooser:function(a){$("#fontChooser").show();if(!a){a=24;}this.changeMarkerFont(a);$(".font-item").removeClass("active-font-item");$(".font-item:contains("+a+")").addClass("active-font-item");},hideFontChooser:function(){$("#fontChooser").hide();},changeObjColor:function(b){var c=this.currentObject;if(this.currentObject.constructor==MarkerWithLabel){var a=this.currentObject.labelStyle;a.color=b;this.currentObject.set("labelStyle",a);}else{if(this.currentObject.icon){insertButtons.deleteCurrentMarker(true);this.currentObject=this.insertMarkerX(this.map,this.currentObject.position,this.currentObject.getTitle(),true,b);}else{this.currentObject.set("strokeColor",b);}}this.currentObject.color=b;},showColorChooser:function(a){$("#colorChooser").show();if(!a){a="red";}this.changeObjColor(a);$(".color-item").removeClass("active-color-item");$(".color-item.with-color-"+a.replace("#","\\#")).addClass("active-color-item");},hideColorChooser:function(){$("#colorChooser").hide();},initColorButtons:function(){var a=this;$(".color-item").click(function(){var b=$(this).attr("class").match(/with-color-(.*)/);a.showColorChooser(b[1]);});},initFontButtons:function(){var a=this;$(".font-item").click(function(){a.showFontChooser($(this).html());});},addClickListenerForObj:function(a,b){google.maps.event.addListener(a,"click",function(){b(this,true);});},generateSelectObject:function(b){var a=this;return function(d,c){a.hideColorChooser();a.hideFontChooser();a.currentObject=d;if(b=="Text"){a.showFontChooser(parseInt(d.labelStyle.fontSize));}if(b!="Text"&&b!="Marker"){a.infoWindow.close();}a.showColorChooser(d.color);if(c&&b!="Text"&&b!="Marker"){$("#delete_path").show();}};},init:function(c){this.map=c;this.currentMarker=0;this.infoWindow=new google.maps.InfoWindow;this.objects={Marker:[],Circle:[],Polyline:[],Text:[],Polygon:[],Rectangle:[],TrafficLayer:[],WeatherLayer:[]};var b=this;$.each(["Circle","Marker","Text","Polygon","Polyline","Rectangle"],function(d,e){b["insert"+e+"X"]=function(){var g=this["insert"+e].apply(b,arguments);b.objects[e].push(g);var h=b.generateSelectObject(e);b.addClickListenerForObj(g,h);var f={Marker:4,Text:5,Rectangle:2,Circle:3,Polygon:2,Polyline:2};if(arguments[f[e]]){g.color=arguments[f[e]];}g.type=e;return g;};});var a=$("button.object");a.click(function(){var d=!$(this).hasClass("active");b.clearActiveButtons();b.mode=d?$(this).val():"";if(!d){$(this).removeClass("active");}else{$(this).addClass("active");}c.setOptions({draggableCursor:d?"crosshair":"move"});$("#finish_path").hide();b.hideFontChooser();b.hideColorChooser();});clickAddObject=function(d){var k=b.mode;var j=d.latLng||c.getCenter(),l=Math.pow(2,c.getZoom()),f=c.getProjection().fromLatLngToPoint(j),h=50,i=null,g=c.getProjection().fromPointToLatLng(new google.maps.Point(f.x-(h/2)/l,f.y-(h/2)/l)),e=c.getProjection().fromPointToLatLng(new google.maps.Point(f.x+(h/2)/l,f.y+(h/2)/l));switch(k){case"Marker":i=b.insertMarkerX(c,j,"Hello World!",true);break;case"Rectangle":i=b.insertRectangleX(c,new google.maps.LatLngBounds(g,e));break;case"Polyline":i=b.insertPolylineX(c,[j]);break;case"Polygon":i=b.insertPolygonX(c,[j]);break;case"addpointtocoord":if(b.currentObject instanceof google.maps.Polyline||b.currentObject instanceof google.maps.Polygon){var n=b.currentObject.getPath();n.push(j);b.currentObject.setPath(n);}else{b.mode="";}break;case"Text":i=b.insertTextX(c,"Hello World!",j,true,"24px");break;case"Circle":i=b.insertCircleX(c,j,google.maps.geometry.spherical.computeDistanceBetween(g,e));break;case"WeatherLayer":deleteObject(k);i=new google.maps.weather.WeatherLayer({temperatureUnits:getTemperatureUnit()});i.setMap(c);break;case"TrafficLayer":deleteObject(k);i=new google.maps.TrafficLayer();i.setMap(c);break;}if(i&&k!="addpointtocoord"){var m=b.generateSelectObject(k);b.addClickListenerForObj(i,m);i.type=k;m(i);if(k!="Polyline"&&k!="Polygon"){$(".object").removeClass("active");c.setOptions({draggableCursor:"move"});b.mode="";}else{b.mode="addpointtocoord";$("#finish_path").show();}}};google.maps.event.addListener(c,"click",clickAddObject);this.initFontButtons();this.initColorButtons();}};})();map=(function(){function c(d){var f=this.map;var e=this;(new google.maps.Geocoder()).geocode({"address":d},function(h,g){if(g==google.maps.GeocoderStatus.OK){e.setCenterWithAutoScaling(h[0]);doksoft_maps_default_x=h[0].geometry.location.lat();doksoft_maps_default_y=h[0].geometry.location.lng();}});}var b;function a(){var f=document.getElementById("codeContainer").innerHTML.split("\n");var e=f.slice(1,f.length-1).join("\n");b=e.replace(/\s+/g," ");var d=new google.maps.LatLng(doksoft_maps_default_x,doksoft_maps_default_y);var g={zoom:doksoft_maps_default_zoom,center:d,disableDefaultUI:true,disableDoubleClickZoom:false,draggable:true,mapTypeControl:true,zoomControl:true,rotateControl:false,scaleControl:true,streetViewControl:false,panControl:false,overviewMapControl:false,draggableCursor:"move",mapTypeId:google.maps.MapTypeId.ROADMAP};
var j=this.map=new google.maps.Map($("#map-canvas")[0],g);if(doksoft_maps_default_address&&!window.top.dataForLoadIntoMap){this.tryToGeoCodeAddress(doksoft_maps_default_address);}google.maps.event.addDomListener(j,"maptypeid_changed",function(){var l=j.getMapTypeId();$("#maptype_img").attr("src","images/"+l+".png");$("#maptype_title").html((translate(l.charAt(0).toUpperCase()+l.slice(1))));});this.map.setMapTypeId("roadmap");function k(){function l(){j.setZoom(+$(this).val());}google.maps.event.addDomListener(j,"zoom_changed",function(){$("#map-zoom").val(j.getZoom());});$("#map-zoom").spinner({min:0,change:l,stop:l,max:20});$("#map-zoom").val(doksoft_maps_default_zoom);}function i(){var m=new google.maps.places.SearchBox($("#target")[0]);m.bindTo("bounds",j);var l=this;google.maps.event.addListener(m,"places_changed",function(){var n=m.getPlaces()[0];l.map.setCenterWithAutoScaling(n);});}var h=/msie/.test(navigator.userAgent.toLowerCase());if(h){$("#dialog_box").addClass("ie");$("#dialog_box .cell").not(".setting").css("width",($(window).width()-120)+"px").css("float","left");$("#dialog_box").parent().addClass("tinymce-"+top.tinyMCE.majorVersion);}k();i();}return{map:null,init:a,tryToGeoCodeAddress:c,setCenterWithAutoScaling:function(d){if(!doksoft_maps_auto_scaling_on_search){this.map.setCenter(d.geometry.location);return true;}else{if(d.geometry.viewport){this.map.fitBounds(d.geometry.viewport);}else{this.map.setZoom(17);this.map.setCenter(d.geometry.location);}}},getNewMapId:function(){for(var d=1;d<100;d++){var e="doksoft_map_"+d;if(!editor.dom.doc.getElementById(e)){return e;}}},getHtmlCode:function(){return b.replace(/{{map-id}}/g,this.getNewMapId()).replace("{{map-json}}",$.toJSON(this.generateCodeMap()));},generateCodeMap:function(){var e=this.map;var j=insertButtons.objects;var n={lat:e.getCenter().lat(),lng:e.getCenter().lng(),zoom:e.getZoom(),type:e.getMapTypeId(),width:doksoft_maps_width,height:doksoft_maps_height,settings:{},objects:{Marker:[],Circle:[],Polyline:[],Text:[],Polygon:[],Rectangle:[],TrafficLayer:[],WeatherLayer:[]}};$.each(layersDialog.selectedOpts,function(o,p){n.settings[o]=p;});$.each(settingsDialog.getSettings(),function(o,p){n.settings[o]=p;});for(var f in j){for(var k in j[f]){if(!j[f][k]||typeof(j[f][k])=="function"){continue;}var g=j[f][k];switch(f){case"Marker":n.objects[f].push([j[f][k].getPosition().lat(),j[f][k].getPosition().lng(),j[f][k].getTitle(),j[f][k].color]);break;case"Circle":n.objects[f].push([g.get("strokeColor"),j[f][k].getCenter().lat(),j[f][k].getCenter().lng(),j[f][k].getRadius()]);break;case"Text":n.objects[f].push([g.getPosition().lat(),g.getPosition().lng(),g.get("labelContent"),g.get("labelStyle").fontSize,g.color]);break;case"Rectangle":n.objects[f].push([g.get("strokeColor"),[g.getBounds().getSouthWest().lat(),g.getBounds().getSouthWest().lng()],[g.getBounds().getNorthEast().lat(),g.getBounds().getNorthEast().lng()]]);break;case"Polygon":case"Polyline":var h=g.getPath().getArray(),m=[g.get("strokeColor")];for(var l in h){m.push([h[l].lat(),h[l].lng()]);}n.objects[f].push(m);break;case"Polygon":var h=g.getPath().getArray(),m=[g.get("strokeColor")],i;for(var l in h){i=[];var i=h[l].getArray();for(var d in i){q.push([i[d].lat(),i[d].lng()]);}m.push(q);}n.objects[f].push(m);break;case"TrafficLayer":case"WeatherLayer":n.objects[f].push(1);break;}}}return n;},loadMapFromJSON:function(l){var e=this.map;l.width&&(doksoft_maps_width=l.width);l.height&&(doksoft_maps_height=l.height);weatherInCelsius=l.weather_in_celsius;redRectangle.updateSpinnersFromParams();redRectangle.updatePositions();var d=new google.maps.LatLng(parseFloat(l.lat),parseFloat(l.lng));e.setCenter(d);e.setZoom(parseInt(l.zoom));e.setMapTypeId(l.type);$.each(["traffic_layer","weather_layer"],function(i,n){layersDialog.selectedOpts[n]=(n in l.settings)?l.settings[n]:false;});layersDialog.updateLayers();settingsDialog.setSettings(l.settings);if(l.objects){for(var f in l.objects){for(var k in l.objects[f]){var d=new google.maps.LatLng(0,0);var j=l.objects[f][k];switch(f){case"Marker":d=new google.maps.LatLng(l.objects[f][k][0],l.objects[f][k][1]);insertButtons.insertMarkerX(e,d,l.objects[f][k][2],false,l.objects[f][k][3]);break;case"Circle":d=new google.maps.LatLng(l.objects[f][k][1],l.objects[f][k][2]);insertButtons.insertCircleX(e,d,l.objects[f][k][3],l.objects[f][k][0]);break;case"Text":d=new google.maps.LatLng(j[0],j[1]);insertButtons.insertTextX(e,j[2],d,false,j[3],l.objects[f][k][4]);break;case"Rectangle":var g=new google.maps.LatLngBounds(new google.maps.LatLng(l.objects[f][k][1][0],l.objects[f][k][1][1]),new google.maps.LatLng(l.objects[f][k][2][0],l.objects[f][k][2][1]));insertButtons.insertRectangleX(e,g,l.objects[f][k][0]);break;case"Polygon":case"Polyline":var m=[];for(var h=1;h<j.length;h++){m.push(new google.maps.LatLng(j[h][0],j[h][1]));}if(f=="Polygon"){insertButtons.insertPolygonX(e,m,j[0]);}else{insertButtons.insertPolylineX(e,m,j[0]);}break;}}}}this.mode=l.type;},doNextMapType:function(){var f=["roadmap","satellite","terrain","hybrid"],d=f.indexOf(this.map.getMapTypeId()),e=(d+1)%f.length;
this.map.setMapTypeId(f[e]);}};})();function updateMapHeight(){var e=window.innerHeight||document.documentElement.clientHeight;document.getElementById("map-canvas").style.height=(e-55)+"px";if(navigator.userAgent.match(/MSIE/)){function b(){var g=top.tinyMCE.DOM.select("iframe",top.document);for(var f=0;f<g.length;f++){if(g[f].src.match(/popup/)){return g[f];}}}var d=b();var c=d.clientHeight;if(top.tinyMCE.majorVersion=="4"){document.getElementById("map-canvas").style.width=(b().clientWidth-120)+"px";document.getElementById("map-search-field").style.width=(b().clientWidth-117)+"px";}else{var a=top.tinyMCE.DOM.select("iframe",b().contentWindow.document)[0];document.getElementById("map-canvas").style.width=(a.clientWidth-120)+"px";document.getElementById("map-search-field").style.width=(a.clientWidth-117)+"px";a.style.height=(c-55)+"px";}}}$(function(){map.init();layersDialog.init(map.map);settingsDialog.init(map.map);redRectangle.init();insertButtons.init(map.map);if(window.top.dataForLoadIntoMap){map.loadMapFromJSON(window.top.dataForLoadIntoMap);}updateMapHeight();});