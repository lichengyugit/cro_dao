<?php
class EquipmentModel extends DB_Model {
    protected $tables = array(
            //'role' => 'mssp_user.role' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_equipment');
        $this->log->log_debug('EquipmentModel  model be initialized');
    }
    
    /**
     * 根据属性获设备家信息
     */
    public function getEquipmentByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    public function updateEquipment($data,$wheres){
        $update=$this->update($data, $wheres);
        return $update;
    }
}
?>