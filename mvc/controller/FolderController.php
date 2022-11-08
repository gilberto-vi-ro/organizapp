<?php 
	/**
	* 
	*/
	class FolderController
	{
		private  $FileManager;
		private  $FolderModel;
		private  $idUser;

		function __construct()
		{   
			$this->FolderModel = new FolderModel();
			$this->FileManager = new FileManager($this->getPathDefault());
			$this->FileManager->hideExtension(['php','trash']);
			$this->idUser=session('id_usuario');
		}



	    public  function getImg () 
	    {   
	   		$idUser = $this->idUser;

	        $dataUser = $this->FolderModel->getUser($idUser);
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

	        $dataUser = $this->FolderModel->getUser($idUser);
	        $nameUser = $dataUser->nombre_completo;
	 
	        return $nameUser; 
	    }

	    public  function getPathDefault() 
	    {   
	   		$idUser = $this->idUser;
	   		$dp = $this->FolderModel->getPathDefault($idUser);
	   	
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

	    public  function showFile($pathname, $extension) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}

	    	$html = "null";
			$img =['png', 'jpg','gif','jpeg','bmp','tif'];
			$music =['mp3','ogg','m4a','wav','wma','aiff','au','mid'];
			$video =['mp4','webm','mov','wmv','avi','wmv','flv','mkv','ts'];
			$txt =['txt','html','css','js','php','sql'];

	    	if (in_array($extension,$img))
	    		$html = '<img src="'.$pathname.'" width="100%">'; 
	    	else if ($extension=='pdf')
				$html = '<embed src="'.$pathname.'#toolbar-0&navpanes=0&scrollbar=0"  type="application/pdf" width="800px" height="600px" />';
				//$html = ' <a  target="_blank" href="'.$pathname.'">ver pdf</a> ';
			else if ($extension=='movil-pdf')
				//$html = '<embed src="'.$pathname.' #toolbar-0&navpanes=0&scrollbar=0"  type="application/pdf" width="100%" height="400px" />';
				$html = '<iframe src="'.$pathname.'" width="100%" height="600px"></iframe> ';
			else if (in_array($extension,$music))
	    		$html = '<audio controls autoplay src="'.$pathname.'" width="100%"></audio>';
			else if (in_array($extension,$video))
	    		$html = '<video controls width="100%" autoplay >
							<source src="'.$pathname.'" type="video/'.$extension.'">
						</video>';
			else if (in_array($extension,$txt)) {
				$text = file_get_contents($pathname); 
				$text = nl2br($text); 
				$html =  '<p style="text-align: justify;"> '.$text.' </p>';
			}
	    	else
	    		$html = "File not supported.";
	    			
	    	echo ($html);

	        exit();
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
			$idFolder = $this->FolderModel->getIdFolder( $this->FileManager->convertToPathname($pathFolder) , $this->idUser);
			$oldName = $this->FileManager->getName( $oldPathname);
			$oldExt = $this->FileManager->getExtension($oldName);
			$idFile = $this->FolderModel->getIdFile($oldName,$oldExt,$idFolder);
			return $idFile;
		}
		public  function upload($pathname, $_FILE_) 
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
			$idFolder = $this->FolderModel->getIdFolder($pathname, $this->idUser);
			$res = $this->FolderModel->reInsertFileInDB( $file, $idFolder );
			if ($res) {
				setMsg( "success","It was saved correctly in the Database." );
				//agregamos en FileManager
				$res2 = $this->FileManager->uploadFile( $_FILE_ );
				if ($res2) setMsg( "success","Successfully uploaded to Archive Manager." );
				else setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
			}
			else setMsg( "error","An error occurred when saving to the database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );
			
			print_r( json_encode(getMsg()) );
	        exit();
	    }

		public  function download($pathname, $isDir) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
		
	   		if ($isDir){
	   			echo "Please wait while we zip the folder...";
				   $file['name']= $this->FileManager->getName($pathname).".zip";
				   $file['size']=0;
				   $file['extension']="zip";
				   $myPathname = $this->FileManager->getPath($pathname);
				   $idFolder = $this->FolderModel->getIdFolder(substr($myPathname,0,-1), $this->idUser);
				   $res = $this->FolderModel->reInsertFileInDB( $file, $idFolder );
				   if ($res) echo"It was saved correctly in the Database";
				   else echo "An error occurred when saving to the Database";
	   			$this->FileManager->downloadFolder($pathname);
	   		}
	   		else{
	   			echo "Please wait while we prepare the file...";
	   			$this->FileManager->downloadFile($pathname);
	   		}
	        exit();
	    }

	    public  function createFolder($pathname, $name) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}

			$data['path'] = $this->FileManager->convertToPath($pathname);
			$data['name'] = $name;
			$data['id_user'] = $this->idUser;
			$res = $this->FolderModel->createFolderInDB($data);

			if($res==2) {setMsg( "error","The folder already exists in the Database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );}
			else if($res==0) {setMsg( "error","An error occurred when creating in the Database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );}
			else{
				setMsg( "success","The folder was created in the Database.") ;
				$this->FileManager->setPath($pathname);
				$res2 = $this->FileManager->createDir($name);
				if ($res2) setMsg( "success","The folder was created in the File Manager." );
				else setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
			}
			print_r( json_encode(getMsg()) );
	        exit();
	    }

	    public  function rename($oldPathname, $newname) 
	    {   
			
			//renombramos en la bd
			$path = $this->FileManager->getPath($oldPathname);
			$newPathname = $path.$newname;
			if( is_dir($oldPathname) ) 
				$res = $this->FolderModel->renameFolderInDB($oldPathname, $newPathname);
			else {
				$idFolder = $this->FolderModel->getIdFolder( $this->FileManager->convertToPathname($path) , $this->idUser);
				$newExt = $this->FileManager->getExtension($newname);
				$res = $this->FolderModel->renameFileInDB($idFolder, $newname, $newExt, $this->getIdFile($oldPathname));
			}

			if ($res === 2) setMsg( "error","The name already exists in the Database.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
			else if ($res) {
				setMsg( "success","It was renamed correctly in the Database." );
				//renombramos en el FileManager
				$res2 = $this->FileManager->rename($oldPathname, $newPathname);

				if ($res2) setMsg( "success","It was successfully renamed in Archive Manager." );
				else setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
			}
			else setMsg( "error","An error occurred when renaming in the Database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );

			print_r( json_encode(getMsg()) );
			exit();
	    }

	    public  function move($_newPathname, $item) 
	    {   
			
	   		foreach ( $item as $key => $value) {
				//renombramos en la bd
				$oldPathname = $this->FileManager->convertToPathname($value["path_name"]);
				$newPathname = $this->FileManager->convertToPath($_newPathname).$value["name"] ;
				
				if ( is_dir($oldPathname) )
					$res = $this->FolderModel->updatePathFolderInDB($oldPathname, $newPathname);
				else
					$res = $this->FolderModel->updatePathFileInDB( $this->getIdFile($oldPathname), $this->FileManager->convertToPathname($_newPathname) );
				

				if ($res) setMsg( "success","It was moved correctly in the Database." );
				else {setMsg( "error","An error occurred when moving in the Database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );		continue;
				}
				//renombramos en el FileManager
				$res2 = $this->FileManager->move( $oldPathname , $newPathname );
				if ($res2) setMsg( "success","Moved successfully in Archive Manager." );
				else setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
			}
			print_r( json_encode(getMsg()) );
			exit();
	    }

	    public  function delete( $item) 
	    {  
			foreach ( $item as $key => $value) {
				//renombramos en la bd
				$pathName = $this->FileManager->convertToPathname($value["path_name"]);
				
				if (is_dir($pathName) )
					$res=$this->FolderModel->updateFolderToTrashInDB($pathName );
				else{
					$name = $this->FileManager->getName($pathName);
					//$ext = $this->FileManager->getExtension($name);
					$res = $this->FolderModel->updateFileToTrashInDB($name.".trash", "trash", $this->getIdFile($pathName));
				} 
				if ($res) setMsg( "success","It was deleted correctly in the Database." );
				else {setMsg( "error","An error occurred when deleting in the Database.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );	continue;
				}
				
				//renombramos en el FileManager
				$res2 = $this->FileManager->rename( $pathName, $pathName.".trash" );

				if ($res2) setMsg( "success","Deleted successfully in Archive Manager." );
				else setMsg( "error",$this->FileManager->getMsg("msg"), $this->FileManager->getMsg("where") ,$this->FileManager->getMsg("line") );
			}
			print_r( json_encode(getMsg()) );
			exit();
	    }


	

	    public  function listAll ($pathname) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
   			$this->FileManager->setPath($pathname);
    		echo json_encode($this->FileManager->listAll());
	   		exit();
	   		
	    }

	    public  function search ($pathname, $search) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
   			$this->FileManager->setPath($pathname);
   			$this->FileManager->hideExtension(['php','trash']);
    		echo json_encode($this->FileManager->listSearch($search));
	   		exit();
	   		
	    }

	    public  function getValues() 
	    {   
    		return json_encode($this->FileManager->getValuesConfig()) ;
	    	
	    }



	}
