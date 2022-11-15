
$(function(){

	var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)')||0)[2]; //generate cookie
	
	FileManager_values = JSON.parse(FileManager_values); // values of class FileManager.php
	var MAX_UPLOAD_SIZE = FileManager_values.value.MAX_UPLOAD_SIZE;
	let dataItem = [];//array contenedor de item selected
	let this_data = null;//contiene info del ultimo item selected
	let move = false;// move is false 
	var $div_list = $('#list');// contain of items
	//$(window).on('hashchange',list).trigger('hashchange');
	list(); // list items

	/*====================================================
	EVENTS KEYUP
	====================================================*/
	$("#search").on('keyup',function(e){//listar search al dar click en  #search
		let pathname = $( "#list" ).attr("data-pathname");
		let search = $(this).val();
		pathname = pathname.replace( new RegExp("/","g") ,"%2F");
			window.location.hash = pathname; 
		if (search ==null || search =="" )
			list(pathname);
		else
			listSearch(pathname,search);
	});

	/*====================================================
	EVENTS CLICK
	====================================================*/

	$(".icon-back").on('click',function( event ){ //retroceder un directorio al dar click en  .icon-back
		var pathname = window.location.hash.substr(1);
		if (pathname == null || pathname=="" ) return;
		var array = pathname.split("%2F");
		array.pop();
		var newPathname ="";
		if (array.length <= 1) return;
		array.forEach(function(array, index, object) {
		    newPathname+= array+"%2F";
		});
		newPathname = newPathname.slice(0,-3);

		window.location.hash = newPathname; 
		list();
	});

	$(document).delegate("#breadcrumb div a" ,'click',function(e){//listar al dar click en  #breadcrumb
		var pathname = $(this).attr('href');
		pathname = pathname.substr(1);
		//console.log(pathname);
		list(pathname);
	});

	$(".js_btn_download").on('click',function( event ){ //descargar item al dar click en  .js_btn_download
		var is_dir = this_data.info.is_dir? 1 : 0;

		$(".js_btn_download").attr("disabled","disabled");
		//document.location.href='folder?download=1&pathname='+ path_name+'&is_dir='+is_dir;
		window.open('folder?download=1&pathname='+ this_data.path_name+'&is_dir='+is_dir, '_blank');
		return false;
	});

	$(".js_btn_share").on('click',function( event ){ //descargar item al dar click en  .js_btn_download
		
		var pathname = this_data.path_name;//.replace( new RegExp("/","g") , "%2F");
		pathname = utf8_to_b64(utf8_to_b64(pathname));
		console.log(this_data.path_name);
		swal("SHARE", window.location.protocol+"//"+window.location.host+BASE_URL+ "view_file?v="+ pathname);

		return false;
	});


	$(".info-item-button-right").on('click',function( event ){ //cerrar modal al dar click en  .info-item-button-right
		hide("#info_item");
		hide("#add_folder");
		hide("#add_file");
		hide("#rename_folder");
		hide("#show_file");
		$(".js_btn_download").removeAttr("disabled");
	});

	$("#menu_add_folder").on('click',function( event ){ //abrir modal add folder al dar click en  #menu_add_folder
		show("#add_folder","flex");
	});

	$("#menu_add_file").on('click',function( event ){ //abrir modal add  file al dar click en #menu_add_file
		show("#add_file","flex");
	});

	$("#menu_add").on('click',function( event ){ //abrir submenu  al dar click en  #menu_add
		
		if( this.classList.contains("menu-hidde") ){
			$(this).removeClass("menu-hidde");
			$(this).addClass("menu-show");
			show("#menu_add_file");
			show("#menu_add_folder");
		}else{
			$(this).removeClass("menu-show");
			$(this).addClass("menu-hidde");
			hide("#menu_add_file");
			hide("#menu_add_folder");
		}
	});

	$("#menu_rename_folder").on('click',function( event ){ //abrir modal rename al dar click en  #menu_rename_folder
		if (dataItem.length == 0 ) 
			{ swal("INFO", "Select an item.", "info"); return false; }
		else if ( dataItem.length > 1 ) 
			{ swal("INFO", "Can't edit multiple Items.", "info"); return false; }

		let name = dataItem[0].name;
		let isDir = dataItem[0].info.is_dir;

		if (isDir) $("#type_rename").html('<i class="fas fa-folder"></i><span>Rename Folder</span>');
		else  $("#type_rename").html('<i class="fas fa-file"></i><span>Rename File</span>');

		$("#input_rename_folder").val(name);
		show("#rename_folder","flex");
	});

	$("#btn_add_folder").on('click',function( event ){ //agregar carpeta al dar click en  #btn_add_folder
		let pathname = $( "#list" ).attr("data-pathname");
		let name = $( "#input_add_name_folder" ).val();
	
		$.post("folder",{'createFolder':'1',"pathname":pathname, "name": name }
			,'json').done(function(response){
				response = JSON.parse(response);
				console.log(response); 
				list();
		});
		hide("#add_folder");
		$( "#input_add_name_folder" ).val("name folder");
		return false;
	});

	$("#btn_add_file").on('click',function( event ){ //agregar archivo al dar click en  #btn_add_file
		{ swal("INFO", "Files are uploaded automatically", "info"); return false; }
		// let pathname = $( "#list" ).attr("data-pathname");
		// let name = $( "#input_add_name_folder" ).val();
	
		// $.post("folder",{'createFolder':'1',"pathname":pathname+"/", "name": name }
		// 	,'json').done(function(response){
		// 		list();
		// 		console.log(response);
		// });
		// hide("#add_folder");
		// $( "#input_add_name_folder" ).val("name folder");
		// return false;
	});

	$("#btn_rename_folder").on('click',function( event ){ //renombrar Item al dar click en  #btn_rename_folder
		
		let oldPathname = dataItem[0].path_name;
		let newname = $( "#input_rename_folder" ).val();
		$.post("folder",{'rename':'1',"oldPathname": oldPathname, "newname": newname }
			,'json').done(function(response){
				console.log(response);
				list();
				response = JSON.parse(response);
				
		});
		hide("#rename_folder");
		$( "#input_rename_folder" ).val("name folder");
		return false;
	});

	$("#menu_delete").on('click',function( event ){ //eliminar Items al dar click en  #menu_delete
		if (dataItem.length == 0 ) 
			{ swal("INFO", "Select one or more Elements.", "info"); return false; }
		
		//console.log(dataItem);

		$.post("folder",{'delete':'1',"item": dataItem  }
			,'json').done(function(response){
				list();
				response = JSON.parse(response);
				console.log(response);
		});
		
		return false;

	});

	// move and paste fie
	$("#menu_move").on('click',function( event ){ //eliminar Items al dar click en  #menu_delete
		if (dataItem.length == 0 ) 
			{ swal("INFO", "Select one or more Elements.", "info"); return false; }
		move = true;
		
		hide("#menu_move");
		show("#menu_paste");
		return false;

	});

	$("#menu_paste").on('click',function( event ){ //eliminar Items al dar click en  #menu_delete
		
		let newPathname = $( "#list" ).attr("data-pathname");
		//console.log(dataItem);

		$.post("folder",{'move':'1',"newPathname":newPathname,"item": dataItem  }
			,'json').done(function(response){
				list();
				response = JSON.parse(response);
				console.log(response);
				move = false;
		});

		hide("#menu_paste");
		show("#menu_move");
		dataItem = [];
		
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
			if(!this_data.info.is_dir) {showItemSelected(_this); return ; }

			var pathname =  this_data.path_name;
			pathname = pathname.replace( new RegExp("/","g") ,"%2F");
			window.location.hash = pathname; 
			list(pathname);
			return ; // end dblclick
		}
		timer = setTimeout(function() { // Manejo de eventos de un click
    		itemSelected(_this);
		}, 300); // end click
		
	});

	$(document).delegate(".my-item" ,'contextmenu',function( e){// mostrar info del item on contextmenu en .my-item
    	
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
		let pathname = this_data.path_name;
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
			    if(array.path_name === pathname){
			      object.splice(index, 1);
			    }
			});
		}
   		console.log(dataItem);
   		//console.log("mousedown");
	}

	function showItemSelected(_this){
		let pathname = this_data.path_name;
		let ext = this_data.info.extension;

		if (ext == "pdf"  && innerWidth <= 800) 
			ext = "movil-pdf";
	
		$.get('folder?showFile=1&pathname='+ pathname+'&extension='+ext,'json').done(function(res) {
		
			//console.log(res);

			show('#show_file',"flex");
			$('#cont_file').empty();
			$('#cont_file').append(res);
		});
		
	}
	function getInfoItem(){

		let name = this_data.info.is_dir? '<i class="fas fa-folder"></i><span>'+this_data.name+'</span>':'<i class="fas fa-file"></i><span>'+this_data.name+'</span>',
			is_dir = this_data.info.is_dir? 1 : 0 ,
			path = this_data.path,
			type = this_data.info.is_dir? "Folder":"File",
			size = formatFileSize(this_data.size),
			contain = this_data.info.is_dir? this_data.info.dirs +' Folders, '+this_data.info.files+' Files':'1 File',
			create_at = formatTimestamp(this_data.ctime),
			modified = formatTimestamp(this_data.mtime),
			perm_write = this_data.is_writable? "true":"false",
			perm_read = this_data.is_readable? "true":"false",
			perm_delete = this_data.is_deleteable? "true":"false",
			perm_exec = this_data.is_executable? "true":"false";

		//console.log(this_data);
		$("#name").html(name);
		$("#is_dir").val(is_dir);
		$("#path").val(path);
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
	/*====================================================
	UPLOAD FILE
	====================================================*/
	var allow_upload = FileManager_values.value.allow_upload;
	if(allow_upload){
		// file upload stuff
		$('#file_drop_target').on('dragover',function(){
			$(this).addClass('drag_over');
			return false;
		}).on('dragend',function(){
			$(this).removeClass('drag_over');
			return false;
		}).on('drop',function(e){
			e.preventDefault();
			var files = e.originalEvent.dataTransfer.files;
			$.each(files,function(k,file) {
				uploadFile(file);
			});
			$(this).removeClass('drag_over');
		});
		$('#load_file').change(function(e) {
			e.preventDefault();
			$.each(this.files,function(k,file) {
				uploadFile(file);
			});
		});


		function uploadFile(file) {
			var pathname = decodeURIComponent(window.location.hash.substr(1));
			
			if(file.size > MAX_UPLOAD_SIZE) {
				var $error_row = renderFileSizeErrorRow(file,pathname);
				$('#upload_progress').append($error_row);
				window.setTimeout(function(){$error_row.fadeOut();},5000);
				return false;
			}

			var $row = renderFileUploadRow(file,pathname);
			$('#upload_progress').append($row);
			var fd = new FormData();
			fd.append('file_data',  file ); 
			fd.append('pathname',pathname);
			fd.append('xsrf',XSRF);
			fd.append('upload','1');

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'folder',true);
			xhr.onload = function(e) {
				
				$row.remove();
	    		list();
				console.log(e.target.response);
	  		};
			xhr.upload.onprogress = function(e){
				if(e.lengthComputable) {
					$row.find('.progress').css('width',(e.loaded/e.total*100 | 0)+'%' );
				}
			};
			xhr.onerror = function(e,i) {
				console.log(e);
	  		};

		    xhr.send(fd);
		    //console.log(  xhr);

		    
		}
		function renderFileUploadRow(file,folder) {
			return $row = $('<div/>')
				.append( $('<span class="fileuploadname" />').text( (folder ? folder+'/':'')+file.name))
				.append( $('<div class="progress_track"><div class="progress"></div></div>')  )
				.append( $('<span class="size" />').text(formatFileSize(file.size)) )
		};
		function renderFileSizeErrorRow(file,folder) {
			return $row = $('<div class="error" />')
				.append( $('<span class="fileuploadname" />').text( 'Error: ' + (folder ? folder+'/':'')+file.name))
				.append( $('<span/>').html(' file size - <b>' + formatFileSize(file.size) + '</b>'
					+' exceeds max upload size of <b>' + formatFileSize(MAX_UPLOAD_SIZE) + '</b>')  );
		}
	}

	/*====================================================
	LIST
	====================================================*/
	function list( pathname = false) {
		if (!move) 
			dataItem = [];

		var setPathname = pathname ? pathname : window.location.hash.substr(1);

		$.get('folder?list=all&pathname='+ setPathname,'json').done(function(data) {
			
			//console.log(data);
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
					if (v.info.is_dir) { //listar carpetas
						$div_list.append(renderFileRow(k,v));
					}
				});
				$.each(data.results,function(k,v){ //recorremos los resultados
					if (!v.info.is_dir) { //listar archivos
						$div_list.append(renderFileRow(k,v));
					}
				});
				!data.results.length && $div_list.append('<tr><td class="empty" colspan=5>This folder is empty</td></tr>')
				//data.is_writable ? $('#body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.results);
				console.warn(data.path_name);
			}
		});
	}

	function listSearch( pathname = false, search) {
		if (!move) 
			dataItem = [];
		
		var setPathname = pathname ? pathname : window.location.hash.substr(1);
	
		$.get('folder?listSearch=all&pathname='+ setPathname+'&search='+search,'json').done(function(data) {
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
					if (v.info.is_dir) { //listar carpetas
						$div_list.append(renderFileRow(k,v));
					}
				});
				$.each(data.results,function(k,v){ //recorremos los resultados
					if (!v.info.is_dir) { //listar archivos
						$div_list.append(renderFileRow(k,v));
					}
				});

				!data.results.length && $div_list.append('<tr><td class="empty" colspan=5>No results found.</td></tr>')
				//data.is_writable ? $('#body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.results);
				console.warn(data.path_name);
			}
		});
	}

	function renderFileRow(i , data) {
		
		var icon = null;
		if (data.info.is_dir) 
			icon = '<i class="fas fa-folder"></i>';
		else
			icon  = getIcon(data.info.extension);
		
		var $html =
			`<div  class="my-item" translate="no">
				<div class="selected hidde"><i class="fas fa-check"></i></div>
                <input type="hidden" value="${i}" data-this="${utf8_to_b64(JSON.stringify(data))}">
                ${icon}
                <p data-tooltip="${data.name}" class="tooltip_elemento"><span class="item-name">${data.name}</span></p>
                <div class="tooltip">Texto del tooltip</div>
            </div> `;
                   
		return $html;
	}

	function getIcon(extension){
		let $img =['png', 'jpg','gif','jpeg','bmp','tif'];
		let	$music =['mp3','ogg','m4a','wav','wma','aiff','au','mid'];
		let	$video =['mp4','webm','mov','wmv','avi','wmv','flv','mkv','ts'];
		let	$word =['doc','docx','dot'];
		let	$excel =['xls','xlsx','xlsm','xlt'];
		let	$powerPoint =['ppt','pptx','pps','pot'];
		//let	$txt =['txt','html','css','js','php','sql'];

		if ($word.indexOf(extension)  >= 0) 
			icon = '<i class="fas fa-file-word icon"></i>';
		else if  ($excel.indexOf(extension)  >= 0) 
			icon = '<i class="fas fa-file-excel icon"></i>';
		else if  ($powerPoint.indexOf(extension)  >= 0) 
			icon = '<i class="fas fa-file-powerpoint icon"></i>';
		else if (extension == "pdf")  
			icon = '<i class="fas fa-file-pdf icon"></i>';
		else if (extension == "zip" || extension == "rar")  
			icon = '<i class="fas fa-file-archive icon"></i>';
		else if ( $img.indexOf(extension)  >= 0)  
			icon = '<i class="fas fa-file-image icon"></i>';
		else if ( $music.indexOf(extension)  >= 0)  
			icon = '<i class="fas fa-headphones-alt icon"></i>';
		else if ($video.indexOf(extension)  >= 0)  
			icon = '<i class="fas fa-file-video icon"></i>';
		else 
			icon = '<i class="fas fa-file icon"></i>';
		return icon;
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


});