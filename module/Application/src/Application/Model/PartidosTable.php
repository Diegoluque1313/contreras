<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PartidosTable{
	protected $tableGateway;
	protected $dbAdapter;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
		$this->dbAdapter = $tableGateway->adapter;
	}
	
	public function fetchAllOld(){
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function fetchAll($paginated=false){
		if($paginated){
			$select = new Select("partidos");
			$select->order(array("partido_id ASC"));
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Partido());
			
			$paginatorAdapter = new DbSelect(
						$select,
						$this->tableGateway->getAdapter(),
						$resultSetPrototype
					);
			
			$paginator = new Paginator($paginatorAdapter);
			
			return $paginator;
		}else{
			$resultSet = $this->tableGateway->select();
			return $resultSet;
		}
	}

	public function fetchDetail($paginated=false , $equipo_id){
		if($paginated){
			$select = new Select("jugador");
			$select->order(array("jugador_id ASC"));
			$select->where("equipo_id = ".$equipo_id."");
			//$select->where("equipo_id = 8");
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Jugador());
			
			$paginatorAdapter = new DbSelect(
						$select,
						$this->tableGateway->getAdapter(),
						$resultSetPrototype
					);
			
			$paginator = new Paginator($paginatorAdapter);
			
			return $paginator;
		}else{
			$resultSet = $this->tableGateway->select();
			return $resultSet;
		}
	}
	
	public function fetchAllSql(){
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from("jugador");
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$data=$statement->execute();
		
		$resultSet = new ResultSet();
		$data=$resultSet->initialize($data);
		
//		$query=$this->dbAdapter->query("SELECT * FROM usuarios",Adapter::QUERY_MODE_EXECUTE);
//		$data=$query->toArray();

		
//		$query=$this->dbAdapter->createStatement("SELECT * FROM usuarios");
//		$data = $query->execute();
		
		return $data;
	}

	public function saveJugador(Jugador $jugador){
		$data = array(
			"equipo_id" => $jugador->equipo_id,
			"jugador_name" => $jugador->jugador_name,
			"jugador_lastname_pat" => $jugador->jugador_lastname_pat,
			"jugador_lastname_mat" => $jugador->jugador_lastname_mat,
			"jugador_number" => $jugador->jugador_number,
			"jugador_picture" => $jugador->jugador_picture,
			"jugador_status" => $jugador->jugador_status,
		);
		
		$jugador_id = (int) $jugador->jugador_id;
		
		if($jugador_id==0){
			$return = $this->tableGateway->insert($data);
		}
		return $return;
	}

	public function deleteJugador($id){
		$delete=$this->tableGateway->delete(array("jugador_id"=>(int) $id));
		return $delete;
	}
	
}