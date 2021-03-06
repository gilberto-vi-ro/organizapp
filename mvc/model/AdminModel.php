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


}