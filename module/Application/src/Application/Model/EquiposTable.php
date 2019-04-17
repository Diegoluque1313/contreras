<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class EquiposTable{
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
			$select = new Select("equipo");
			$select->order(array("equipo_id ASC"));
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Equipo());
			
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
		$select->from("equipo");
		
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

	public function saveEquipo(Equipo $equipo){
		$data = array(
			"equipo_name" => $equipo->equipo_name,
			"equipo_logo" => $equipo->equipo_logo,
			"equipo_status" => $equipo->equipo_status,
		);
		
		$equipo_id = (int) $equipo->equipo_id;
		
		if($equipo_id==0){
			$return = $this->tableGateway->insert($data);
		}
		return $return;
	}

	public function deleteEquipo($id){
		$delete=$this->tableGateway->delete(array("equipo_id"=>(int) $id));
		return $delete;
	}
	
}