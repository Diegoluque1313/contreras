<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UsuariosTable{
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
			$select = new Select("usuarios");
			$select->order(array("id DESC"));
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Usuario());
			
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
		$select->from("usuarios");
		
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
	
	public function getUsuario($id){
		$id = (int) $id;
		
		$rowset = $this->tableGateway->select(array("id"=>$id));
		$row = $rowset->current();
		
		return $row;
	}
	
	public function getUsuarioByEmail($email){		
		$rowset = $this->tableGateway->select(array("email"=>$email));
		$row = $rowset->current();
		
		return $row;
	}
	
	public function saveUsuario(Usuario $usuario){
		$data = array(
			"name" => $usuario->name,
			"surname" => $usuario->surname,
			"description" => $usuario->description,
			"email" => $usuario->email,
			"password" => $usuario->password,
			"image" => $usuario->image,
			"alternative" => $usuario->alternative
		);
		
		$id = (int) $usuario->id;
		
		if($id==0){
			$return = $this->tableGateway->insert($data);
		}else{
			if($this->getUsuario($id)){
				$return = $this->tableGateway->update($data,array("id" => $id));
				
			}else{
				throw new \Exception("El usuario no existe");
			}
		}
		return $return;
	}
	
public function updateUsuario(Usuario $usuario){
	$data = array(
			"name" => $usuario->name,
			"surname" => $usuario->surname,
			"description" => $usuario->description,
			"email" => $usuario->email,
			"password" => $usuario->password,
			"image" => $usuario->image,
			"alternative" => $usuario->alternative
		);
		
		$id = (int) $usuario->id;
	$return = $this->tableGateway->update($data, array('id' => $id));
	return $return;
}
	
	public function deleteUsuario($id){
		$delete=$this->tableGateway->delete(array("id"=>(int) $id));
		return $delete;
	}
}