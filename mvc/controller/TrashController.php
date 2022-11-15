<?php 
	/**
	* 
	*/
	class TrashController
	{
		private $FileManager;
		private $TrashModel;
		private $idUser;

		function __construct()
		{   
			$this->TrashModel = new TrashModel();
			$this->FileManager = new FileManager("drive/".$this->getPathDefault());
			$this->FileManager->hideExtension(['php','trash']);
			$this->idUser = session("id_usuario");
		}



	    public  function getImg () 
	    {   
	   		$idUser = $this->idUser;

	        $dataUser = $this->TrashModel->getUser($idUser);
	        $userImg = $dataUser->img;
	   	 	if ($userImg == null) 
                $img = BASE_URL."public/img/icon/user.png"; 
            else 
                $img = $userImg; 
	
	        return $img; 
	    }
	    public  function getName() 
	    {   
	   		$idUser = $this->idUser;

	        $dataUser = $this->TrashModel->getUser($idUser);
	        $nameUser = $dataUser->nombre_completo;
	 
	        return $nameUser; 
	    }

	    private  function pathIgnored($path) 
	    {   
			$search = $this->idUser;
			if (!stripos($path, $search) ) 
			 	return true;
			 
    		return $this->FileManager->pathIgnored($path);
	    }

	    public  function getPathDefault() 
	    {   
	   		$idUser = $this->idUser;
	   		$dp = $this->TrashModel->getPathDefault($idUser);
	   	
	   		$path = ($dp)? $dp  : "drive/".$idUser ;
	        return $path;  
	    }

		/*====================================================
		FILE MANAGER
		====================================================*/
		/**
		* get id file : return id of database of file
		* @param string $oldPathname old pathname of file
		* @return string $idFile id of file
		*/
		private function getIdFile($oldPathname){
			$pathFolder = $this->FileManager->getPath($oldPathname);
			$idFolder = $this->TrashModel->getIdFolder( $this->FileManager->convertToPathname($pathFolder) , $this->idUser);
			$oldName = $this->FileManager->getName( $oldPathname);
			$oldExt = $this->FileManager->getExtension($oldName);
			$idFile = $this->TrashModel->getIdFile($oldName,$oldExt,$idFolder);

			return $idFile;
		}

	    public  function restoreTrash($item) 
	    {   
			function replaceTrash($value){
				return str_replace(".trash", "", $value);
			}
	   		
			foreach ( $item as $key => $value) {
				//renombramos en la bd
				$pathName = $this->FileManager->convertToPathname( $value["path_name"] );
				
				if (is_dir($pathName) )
					$res = $this->TrashModel->restoreFolderOfTrashInDB($pathName, replaceTrash($pathName));
				else{
					$name = $this->FileManager->getName(replaceTrash($pathName));
					$ext = $this->FileManager->getExtension($name);
					$res = $this->TrashModel->restoreFileOfTrashInDB($name, $ext, $this->getIdFile($pathName));
				} 

				if ($res) setMsg( "success","It was successfully restored in the Database." );
				else {setMsg( "error","An error occurred while restoring to the database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); continue;
				}
				
				//renombramos en el FileManager
				$res2 = $this->FileManager->rename( $pathName , replaceTrash($pathName) );

				if ($res2) setMsg( "success","Restored successfully in Archive Manager." );
				else setMsg( "error","An error occurred while restoring in Archive Manager.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );
			}
			print_r( json_encode(getMsg()));
			exit();
	    }

	    public  function deleteTrash($item) 
	    {   
			
			foreach ( $item as $key => $value) {
				//borramos en la bd
				$pathName = $this->FileManager->convertToPathname( $value["path_name"]  );
				
				if (is_dir($pathName) )
					$res = $this->TrashModel->deleteFolderInDB($pathName);
				else
					$res = $this->TrashModel->deleteFileInDB($this->getIdFile($pathName));	
				

				if ($res) setMsg( "success","It was deleted correctly in the Database." );
				else {setMsg( "error","An error occurred when deleting in the Database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );		continue;
				}
				//borramos en el FileManager
				$res2=$this->FileManager->delete( $value["path_name"] );

				if ($res2) setMsg( "success","It was successfully deleted in the File Manager." );
				else setMsg( "error","An error occurred while deleting in File Manager.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );
			}
			print_r( json_encode(getMsg()));
			exit();
	    }

	    public  function listAllTrash ($pathname) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
   			$this->FileManager->setPath($pathname);
   			$this->FileManager->hideExtension(['php']);
    		echo json_encode($this->FileManager->listAllTrash());
	   		exit();
	   		
	    }

	    public  function searchTrash ($pathname, $search) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
   			$this->FileManager->setPath($pathname);
   			$this->FileManager->hideExtension(['php']);
    		echo json_encode($this->FileManager->listSearch($search,true));
	   		exit();
	   		
	    }

	    public  function getValues() 
	    {   
    		return json_encode($this->FileManager->getValuesConfig()) ;
	    	
	    }

	}

 ?>

