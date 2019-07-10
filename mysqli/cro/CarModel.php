<?php
class CarModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_car'); 
        $this->log->log_debug('CarModel  model be initialized'); 
    }

    public function addCar($data){
        $data['createtime'] = time();
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }

    /**
     * [ 获取车辆单条信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getCarInfoByAttrs($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }
    
    public function updateCar($data,$where){
        $update=$this->update($data, $where);
        return $update;
    }

}
?>