<?php
class BrandModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_brand');
        $this->log->log_debug('BrandModel  model be initialized');
    }
    
    
    
    /**
     * 根据类型获得所有用户
     */
    public function getAllBrand($status,$name){
        $where="";
        if($name){
            $where.=" AND brand_name like '%".$name."%'";
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE status = ?".$where." ORDER BY capital ASC";
        return $this->getCacheResultArray($sql,array($status));
    }
}
?>