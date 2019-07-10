<?php
class CityModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_city');
        $this->log->log_debug('CityModel  model be initialized');
    }
    
    
    
    /**
     * 根据类型获得所有城市
     */
    public function getAllCity($name){
        $where="";
        if($name){
            $where.=" AND CityName like '%".$name."%'";
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }
    
    /**
     * 根据ID获取城市信息
     */
    public function getCityById($CityID){
        $sql = " SELECT * FROM ".$this->tablename." WHERE CityID = ".$CityID;
        return $this->getCacheRowArray($sql);
    }
}
?>