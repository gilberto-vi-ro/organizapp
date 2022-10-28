<?php 
	/**
	* 
	*/
	class HomeController	
	{
		private $FileManager;
		private $HomeModel;
		private $idUser;

		function __construct()
		{
			$this->HomeModel = new HomeModel();
			$this->FileManager = new FileManager($this->getPathDefault());
			$this->FileManager->hideExtension(['php','trash']);
			$this->idUser = session("id_usuario");
		}

	    public  function getImg () 
	    {   
	        $dataUser = $this->HomeModel->getUser($this->idUser);
	        $userImg = $dataUser->img;
	   	 	if ($userImg == null) 
                $img = BASE_URL."public/img/icon/user.png"; 
            else 
                $img = $userImg; 
	
	        return $img; 
	    }
	    public  function getName() 
	    {   
	        $dataUser = $this->HomeModel->getUser($this->idUser);
	        $nameUser = $dataUser->nombre_completo;
	 
	        return $nameUser; 
	    }

	    public  function getPathDefault() 
	    {   
	   		$idUser = $this->idUser;
	   		$dp = $this->HomeModel->getPathDefault($idUser);
			$path = ($dp)? $dp  : "drive/$idUser/";
	        return $path; 
	    }

	    private  function pathIgnored($path) 
	    {   
			$search =  $this->idUser;
			if (stripos($path, $search) === false)  
			 	return true;
			 
    		return $this->FileManager->pathIgnored($path);
	    }

		public  function getPathFile($idfile) 
	    {   
    	    echo $this->HomeModel->getPathFileInDB($idfile);
	    }

		/*====================================================
		FILE MANAGER
		====================================================*/
		public  function upload($pathname, $idFolder, $_FILE_ ) 
	    {   
			// print_r($_FILE_);
			$file["name"] = $_FILE_["file_data"]["name"];
			$file["extension"] = $this->FileManager->getExtension($_FILE_["file_data"]["name"]);
			$file["size"] = $_FILE_["file_data"]["size"];
			//print_r($file["extension"]);
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
	   		$this->FileManager->setPath($pathname);
			//agregamos en la BD
			$res = $this->HomeModel->reInsertFileInDB( $file, $idFolder );
			if ($res) {
				setMsg( "success","Se guardo correctamente en la BD." );
				//agregamos en FileManager
				$res2 = $this->FileManager->uploadFile( $_FILE_ );
				if ($res2) setMsg( "success","Se cargo correctamente en el Gestor." );
				else setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
			}
			else setMsg( "error","ocurrio un error al guardar en la BD.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );
			
			print_r( json_encode(getMsg()) );
	        //exit();
	    }

	    public  function addNewTask($data) 
	    {   
	    	$pathname = $this->FileManager->convertToPathname($data['path_name']);
	    	$idFolder = $this->HomeModel->getIdFolder($pathname, $this->idUser);
	    	$data["id_folder"] = $idFolder;
			//echo"$idFolder \n";
	    	$res = $this->HomeModel->addNewTask($data, $idFolder);
			if ($res === 2) setMsg( "error","La tarea ya existe en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
			else if ($res) {
	    		setMsg( "success","La tarea se agrego en la BD." ); 
	    	}else{
	    		setMsg( "error","ocurrio un error al agregar la tarea en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
	    	}
			print_r( json_encode(getMsg()) );
	    	exit();
	    }

		public  function editStatusTask($post) 
	    {   
			//renombramos en la bd
			$res = $this->HomeModel->editStatusTaskInDB($post);
			if ($res) {
	    		setMsg( "success","La tarea se actualizó en la BD." ); 
	    	}else{
	    		setMsg( "error","ocurrio un error al editar la tarea en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
	    	}
			print_r( json_encode(getMsg(),JSON_UNESCAPED_UNICODE) );
			exit();
	    }
		public  function editTask($post) 
	    {   
			$GLOBALS["id_file"]=null;
			if ( $_FILES["file_data"]["name"] != null || $_FILES["file_data"]["name"] != "" )
				$this->upload($post["pathname"],$post["id_carpeta"], $_FILES);
			
			//renombramos en la bd
			$res = $this->HomeModel->editTaskInDB($post);
			if ($res === 2) setMsg( "error","La tarea ya existe en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
			else if ($res) {
	    		setMsg( "success","La tarea se actualizó en la BD." ); 
	    	}else{
	    		setMsg( "error","ocurrio un error al editar la tarea en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
	    	}
			print_r( json_encode(getMsg(),JSON_UNESCAPED_UNICODE) );
			exit();
	    }

		public  function moveTask($_newPathname, $item) 
	    {   
			$newPathname = $this->FileManager->convertToPathname($_newPathname) ;
			
			$idPathname = $this->HomeModel->getIdPathnameInDB($newPathname) ;
	   		foreach ( $item as $key => $value) {

				if($value["archivo_nombre"] != "null"){
					$oldPathname = $this->FileManager->convertToPathname ($value["carpeta_path_name"]."/".$value["archivo_nombre"]);
					if(!$this->moveFile($oldPathname , $newPathname, $value["archivo_nombre"]))
					{
						setMsg( "error","Ocurrio un error al mover la tarea en la BD: ".$value["tarea_nombre"]);
						continue;
					}
				}
				$res = $this->HomeModel->updatePathTaskInDB($idPathname, $value["id_tarea"]);
				if ($res) 
					setMsg( "success","La tarea Se movio correctamente en la BD: ".$value["tarea_nombre"] );
				else {setMsg( "error","Ocurrio un error al mover la tarea en la BD: ".$value["tarea_nombre"], __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );		continue;
				}
			}
			print_r( getMsgInText() );
			exit();
	    }

		public  function moveFile($oldPathname, $newPathname, $nameFile) 
	    {   
				//renombramos en la bd
				
				if ( is_dir($oldPathname) )
					$res = $this->HomeModel->updatePathFolderInDB($oldPathname, $newPathname);
				else
					$res = $this->HomeModel->updatePathFileInDB( $this->getIdFile($oldPathname), $this->FileManager->convertToPathname($newPathname) );
				
				if ($res) setMsg( "success","El archivo Se movio correctamente en la BD: ". $nameFile );
				else {
					setMsg( "error","Ocurrio un error al mover el archivo en la BD:  $nameFile, puede que la ruta del archivo sea diferente a la ruta de la tarea.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );
					return false;
				}
				
				//renombramos en el FileManager
				$res2 = $this->FileManager->move( $oldPathname , $newPathname."/".$nameFile );
				if ($res2) setMsg( "success","El archivo Se movio correctamente en el Gestor." );
				else {
					setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
					return false;
				}
				
			//print_r( json_encode(getMsg()) );
			return true;
	    }

		/**
		* get id file : return id of database of file
		* @param string $oldPathname old pathname of file
		* @return string $idFile id of file
		*/
		private function getIdFile($oldPathname){
			$pathFolder = $this->FileManager->getPath($oldPathname);
			$idFolder = $this->HomeModel->getIdFolder( $this->FileManager->convertToPathname($pathFolder) , $this->idUser);
			$oldName = $this->FileManager->getName( $oldPathname);
			$oldExt = $this->FileManager->getExtension($oldName);

			$idFile = $this->HomeModel->getIdFile($oldName,$oldExt,$idFolder);
			return $idFile;
		}

		public  function deleteTask($item) 
	    {   
			foreach ( $item as $key => $value) {
				//borramos en la bd
				$res = $this->HomeModel->deleteTaskInDB($value["id_tarea"]);
			}
			exit();
	    }

	    /*=========================================================================
	    LIST
	    ===========================================================================*/

	    public  function listFolder($path) 
	    {   
	    	if ( $this->pathIgnored($path) ) {
	    		$path = $this->getPathDefault();
	    	}
   			$this->FileManager->setPath($path);
    		echo json_encode($this->FileManager->listAll());
	   		exit();
	   		
	    }

	    public  function listTaskPending($pathname, $priority, $search, $range) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
			$pathname = $this->FileManager->convertToPathname($pathname);
   			$list = [];
   			$result = [];
   			$res=$this->HomeModel->getTaskPending($pathname, $priority, $search, $range);
   			if ($res)
   				$list = [ 'success' => true, 'path' => $pathname, 'results' => $res ];
   			else 
   				$list = [ 'success' => true, 'path' => $pathname, 'results' => $result ];
    		echo json_encode($list);

	   		exit();
	   		
	    }
	    public  function listTaskDone($pathname, $priority, $search, $range) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
			$pathname = $this->FileManager->convertToPathname($pathname);
	    	$list = [];
   			$result = [];
   			$res=$this->HomeModel->getTaskDone($pathname, $priority, $search, $range);
   			if ($res)
   				$list = [ 'success' => true, 'path' => $pathname, 'results' => $res ];
   			else 
   				$list = [ 'success' => true, 'path' => $pathname, 'results' => $result ];
    		echo json_encode($list);

	   		exit();
	   		
	    }
	    public  function listTaskDelivered($pathname, $priority, $search, $range) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) { 
	    		$pathname = $this->getPathDefault();
	    	}
			$pathname = $this->FileManager->convertToPathname($pathname);
   			$list = [];
   			$result = [];
   			$res=$this->HomeModel->getTaskDelivered($pathname, $priority, $search, $range);
   			if ($res)
   				$list = [ 'success' => true, 'path' => $pathname, 'results' => $res ];
   			else 
   				$list = [ 'success' => true, 'path' => $pathname, 'results' => $result ];
    		echo json_encode($list);

	   		exit();
	   		
	    }
		
	}

 ?>