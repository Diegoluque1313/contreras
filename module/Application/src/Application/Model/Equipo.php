<?php

namespace Application\Model;

class Equipo{

	public $equipo_id, $equipo_name, $equipo_logo, $equipo_status;

	public function exchangeArray($data){
		$this->equipo_id = (!empty($data["equipo_id"])) ? $data["equipo_id"] : null ;
		$this->equipo_name = (!empty($data["equipo_name"])) ? $data["equipo_name"] : null ;
		$this->equipo_logo = (!empty($data["equipo_logo"])) ? $data["equipo_logo"] : null ;
		$this->equipo_status = (!empty($data["equipo_status"])) ? $data["equipo_status"] : null ;
	}
	
	public function getArrayCopy(){
		return get_object_vars($this);
	}
	
}