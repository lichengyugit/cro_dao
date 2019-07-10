<?php
class CarModelModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_car_model');
        $this->log->log_debug('CarModelModel  model be initialized');
    }
    
    
    
    /**
     * 根据类型获得所有用户
     */
    public function getAllCarModel($status,$brand_id,$name){
        $where="";
        if($name){
            $where.=" AND car_model_name like '%".$name."%'";
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE status = ? AND brand_id = ?";
        return $this->getCacheResultArray($sql,array($status,$brand_id));
    }
}
?>