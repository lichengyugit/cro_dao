<?php
class UsertelModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_usertel'); 
        $this->log->log_debug('UsertelModel  model be initialized'); 
    }

    public function addUsertel($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }

    /**
     * [ 获取车辆单条信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getUsertelInfoByAttrs($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }
    




}
?>