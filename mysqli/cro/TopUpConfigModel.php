<?php
class TopUpConfigModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_top_up_config'); 
        $this->log->log_debug('TopUpConfigModel  model be initialized'); 
    }

    public function getTopUpInfoById($id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE id=".$id;
        return $this->getCacheRowArray($sql);
    }

    /**
     * 根据条件获取所有提现
     */
    public function getAllTopUpConfig($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }
    /**
     * 新增充值记录
     */
    public function addTopUpConfig($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
}
?>