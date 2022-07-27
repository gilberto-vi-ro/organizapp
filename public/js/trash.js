

$(function(){

	var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)')||0)[2]; //generate cookie
	
	FileManager_values = JSON.parse(FileManager_values); // values of class FileManager.php
	var MAX_UPLOAD_SIZE = FileManager_values.value.MAX_UPLOAD_SIZE;
	let dataItem = [];//array contenedor de item selected
	let this_data = null;//contiene info del item selected
	var $div_list = $('#list');
	//$(window).on('hashchange',list).trigger('hashchange');
	listTrash();  //list items trash

	/*====================================================
	EVENTS CLICK
	====================================================*/

	$(document).delegate("#breadcrumb div a" ,'click',function(e){//listar al dar click en  #breadcrumb
		var pathname = $(this).attr('href');
		pathname = pathname.substr(1);
		//console.log(pathname);
		listTrash(pathname);
	});

	$(".info-item-button-right").on('click',function( event ){ //cerrar modal al dar click en  .info-item-button-right
		hide("#info_item");
		
	});

	$("#search").on('keyup',function(e){//listar search al dar click en  #search
		let pathname = $( "#list" ).attr("data-pathname");
		let search = $(this).val();
		pathname = pathname.replace( new RegExp("/","g") ,"%2F");
			window.location.hash = pathname; 
		if (search ==null || search =="" )
			listTrash(pathname);
		else
			listSearch(pathname,search);
	});
	

	$("#btn_rename_folder").on('click',function( event ){ //renombrar Item al dar click en  #btn_rename_folder
		// let pathname = $( "#list" ).attr("data-pathname");
		// let oldname = dataItem[0].name;
		// let newname = $( "#input_rename_folder" ).val();
	
		// $.post("trash",{'rename':'1',"pathname":pathname,"oldname": oldname, "newname": newname }
		// 	,'json').done(function(response){
		// 		listTrash();
		// });
		// hide("#rename_folder");
		// $( "#input_rename_folder" ).val("name folder");
		return false;
	});

	$("#menu_delete_trash").on('click',function( event ){ //eliminar Items al dar click en  #menu_delete
		if (dataItem.length == 0 ) 
			{ alert("Selecciona un o varios Items"); return false; }
		//let pathname = $( "#list" ).attr("data-pathname");
		//console.log(dataItem);
		if (dataItem.length == 1) 
			var opcion = confirm("Esta seguro que deseas eliminar a "+ dataItem[0].name +"?");
		if (dataItem.length > 1) 
			var opcion = confirm("Esta seguro que deseas eliminar los archivos seleccionados?");
		if (!opcion) return;

		$.post("trash",{'deleteTrash':'1',"item": dataItem  }
			,'json').done(function(response){
				listTrash();
				console.log(response);
		});
		
		return false;

	});

	$("#menu_restore_trash").on('click',function( event ){ //restaurar Items al dar click en  #menu_restore
		if (dataItem.length == 0 ) 
			{ alert("Selecciona un o varios Items"); return false; }
		//let pathname = $( "#list" ).attr("data-pathname");
		//console.log(dataItem);

		$.post("trash",{'restoreTrash':'1',"item": dataItem  }
			,'json').done(function(response){
				listTrash();
				console.log(response);
		});
		
		return false;

	});



	/*====================================================
	ITEMS CLICK, DBLCLICK, CONTEXTMENU
	====================================================*/
	var timer = null; 
	$(document).delegate(".my-item" ,'click',function( event ){  // click or dblclick
		let _this = this;
		this_data = $(_this.querySelector("input")).attr('data-this');
    	this_data = JSON.parse(b64_to_utf8(this_data));
		

		if (event.detail == 2) {  clearTimeout(timer); // Manejo de eventos dblclick
			
			return ; // end dblclick
		}
		timer = setTimeout(function() { // Manejo de eventos de un click

    		itemSelected(_this);
		}, 30); // end click
		
	});

	$(document).delegate(".my-item" ,'contextmenu',function( e){ //mostrar info del item on contextmenu en .my-item
    	
    	this_data = $(this.querySelector("input")).attr('data-this');
		this_data = JSON.parse(b64_to_utf8(this_data));
		//console.log(this_data);
		getInfoItem();
		show("#info_item","flex");
    	//document.oncontextmenu = function(){return false;}
    	// $(".my-item").bind("contextmenu", function () {return false;});
    	return false;//no mostrar contextmenu default
	});


	function itemSelected(_this){
		let selected = _this.querySelector(".selected");
		let path_name = this_data.path_name;
		if ( selected.classList.contains("hidde") ) {
			$(selected).removeClass("hidde");
			$(selected).addClass("show");
			$(selected).show();
			dataItem.push( this_data );
		}else{
			$(selected).removeClass("show");
			$(selected).addClass("hidde");
			$(selected).hide();
			// eliminar array dataItem por valor
			dataItem.forEach(function(array, index, object) {
			    if(array.path_name === path_name){
			      object.splice(index, 1);
			    }
			});
		}
		
   		//console.log(dataItem);
   		//console.log("mousedown");
	}

	/*====================================================
	LIST
	====================================================*/
	function listTrash( pathname = false) {
		dataItem = [];
		var setPathname = pathname ? pathname : window.location.hash.substr(1);

		$.get('trash?list=all&pathname='+ setPathname,'json').done(function(data) {
			data = JSON.parse(data); // convertimos el resultado a formato JSON
			//console.log(data);
			$div_list.empty();

			if(setPathname){	
				//generar el Breadcrumbs
				$('#breadcrumb').empty().html(renderBreadcrumbs(setPathname));
				//elimar el primer nodo
				$('#breadcrumb div span').first().remove();
				$('#breadcrumb div [href="#drive"]').remove();
			}
			if(data.success ) {

				$( "#list" ).attr("data-pathname",data.path_name); //asignamos el path a #list
				$.each(data.results,function(k,v){ //recorremos los resultados
					$div_list.append(renderFileRow(k,v));
				});
				!data.results.length && $div_list.append('<tr><td class="empty" colspan=5>Esta carpeta está vacía</td></tr>')
				//data.is_writable ? $('#body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.results);
				console.warn(data.path_name);
			}
		});
	}

	function listSearch( pathname = false, search) {
		dataItem = [];
		var setPathname = pathname ? pathname : window.location.hash.substr(1);
	
		$.get('trash?listSearch=all&pathname='+ setPathname+'&search='+search,'json').done(function(data) {
			data = JSON.parse(data); // convertimos el resultado a formato JSON
			//console.log(data);
			$div_list.empty();

			if(setPathname){	
				//generar el Breadcrumbs
				$('#breadcrumb').empty().html(renderBreadcrumbs(setPathname));
				//elimar el primer nodo
				$('#breadcrumb div span').first().remove();
				$('#breadcrumb div [href="#drive"]').remove();
			}
			if(data.success ) {

				$( "#list" ).attr("data-pathname",data.path_name); //asignamos el pathname a #list
				$.each(data.results,function(k,v){ //recorremos los resultados
					$div_list.append(renderFileRow(k,v));
				});
				!data.results.length && $div_list.append('<tr><td class="empty" colspan=5>No se encontraron resultados.</td></tr>')
				//data.is_writable ? $('#body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.results);
				console.warn(data.path_name);
			}
		});
	}

	function renderFileRow(i , data) {
		
		var icon = null;
		if (data.info.is_dir) icon = '<i class="fas fa-folder"></i>'; else icon = '<i class="fas fa-file"></i>';
		
		var $html =
			`<div  class="my-item">
					<div class="selected hidde"><i class="fas fa-check"></i></div>
	                <input type="hidden" value="${i}" data-this="${utf8_to_b64(JSON.stringify(data))}">
	                ${icon}
	                <p data-tooltip="${data.name}" class="tooltip_elemento"><span class="item-name">${data.name}</span></p>
	                <div class="tooltip">Texto del tooltip</div>
	  
	        </div> `;
                   
		return $html;
	}

	function renderBreadcrumbs(pathname) {
		var base = "",
			$html = $('<div/>').append( $('<a href=#></a></div>') );
		$.each(pathname.split('%2F'),function(k,v){
			if( v ) {
				var v_as_text = decodeURIComponent(v);
				$html.append( $('<span/>').text(' / ') )
					.append( $('<a/>').attr('href','#'+base+v).text(v_as_text) );
				base += v + '%2F';
			}
		});
		return $html;
	}

	function getInfoItem(){

		let name = this_data.info.is_dir? '<i class="fas fa-folder"></i><span>'+this_data.name+'</span>':'<i class="fas fa-file"></i><span>'+this_data.name+'</span>';
			type = this_data.info.is_dir? "Folder":"File";
			size = formatFileSize(this_data.size);
			contain = this_data.info.is_dir? this_data.info.dirs +' Carpetas, '+this_data.info.files+' Archivos':'1 Archivo';
			create_at = formatTimestamp(this_data.ctime);
			modified = formatTimestamp(this_data.mtime);
			perm_write = this_data.is_writable? "true":"false";
			perm_read = this_data.is_readable? "true":"false";
			perm_delete = this_data.is_deleteable? "true":"false";
			perm_exec = this_data.is_executable? "true":"false";

		//console.log(this_data);
		$("#name").html(name);
		$("#type").html(type);
		$("#size").html(size);
		$("#contain").html(contain);
		$("#create_at").html(create_at);
		$("#modified").html(modified);
		$("#perm_write").html(perm_write);
		$("#perm_read").html(perm_read);
		$("#perm_delete").html(perm_delete);
		$("#perm_exec").html(perm_exec);
	}


});


