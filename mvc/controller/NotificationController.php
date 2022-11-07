<?php 
	/**
	* 
	*/
	class NotificationController
	{
		private $NotificationModel;
		private $idUser;

		function __construct()
		{   
			$this->NotificationModel = new NotificationModel();
			$this->idUser = session("id_usuario");
		}



	    public  function getImg () 
	    {   
	   		$idUser = $this->idUser;

	        $dataUser = $this->NotificationModel->getUser($idUser);
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

	        $dataUser = $this->NotificationModel->getUser($idUser);
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
	   		$dp = $this->NotificationModel->getPathDefault($idUser);
	   	
	   		$path = ($dp)? $dp  : "drive/".$idUser ;
	        return $path;  
	    }

		public  function createNotification() 
	    {   
			
			$idMsg = 0;$idTask = 0;
	   		$data = $this->NotificationModel->getTaskExpired();
			if ($data!=null){
				foreach ($data as $task){
					
					if (date($task->fecha_entrega) < now("Y-m-d"))
						$idMsg = 1000;
					else if (date($task->fecha_entrega) == now("Y-m-d"))
						$idMsg = 1001;
					else if (date($task->fecha_entrega) == tomorrow("Y-m-d"))
						$idMsg = 1002;
					$res = $this->NotificationModel->insertNotification( $task->id_tarea, $idMsg );
					if ($res == 2 ) echo '';
					else if ($res) {
						setMsg( "success","La notificacion se inserto en la BD." ); 
					}else{
						setMsg( "error","ocurrio un error al insertar notificacion en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
					}
				}
			}
			
			//print_r( json_encode(getMsg()) );
			//exit();
	    }

		public static function sendNotificationEmail() 
	    {   
			$res = NotificationModel::getNotificationForEmail();
			
			if ($res) {
				foreach ($res as $key => $value) {
					self::sendMail($value); 
					echo "<br>";
				}
			}else{
				setMsg( "error","No hay notificacion por enviar.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
			}
				
			echo ' <button style="
			background-color: #3B86E8;
			outline: none;
			border: none;
			cursor: pointer;
			min-height: 35px;
			font-size: 17px;
			/* font-weight: 700; */
			border-radius: 30px;
			transition: .5s;
			color: #E8F5FB;
			padding-left: 20px;
			padding-right: 20px;
			margin: 5px;
			position:fixed;
			top:0;
			">
				<a type="button" href="'.BASE_URL.'admin" style="text-decoration: none;color: #E8F5FB;"> Volver </a>
			</button>';
			print_r( json_encode(getMsg()) );
			exit();
	    }

		private static function sendMail($data){
			$to = $data["email"]; 
			$subject = "OrganizApp"; 
			$body = ' 
				<html> 
				<head> 
				<title>OrganizApp</title> 
				</head> 
				<body> 
				<h1>OranizApp</h1> 
				<h2><p>
						'.$data["mensaje"].', <strong> nombre: </strong> '.$data["nombre"].',
						<strong>ruta: </strong>'.$data["path_name"].'
					</p>
				</h2> 
				<br>
				<p> 
					<b>Puedes acceder desde el siguiente link, primero inicia sesion :</b>
					<a href="https://myproyecto.com/organizapp/home#'.$data["path_name"].'" >https://myproyecto.com/organizapp/home#'.$data["path_name"].'</a>
				</p> 
				<br>
				<p> No responda a este email ya que fue generado automaticamente.</p> 
				</body> 
				</html> 
			'; 
			// Para enviar un correo HTML, debe establecerse la cabecera Content-type
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			if(mail($to,$subject,$body,$headers)){
				NotificationModel::updateNotificationSentEmail($data["id_notificacion"]);
				echo 'We have sent you a message to '.$to.' with subject "OrganizApp"';
			}
			else 
				echo 'An error occurred while sending the Email';

		}

		public  function seenNotification($idNotification) 
	    {   
			$res = $this->NotificationModel->seenNotification( $idNotification );
			
			if ($res) {
				setMsg( "success","La notificacion ha sido vista en la BD." ); 
			}else{
				setMsg( "error","ocurrio un error al ver notificacion en la BD.",  __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() ); 
			}
				
			print_r( json_encode(getMsg()) );
			exit();
	    }

		public  function countNotification() 
	    {   
			$this->createNotification();
			$res = $this->NotificationModel->countNotification( $this->idUser);
			echo $res;
			exit();
	    }

		public  function deleteNotification($item) 
	    {   
			foreach ( $item as $key) {
				//borramos en la bd
				$res = $this->NotificationModel->deleteNotification($key->id_notificacion);
				
				if ($res) setMsg( "success","Se elimino correctamente en la BD." );
				else {setMsg( "error","ocurrio un error al eliminar en la BD.", __CLASS__."->".__FUNCTION__ , (new Exception(""))->getLine() );
				}
			}
			print_r( json_encode(getMsg()));
			exit();
	    }

		public  function listAllNotification($range) 
	    {   
	   		
	   		$res = $this->NotificationModel->getNotification($this->idUser,$range);
			$list = [];
   			$result = [];
   			if ($res)
   				$list = [ 'success' => true, 'results' => $res ];
   			else 
   				$list = [ 'success' => true, 'results' => $result ];
    		echo json_encode($list);
	   		exit(); 
	    }

    }

 ?>