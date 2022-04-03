<?php 


class EditProfileModel extends DB
{
	

	public function updateProfile($dataUser, $id){
		try {

			$where = "id_usuario = '$id'";
			$this->update("usuario", $dataUser , $where );
			/*=====================error=========================*/
			/*if (!$this->response){
				print_r($this); exit();
			}*/
			/*=====================return=========================*/
			return $this->response;
			
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

}