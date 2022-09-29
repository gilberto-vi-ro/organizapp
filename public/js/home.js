
/*====================================================
ITEM
====================================================*/ 
hide("#_msg");

let dataItem = [];//array contenedor de item selected
let this_data = null;//contiene info del ultimo item selected

dateRange();
list();

function list(path=false, priority = 0, search = "", range = null){
	dataItem = [];
	if(path)
		 window.location.hash = path;
	if(range == null)
		range = date_range1.value+"::"+ date_range2.value;
	listFolder();  // list folder
	listTask( $("#list_task_pending") , 'listTaskPending', priority, search, range); // list task
	listTask( $("#list_task_done") , "listTaskDone", priority, search, range) // list task
	listTask( $("#list_task_delivered"), "listTaskDelivered", priority, search, range) // list task

}
	/*====================================================
	EVENTS CHANGE
	====================================================*/
	$("#task_priority").on('change',function( event ){  //change list on #task_priority 
		//console.log(priority);
		let path = window.location.hash.substr(1);
		let priority = this.value;
		let search = id_search.value;
		list(path, priority, search);
	});

	$("#date_range1").on('change',function( event ){  //change range date on #date_range1 
		let path = window.location.hash.substr(1);
		let priority = task_priority.value;
		let search = id_search.value;
		let range = date_range1.value+"::"+ date_range2.value;
		list(path, priority,search, range );
	});

	$("#date_range2").on('change',function( event ){  //change range date on #date_range2 
		let path = window.location.hash.substr(1);
		let priority = task_priority.value;
		let search = id_search.value;
		let range = date_range1.value+"::"+ date_range2.value;
		list(path, priority,search, range );
	});

	/*====================================================
	EVENTS KEYUP
	====================================================*/
	$("#id_search").on('keyup',function( event ){ //search on keyup #id_search
		//console.log(priority);
		let path = window.location.hash.substr(1);
		let priority = task_priority.value;
		let search = this.value;
		list(path, priority, search);
	});
	/*====================================================
	ADD TASK EVENTS SUBMIT
	====================================================*/
	$("#add_task_form").on('submit',function( event ){ //agregar nueva tarea al dar click submit #add_task_form
		//let form = $("#add_task_form").serialize() ;
		let form = new FormData(document.getElementById('add_task_form'));
			form.append('addNewTask', 1 ); 
			form.append('path_name', $( "#breadcrumb" ).attr("data-path") ); 
		
		//console.log(form);
		let res = loadAjax("home", "POST" , form);
		//console.log(res);
		res = JSON.parse(res);
		
		$('.msg-lbl-txt').html(res.response[0].msg);
		show("#_msg");
		
		list();
		$('#add_task_form')[0].reset();
		hide("#add_task");
		return false;//para que no envie get o post automatico

	});
	/*====================================================
	EVENTS CLICK
	====================================================*/

	$(".icon-back").on('click',function( event ){ //retroceder un directorio al dar click en  .icon-back
		var path = window.location.hash.substr(1);
		if (path == null || path=="" ) return;
		var array = path.split("%2F");
		array.pop();
		var newPath ="";
		if (array.length <= 1) return;
		array.forEach(function(array, index, object) {
		    newPath+= array+"%2F";
		});
		newPath = newPath.slice(0,-3);
		list(newPath);
	});

	$(document).delegate("#breadcrumb div a" ,'click',function(e){//listar al dar click en  #breadcrumb
		var path = $(this).attr('href');
		path = path.substr(1);
		//console.log(path);
		list(path);
	});

	$(".icon-foldersearch").on('click',function( event ){ //mostrar carpetas al dar click en  .icon-foldersearch
		show("#list_folder", "flex");
	});

	$("#list_folder .close-modal").on('click',function( event ){ //cerrar modal  folder .close-modal
		hide("#list_folder");
	});

	$("#menu_add").on('click',function( event ){ //abrir modal add task al dar click en  #menu_add
		show("#add_task","flex");
	});

	$("#menu_edit").on('click',function( event ){ //abrir modal edit  task al dar click en  #menu_edit
		if (dataItem.length == 0 ) 
			{ alert("Selecciona un Item"); return false; }
		if ( dataItem.length > 1 ) 
			{ alert("No se puede editar multiples Task."); return false; }

		InfoItemEnabled(true);
		getInfoItem(dataItem[0]); 
		show(".js_btn_edit_task");
		show("#edit_task_file");
		show("#edit_task","flex");
	});

	$(".js_btn_edit_task").on('click',function( event ){ //editar  task al dar click en  .js_btn_edit_task
		let form = new FormData(document.getElementById("edit_task_form"));
		form.append('id_tarea', dataItem[0].id_tarea ); 
		form.append('id_carpeta', dataItem[0].id_carpeta ); 
		form.append('pathname', dataItem[0].carpeta_path_name );
		form.append('editTask', "1" ); 
		
		let res = loadAjax("home", "POST" , form);
		console.log(res);

		let path = window.location.hash.substr(1);
		let priority = task_priority.value;
		list(path, priority);
				
		hide("#edit_task");
		return false;
	});


	$("#menu_delete").on('click',function( event ){ //borrar item al dar click en  #menu_delete
		if (dataItem.length == 0 ) 
			{ alert("Selecciona un o varios Items"); return false; }
		if (dataItem.length == 1) 
			var opcion = confirm("Esta seguro que deseas eliminar a "+ dataItem[0].tarea_nombre +"?");
		if (dataItem.length > 1) 
			var opcion = confirm("Esta seguro que deseas eliminar los archivos seleccionados?");
		if (!opcion) return;


		$.post("home",{'deleteTask':'1',"item": dataItem  }
			,'json').done(function(response){
				let path = window.location.hash.substr(1);
				let priority = task_priority.value;
				list(path, priority);
			
				console.log(response);
		});
		
		return false;
	});

	$(".task-item-button-right").on('click',function( event ){ //cerrar modal al dar click en  .info-item-button-right
		hide("#add_task");
		hide("#edit_task");
		//hide("#add_folder");
		//hide("#add_file");
		//hide("#rename_folder");
		//hide("#show_file");
		//$(".js_btn_download").removeAttr("disabled");
	});

	function getInfoItem(data){

		console.log(data);
		let date = data.tarea_fecha_entrega;
			date = date.replace(new RegExp(" ","g") ,"T");
		$("#edit_task_name").val(data.tarea_nombre);
		$("#edit_task_delivery_date").val(date);//date.toLocaleDateString('en-GB')
		$("#edit_task_status").val(data.tarea_estado);
		$("#edit_task_description").val(data.tarea_descripcion);
		$("#edit_task_priority").val(data.tarea_prioridad); 
		$("#edit_task_name_file").text(data.archivo_nombre);

		let filePath = loadAjax("home?getPathFile=1&idFile="+data.id_archivo, "GET");
		let path = (data.archivo_nombre == "null")?"null": filePath;
		$("#edit_task_path").text(path );
		if (path==null || path=="null")
			$("#edit_task_path").attr("href","#") ;
		else
			$("#edit_task_path").attr("href",BASE_URL + "folder#" + path) ;
		
	}

	function InfoItemEnabled(enabled=true){

		if (enabled){
			$(".txt-new-task").text("Edit task");
			$("#edit_task_name").removeAttr("disabled");
			$("#edit_task_delivery_date").removeAttr("disabled");
			$("#edit_task_status").removeAttr("disabled");
			$("#edit_task_description").removeAttr("disabled");
			$("#edit_task_priority").removeAttr("disabled");
			$("#edit_task_name_file").removeAttr("disabled");
			$("#edit_task_path").removeAttr("disabled");
		}else{
			$(".txt-new-task").text("My task");
			$("#edit_task_name").attr("disabled" , "disabled");
			$("#edit_task_delivery_date").attr("disabled" , "disabled");
			$("#edit_task_status").attr("disabled" , "disabled");
			$("#edit_task_description").attr("disabled" , "disabled");
			$("#edit_task_priority").attr("disabled" , "disabled");
			$("#edit_task_name_file").attr("disabled" , "disabled");
			$("#edit_task_path").attr("disabled" , "disabled");
		}
	}


	
	
	/*====================================================
	FOLDER DBLCLICK
	====================================================*/
	var timer = null; 
	$(document).delegate("#list_folder .my-item" ,'dblclick',function( event ){  // dblclick folder
	
		data = $(this.querySelector("input")).attr('data-this');
    	data = JSON.parse(b64_to_utf8(data));
    	var path =  data.path_name;
		path = path.replace( new RegExp("/","g") ,"%2F");
		list(path);
		return ; // end dblclick
	});

	/*====================================================
	TASK CLICK, DBLCLICK, CONTEXTMENU
	====================================================*/
	$(document).delegate(".js_list_task .my-item" ,'contextmenu',function( event ){  // contextmenu .js_list_task .my-item
	
		let data = $(this.querySelector("input")).attr('data-this');
    	data = JSON.parse(b64_to_utf8(data));
    	//console.log(data);

		InfoItemEnabled(false);
		getInfoItem(data);

		hide(".js_btn_edit_task");
		hide("#edit_task_file");
		show("#edit_task","flex");
		return false; // end contextmenu
	});

	 
	$(document).delegate(".js_list_task .my-item" ,'click',function( event ){  // click on .js_list_task.my-item
		this_data = $(this.querySelector("input")).attr('data-this');
    	this_data = JSON.parse(b64_to_utf8(this_data));
		itemSelected(this);
		//console.log(this_data);
	});

	function itemSelected(_this){
		let selected = _this.querySelector(".selected");
		let id_tarea = this_data.id_tarea;
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
			    if(array.id_tarea === id_tarea){
			      object.splice(index, 1);
			    }
			});
		}
   		//console.log(dataItem);
   		//console.log("mousedown");
	}



	/*====================================================
	LIST FOLDER
	====================================================*/

	function listFolder( path = false) {
		var $div_list_folder = $('#list_item_folder');// contain of items list_item_folder
		var setPath = path ? path : window.location.hash.substr(1);

		$.get('home?listFolder=1&path='+ setPath,'json').done(function(data) {
			data = JSON.parse(data); // convertimos el resultado a formato JSON
			//console.log(data); return;
			$div_list_folder.empty();

			if(setPath){	
				//generar el Breadcrumbs
				$('#breadcrumb').empty().html(renderBreadcrumbs(setPath));
				//elimar el primer nodo
				$('#breadcrumb div span').first().remove();
				$('#breadcrumb div [href="#drive"]').remove();
			}
			if(data.success ) {

				$( "#breadcrumb" ).attr("data-path",data.path); //asignamos el path a #list_item_folder
				let i=0;
				$.each(data.results,function(k,v){ //recorremos los resultados
					if (v.info.is_dir) {
						$div_list_folder.append(renderFolderRow(k,v));
						i++;
					}
					
				});
				i==0? $div_list_folder.append('<tr><td class="empty" colspan=5>No hay mas carpetas.</td></tr>'): "";
				!data.results.length && $div_list_folder.append('<tr><td class="empty" colspan=5>Esta carpeta está vacía</td></tr>')
				//data.is_writable ? $('#body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.results);
				console.warn(data.path);
			}
		});
	}

	function renderFolderRow(i , data) {
		
		var icon = null;
		if (data.info.is_dir) icon = '<i class="fas fa-folder"></i>'; else icon = '<i class="fas fa-file"></i>';
		
		var $html =
			`<div  class="my-item">
                <input type="hidden" value="${i}" data-this="${utf8_to_b64(JSON.stringify(data))}">
                ${icon}
                <p data-tooltip="${data.name}" class="tooltip_elemento"><span class="item-name">${data.name}</span></p>
                <div class="tooltip">Texto del tooltip</div>
            </div> `;
                   
		return $html;
	}


	function renderBreadcrumbs(path) {
		var base = "",
			$html = $('<div/>').append( $('<a href=#></a></div>') );
		$.each(path.split('%2F'),function(k,v){
			if( v ) {
				var v_as_text = decodeURIComponent(v);
				$html.append( $('<span/>').text(' / ') )
					.append( $('<a/>').attr('href','#'+base+v).text(v_as_text) );
				base += v + '%2F';
			}
		});
		return $html;
	}
	/*====================================================
	LIST TASK
	====================================================*/
	function listTask($cont_list_task, state_task, priority, search, range) {

		var path = window.location.hash.substr(1);

		search = search.replace( new RegExp("#","g") ,encodeURIComponent("#"));
		let url = 'home?'+state_task+'=1&priority='+priority+'&search='+search+'&path='+ path+'&range='+ range;
		$.get(url,'json').done(function(data) {
			
			//console.log(data); return;
			data = JSON.parse(data); // convertimos el resultado a formato JSON
			
			$cont_list_task.empty();

			if(data.success ) {

				$( "#breadcrumb" ).attr("data-path",data.path); //asignamos el path a #list_item_folder
				
				$.each(data.results,function(k,v){ //recorremos los resultados
					
					$cont_list_task.append(renderTaskRow(k,v));
				
				});
				!data.results.length && $cont_list_task.append('<tr><td class="empty" colspan=5>No hay tareas.</td></tr>')
				//data.is_writable ? $('#body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.results);
				console.warn(data.path);
			}
		});
	}


	function renderTaskRow(i , data ) {
		let date =  dateFormat(new Date(data.tarea_fecha_entrega), 'dd/mm/yyyy hh:ii:ss ampm');
		let taskIsTomorrow = ( taskIsTomorrow2(data.tarea_fecha_entrega)&&data.tarea_estado!=3 )?"task-warning":"";

		var $html =
			`<div  id="${data.id_tarea}"  class="my-item ${taskIsTomorrow }"   ondragstart="dragStart(event)" ondrag="drag(event)" draggable="true">
				<div class="selected hidde"><i class="fas fa-check"></i></div>
                <input type="hidden" value="${i}" data-this="${utf8_to_b64(JSON.stringify(data))}" class="js-data-this">
                
                <p data-tooltip="${data.tarea_nombre}" class="tooltip_elemento"><span class="item-name">${data.tarea_nombre}</span></p>
                <div class="tooltip">Texto del tooltip</div>

                <img src="${BASE_URL}public/img/icon/task.png" class="logo-task" >
                <p class="item-expired" style="font-size: 12px;" >${date}</p>
            </div> `;
                   
		return $html;
	}

	//alert(addDays(d, 4));
	function dateAddDays(datetime, days=1){
		var date = new Date(datetime);
		date.setDate(date.getDate() + days);
		return date;
	}

	function taskIsTomorrow2(datetime){
		
		var now = new Date(); //now
		f1 = dateAddDays(now);
		var f2 = new Date(datetime);
		now.setHours(0,0,0,0);
		f1.setHours(0,0,0,0);
		f2.setHours(0,0,0,0);
		if (f1.getTime() == f2.getTime() || now.getTime()== f2.getTime() || f2.getTime() < now.getTime() ){
			//console.log("es para manana");
			return true;
		}
		return false;
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

	/*=============================================================================
	DRAG
	===============================================================================*/
	let item_data ;
	function dragStart(event) {
		//mydrag = event.target;

		try{
		event.dataTransfer.setData("Data", event.target.id);
		item_data = $(event.target.querySelector("input")).attr('data-this');
		item_data = JSON.parse(b64_to_utf8(item_data));
		//console.log(item_data);
		}catch(e){
			return false;
		}
	}
	
	function drag(event) {
		event.preventDefault();
		// document.getElementById("miDemo").innerHTML = "El elemento p está siendo arrastrado";
	}
	
	function dragover(event) {
		event.preventDefault();
	}
	

	function drop(event,idStatus) {
		event.preventDefault();
		const id = event.dataTransfer.getData('Data');
		//console.log(id);return;
		const draggableElement = document.getElementById(id);
		if(draggableElement == null || draggableElement == "undefined" ) return false;
		const dropzone = document.getElementById(idStatus);
		dropzone.append(draggableElement);
		event.dataTransfer.clearData();
		updateStatus(id, idStatus);
		//document.getElementById("miDemo").innerHTML = "El elemento p fue eliminado";
	}

	function updateStatus(id,status){
		let form = new FormData();
		form.append('id_tarea', id ); 
		form.append('estado', (status=="list_task_pending")?1:(status=="list_task_done")?2:3 );
		form.append('editStatusTask', "1" ); 
		
		let res = loadAjax("home", "POST" , form);
		console.log(res);
		list();
	}
	
 