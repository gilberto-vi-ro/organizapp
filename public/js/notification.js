/*!
 * trash.js v1.0.0
 * since 18/12/2020
 * Copyright 2020 Gilberto Villarreal Rodriguez <Gil_yeung@outlook.com>
 * Licensed open source
 */

$(function(){
	dateRange();
	listNotification();

	var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)')||0)[2]; //generate cookie
	
	let dataItem = [];//array contenedor de item selected
	let this_data = null;//contiene info del item selected
	var $div_list = $('#list');
	//$(window).on('hashchange',list).trigger('hashchange');
	//listNotification();  //list items trash

	/*====================================================
	EVENTS CHANGE
	====================================================*/

	$("#date_range1").on('change',function( event ){  //change range date on #date_range1 
		
		listNotification();
	});

	$("#date_range2").on('change',function( event ){  //change range date on #date_range2 
		
		listNotification();
	});

	/*====================================================
	EVENTS Click
	====================================================*/
	$("#menu_delete").on('click',function( event ){ //borrar item al dar click en  #menu_delete
		if (dataItem.length == 0 ) 
			{ alert("Selecciona un o varios Items"); return false; }
		if (dataItem.length == 1) 
			var opcion = confirm("Esta seguro que deseas eliminar a "+ dataItem[0].tarea_nombre +"?");
		if (dataItem.length > 1) 
			var opcion = confirm("Esta seguro que deseas eliminar los archivos seleccionados?");
		if (!opcion) return;
		$.get("notification?deleteNotification=1&dataItem="+ JSON.stringify(dataItem)  ,'json').done(function(response){
				console.log(response);
				listNotification();
		});
		
		return false;
	});

	$(".info-item-button-right").on('click',function( event ){ //cerrar modal al dar click en  .info-item-button-right
		hide("#info_item");
	});

	/*====================================================
	ITEMS CLICK, DBLCLICK, CONTEXTMENU
	====================================================*/
	var timer = null; 
	$(document).delegate(".my-item" ,'click',function( event ){  // click or dblclick on .my-item
		let _this = this;
		this_data = $(this.querySelector("input")).attr('data-this');
		this_data = JSON.parse(b64_to_utf8(this_data));
		
		if (event.detail == 2) {  clearTimeout(timer); // Manejo de eventos dblclick
			$.get('notification?seenNotification=1&idNotification='+ this_data.id_notificacion  ,'json').done(function(data) {
				//console.log(data);
				window.location.href = $(_this).attr('href');
			});
			return ; // end dblclick
		}
		timer = setTimeout(function() { // Manejo de eventos de un click
			itemSelected(_this);
			//console.log(this_data);
		}, 300); // end click
		
	});

	$(document).delegate(".my-item" ,'contextmenu',function( event ){  // click or dblclick on .my-item
		this_data = $(this.querySelector("input")).attr('data-this');
		this_data = JSON.parse(b64_to_utf8(this_data));

		$.get('notification?seenNotification=1&idNotification='+ this_data.id_notificacion  ,'json').done(function(data) {
				//console.log(data);
		});
		info_item_msg.innerHTML =(`<i class="fas fa-bell item"></i>${this_data.mensaje_mensaje}.`);
		info_item_name_task.innerHTML =(`${this_data.tarea_nombre}`);
		info_item_url.innerHTML =(`${this_data.carpeta_path_name}`);
		info_item_url.href =(BASE_URL+"home#"+`${this_data.carpeta_path_name}`);
		info_item_status.innerHTML =(`${this_data.tarea_estado==1?"pendiente":this_data.tarea_estado==2?"listo":"entregado"}`);	
		info_item_priority.innerHTML =(`${this_data.tarea_prioridad==1?"urgente":this_data.tarea_prioridad==2?"importante":"no urgente"}`);	
		info_item_date_notification.innerHTML =(`${this_data.notificacion_fecha_registro}`);							
								
		show("#info_item", "flex");
		this.removeAttribute("style");
		return false;
	});

	function itemSelected(_this){
		let selected = _this.querySelector(".selected");
		let id_notificacion = this_data.id_notificacion;
		if ( selected.classList.contains("hidde") ) {
			$(selected).removeClass("hidde");
			$(selected).addClass("show");
			$(selected).show();
			dataItem.push( this_data );//agregar datos al array dataItem
		}else{
			$(selected).removeClass("show");
			$(selected).addClass("hidde");
			$(selected).hide();
			
			dataItem.forEach(function(array, index, object) {// eliminar array dataItem por valor
			    if(array.id_notificacion === id_notificacion){
			      object.splice(index, 1);
			    }
			});
		}
   		console.log(dataItem);
   		//console.log("mousedown");
	}


	/*====================================================
	LIST NOTIFICATION
	====================================================*/

	function listNotification() {
		
		range = date_range1.value+"::"+ date_range2.value;
		$.get('notification?listNotification=1&range='+ range ,'json').done(function(data) {
			//console.log(data); return;
			data = JSON.parse(data); // convertimos el resultado a formato JSON
			
			$div_list.empty();

			if(data.success ) {
			
				$.each(data.results,function(k,v){ //recorremos los resultados
						$div_list.append(renderFolderRow(k,v));
				});
				
				!data.results.length && $div_list.append('<tr><td class="empty" colspan=5>Esto está vacío</td></tr>')
				
			} else {
				console.warn(data.results);
			}
		});
	}

	function renderFolderRow(i , data) {
		let style = data.notificacion_visto==0?'style="background-color: rgba(59, 134, 232 ,0.8); "':"";
		var $html =
			`<a href="${BASE_URL}home#${data.carpeta_path_name}" class="my-item" type="text" ${style} onClick="return false;" >
					<div class="selected hidde" ><i class="fas fa-check"></i></div>
					<input type="hidden" value="${i}" data-this="${utf8_to_b64(JSON.stringify(data))}">
					
					<p class="item-name item"><i class="fas fa-bell item"></i>
						${data.mensaje_mensaje}, <span> nombre: </span> ${data.tarea_nombre},
						<span>ruta: </span>${data.carpeta_path_name}
					</p>
					<p class="item-name item"><span>${data.notificacion_fecha_registro}</span></p>
				</a>
			 `;
                   
		return $html;
	}

	function dateRange(){

		var date1 = new Date(); //now
		var date2 = new Date(); //now
		date1.setHours(0,0,0,0);
		date2.setHours(0,0,0,0);
		date1.setDate(date1.getDate() - 15 );
		date2.setDate(date2.getDate() + 15 );
		
		date_range1.value = dateFormat(date1,"yyyy-mm-dd");
		date_range2.value = dateFormat(date2,"yyyy-mm-dd");
	}

});