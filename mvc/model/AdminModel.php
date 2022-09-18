<?php 


class AdminModel extends DB
{
	

	public function listUsers(){
		try {

			$this->prepare("SELECT * FROM usuario
							INNER JOIN carpeta
							ON 
							usuario.id_usuario = carpeta.id_usuario
							WHERE raiz=true; ");
			$this->execute();
			if ($this->rowCount()>0)
				return $this->fetchAll(PDO::FETCH_OBJ);
			else
				return false;
			
		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	
	}

	public function getUser($id){
		try {

			$this->prepare("SELECT nombre_completo, img FROM usuario WHERE id_usuario=$id ");
			$this->execute();
			if ($this->rowCount()>0)
				return $this->fetchAll(PDO::FETCH_OBJ)[0];
			else
				return false;
			
			
		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	
	}

	public function getType($id){
		try {

			$this->prepare("SELECT tipo FROM usuario WHERE id_usuario=$id ");
			$this->execute();
			if ($this->rowCount()>0)
				return $this->fetchAll(PDO::FETCH_OBJ)[0];
			else
				return false;
			
			
		} catch (PDOException $e) {
			echo $e->getMessage(); 
			exit();
		}
	
	}

	public function deleteUser($id){
			
		
    	$where="id_usuario = '".$id."'";
    	$object=$this->delete("usuario",$where);
    	//print_r($object);
    	//exit();
		return $object->response;

	
	}

	
	public function insertPayAndLicense(){
		try {

			$dataCol['codigo_licencia'] = $_POST['cod_license'];
			/*=====================is active license=========================*/
			$this->prepare("SELECT codigo_licencia, fecha_fin FROM licencia
					INNER JOIN pago on pago.id_pago = licencia.id_licencia
					INNER JOIN usuario on usuario.id_usuario = pago.id_usuario
					WHERE fecha_fin > ? AND usuario.id_usuario = ?
					");
			$this->execute(array( tomorrow(), $_POST['id_user'] ));
			if ($this->rowCount()>0)
				return 2;

			$this->beginTransaction();

			/*=====================insert pago=========================*/
			$dataPay["monto"] = $_POST['pay_amount'] ;
			$dataPay["metodo_pago"] = $_POST['pay_method'] ;
			$dataPay["status"] = $_POST['pay_status'];
			$dataPay["descripcion"] = $_POST['pay_description'];
			$dataPay["id_usuario"] = $_POST['id_user'];
			$this->insert("pago",$dataPay);

			/*=====================insert licencia=========================*/
			$idPay = $this->getMaxId('id_pago' , 'pago');
			$dataLicense["codigo_licencia"] = $_POST['cod_license'];
			$dataLicense["fecha_ini"] = now() ;
			$dataLicense["fecha_fin"] = $_POST['date_end_license'];
			$dataLicense["id_admin"] = session("id_usuario");
			$dataLicense["id_pago"] = $idPay;
			$this->insert("licencia",$dataLicense);
		
			/*======================transaction========================*/
			if ($this->response)
				$this->commit();
			else{
				$this->rollback();
				print_r($this->error); exit();
			}
				
			
			/*=====================return=========================*/
			return true;
			
		} catch (PDOException $e) {
			$this->rollback();
			echo $e->getMessage(); 
			exit();
		}
	}


}