<?php 
	/**
	* 
	*/
	class EditProfileController	{
		
		/*

		function __construct()
		{
		
		}
		*/
	
		public function updateProfile()
		{
			$EditProfileModel = new EditProfileModel();
	        $nameImg = $this->moveFile(ROOT_PATH."public/img/user/");
	    	
	    	$id = session("id_usuario");
	        $dataBD["nombre_completo"] = $_POST["name_c"];
	        $dataBD["pwd"] =  password_hash( $_POST["new_pwd"], PASSWORD_DEFAULT );
 			$dataBD["img"] = BASE_URL."public/img/user/".$nameImg;

	        if ($_POST["new_pwd"]=="default") {
	        	unset($dataBD["pwd"]);
	        }
	        if (!$_POST["img_changed"]) {
	        	unset($dataBD["img"]);
	        }

	        
	        if ( $EditProfileModel->updateProfile( $dataBD, $id) )
	        	href(BASE_URL."edit_profile?saved_profile");
	        else
	        	href(BASE_URL."edit_profile?not_saved_profile");
		}

		private  function moveFile ($moveFilePath) 
	    {   
	        #========================= validate if exist img ========================
	        foreach ($_FILES as $keys_name)
	        {
	           $filename = $keys_name["name"];
	           $filetype = $keys_name["type"];
	           $source = $keys_name["tmp_name"];
	           $n = $keys_name["error"];
	           if (!is_uploaded_file($source))
	               return "error: ".$n; 
	           if (!copy($source, $moveFilePath.$filename))
	               return "null"; 
	        }
	        #================================ end img ================================
	        return $filename; 
	    }


	    public  function getImg () 
	    {   
	   		$idUser = session("id_usuario");

	   		$EditProfileModel = new EditProfileModel();
	        $dataUser = $EditProfileModel->getUser($idUser);
	        $userImg = $dataUser->img;
	   	 	if ($userImg == null) 
                $img = BASE_URL."public/img/icon/user.png"; 
            else 
                $img = $userImg; 
	
	        return $img; 
	    }
	    public  function getName() 
	    {   
	   		$idUser = session("id_usuario");

	   		$EditProfileModel = new EditProfileModel();
	        $dataUser = $EditProfileModel->getUser($idUser);
	        $nameUser = $dataUser->nombre_completo;
	 
	        return $nameUser; 
	    }

	    public  function redirMyHome() 
	    {   
	   		 $type = session('data_user')["tipo"];
    		if ($type == 0) 
    			$redir = BASE_URL."admin"; 
    		else 
    			$redir = BASE_URL."home";
	 
	        return $redir; 
	    }
		
	}

 ?>