<?php 


class LoginModel extends DB
{
	

	public function userExist($_post){
		try {

			$this->prepare("SELECT id_usuario, email, pwd, tipo FROM usuario WHERE email=? ");
			$this->bindParam(1,$_post['email']);
			$this->execute();
			if ($this->rowCount()>0)
				return $this->fetchAll(PDO::FETCH_ASSOC)[0];
			else
				return false;
			
		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	
	}

	public function register($_post){
		try {

			$dataUser['email'] = $_post['email'];
			/*=====================exists usuario=========================*/
			if( $this->existsData("usuario",$dataUser)->response )
				return 2;

			$this->beginTransaction();
			/*=====================insert usuario=========================*/
			$dataUser["nombre_completo"] = $_post["nombre_c"];
			$dataUser["pwd"]  = $_post["pwd"];
			$dataUser["tipo"] = 1;
			$this->insert("usuario",$dataUser);

			/*=====================insert carpeta=========================*/
			$idUser = $this->getMaxUser();
			$dataFolder["path"] = "drive/" ;
			$dataFolder["path_name"] = "drive/".$idUser ;
			$dataFolder["nombre"] = $idUser;
			$dataFolder["descripcion"] = "carpeta raiz";
			$dataFolder["raiz"] = 1;
			$dataFolder["id_usuario"] = $idUser;
			$this->insert("carpeta",$dataFolder);

			/*=====================insert pago=========================*/
			$dataPay["monto"] = "0" ;
			$dataPay["metodo_pago"] = "default" ;
			$dataPay["status"] = "completado";
			$dataPay["descripcion"] = "pago de prueba";
			$dataPay["id_usuario"] = $idUser;
			$this->insert("pago",$dataPay);

			/*=====================insert licencia=========================*/
			$idPay = $this->getMaxId('id_pago' , 'pago');
			$dataLicense["codigo_licencia"] = generatePassword(20);
			$dataLicense["fecha_ini"] = now() ;
			$dataLicense["fecha_fin"] = date("Y-m-d H:i:s",strtotime(now()."+ 15 days"));
			//$dataLicense["fecha_fin"] = date("Y-m-d H:i:s",strtotime(now()."+ 4 months"));
			$dataLicense["id_admin"] = "1000";
			$dataLicense["id_pago"] = $idPay;
			$this->insert("licencia",$dataLicense);
		
			/*======================transaction========================*/
			if ($this->response)
				$this->commit();
			else
				$this->rollback();

			/*=====================error=========================*/
			/*if (!$this->response){
				print_r($this->error); exit();
			}*/
			/*=====================return=========================*/
			return $this->response;
			
		
			
		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	}

	public function setLastTime($idUser){
		try {
			$dataUpdate['fecha_ultima_vez'] = now();
			$where = "id_usuario='$idUser'";
			$this->update("usuario", $dataUpdate, $where);

			if ($this->response)
				return true;
			else
				return false;

		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	}

	public function getMaxUser(){
		try {
			return $this->getMaxId( 'id_usuario' , 'usuario');
		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	}

}