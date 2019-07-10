<?php
class CarInsureModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_car_insure'); 
        $this->log->log_debug('CarInsureModel  model be initialized'); 
    }

    public function addCarInsure($data){
        $data['createtime'] = time();
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }

    /**
     * [ 获取订单单条信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getCarInsuresInfoByIds($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    public function updateCarInsureByAttr($data,$where){
        $update=$this->update($data, $where);
        return $update;
    }


}
?>