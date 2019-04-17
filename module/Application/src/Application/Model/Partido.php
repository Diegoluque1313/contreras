<?php

namespace Application\Model;

class Partido{

	public $partido_id, $equipo_id_1, $equipo_id_2, $partido_marcador, $partido_jornada, $partido_campo, $partido_status;

	public function exchangeArray($data){
		$this->partido_id = (!empty($data["partido_id"])) ? $data["partido_id"] : null ;
		$this->equipo_id_1 = (!empty($data["equipo_id_1"])) ? $data["equipo_id_1"] : null ;
		$this->equipo_id_2 = (!empty($data["equipo_id_2"])) ? $data["equipo_id_2"] : null ;
		$this->partido_marcador = (!empty($data["partido_marcador"])) ? $data["partido_marcador"] : null ;
		$this->partido_jornada	 = (!empty($data["partido_jornada"])) ? $data["partido_jornada"] : null ;
		$this->partido_campo	 = (!empty($data["partido_campo"])) ? $data["partido_campo"] : null ;
		$this->partido_status	 = (!empty($data["partido_status"])) ? $data["partido_status"] : null ;
	}
	
	public function getArrayCopy(){
		return get_object_vars($this);
	}
	
}