<?php 
	/**
	* 
	*/
	class AdminController	{
		
		private $AdminModel;

		function __construct()
		{
			$this->AdminModel = new AdminModel();
		}
		
	
		public function listUsers()
		{
			
			return $mydata = $this->AdminModel->listUsers();
	       
		}

		public function deleteUser($id)
		{
			$AdminModel = new AdminModel();
			
			if ( $this->isAdmin($id) )
				 href(BASE_URL."admin?is_admin");
			

			if ($AdminModel->deleteUser($id)>0) {
				$FileManager = new FileManager("drive");
				$FileManager->delete( "drive/".$id."/" );
				 href(BASE_URL."admin?user_dlt");
			}
			else
				 href(BASE_URL."admin?no_user_dlt");
		}

		private function isAdmin($id)
		{
		
			$obj = $this->AdminModel->getType($id);

	      	if ( $obj->tipo == 0)
	      		return true;
	      	else
	      		return false;
	      	
		}

		 public  function getImg () 
	    {   
	   		$idUser = session("id_usuario");

	        $dataUser = $this->AdminModel->getUser($idUser);
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

	   		
	        $dataUser = $this->AdminModel->getUser($idUser);
	        $nameUser = $dataUser->nombre_completo;
	 
	        return $nameUser; 
	    }

		
	}

 ?>