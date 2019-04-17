<?php

namespace Application\Model;

class Jugador{

	public $jugador_id, $equipo_id, $jugador_name, $jugador_lastname_pat, $jugador_lastname_mat, $jugador_number, $jugador_picture, $jugador_status;

	public function exchangeArray($data){
		$this->jugador_id = (!empty($data["jugador_id"])) ? $data["jugador_id"] : null ;
		$this->equipo_id = (!empty($data["equipo_id"])) ? $data["equipo_id"] : null ;
		$this->jugador_name = (!empty($data["jugador_name"])) ? $data["jugador_name"] : null ;
		$this->jugador_lastname_pat = (!empty($data["jugador_lastname_pat"])) ? $data["jugador_lastname_pat"] : null ;
		$this->jugador_lastname_mat	 = (!empty($data["jugador_lastname_mat"])) ? $data["jugador_lastname_mat"] : null ;
		$this->jugador_number	 = (!empty($data["jugador_number"])) ? $data["jugador_number"] : null ;
		$this->jugador_picture	 = (!empty($data["jugador_picture"])) ? $data["jugador_picture"] : null ;
		$this->jugador_status	 = (!empty($data["jugador_status"])) ? $data["jugador_status"] : null ;
	}
	
	public function getArrayCopy(){
		return get_object_vars($this);
	}
	
}