<?php 
	/**
	* 
	*/
	class ViewFileController
	{
		private  $FileManager;
		private  $ViewFileModel;

		function __construct()
		{   
			$this->FileManager = new FileManager($this->getPathDefault());
			$this->FileManager->hideExtension(['php','trash']);
		}

		static public  function decodePathName() 
	    {   
			$data = base64_decode($_GET["v"]);
			$pathname = base64_decode($data);
			
        	session('default_path',$pathname);
	    }
		
	    public  function getPathDefault() 
	    {   
			return session('default_path');
	    }

		private  function pathIgnored($path) 
	    {   

			$search =  $this->getPathDefault() ;
			
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
	    		$html = "Archivo no soportado.";
	    			
	    	echo ($html);

	        exit();
	    }


	    /*====================================================
		FILE MANAGER
		====================================================*/

		public  function download($pathname, $isDir) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
	   		if ($isDir){
	   			echo "Espere mientras zipiamos la carpeta...";
	   			$this->FileManager->downloadFolder($pathname);
	   		}
	   		else{
	   			echo "Espere mientras preparomos el archivo...";
	   			$this->FileManager->downloadFile($pathname);
	   		}
	        exit();
	    }

		
	    public  function listAll ($pathname) 
	    {   
	    	if ( $this->pathIgnored($pathname) ) {
	    		$pathname = $this->getPathDefault();
	    	}
			if (is_dir(session('default_path'))){
				$this->FileManager->setPath($pathname);
				echo json_encode($this->FileManager->listAll());
				
			}else{
				$path = $this->FileManager->getPath($pathname);
				$name = $this->FileManager->getName($pathname);
				$this->FileManager->setPath($path);
    			echo json_encode($this->FileManager->listSearch($name));
			}
			exit();
	    }
		
	    public  function search ($path, $search) 
	    {   
	    	if ( $this->pathIgnored($path) ) {
	    		$path = $this->getPathDefault();
	    	}
   			$this->FileManager->setPath($path);
   			$this->FileManager->hideExtension(['php','trash']);
    		echo json_encode($this->FileManager->listSearch($search));
	   		exit();
	   		
	    }

	    public  function getValues() 
	    {   
    		return json_encode($this->FileManager->getValuesConfig()) ;
	    	
	    }



	}
