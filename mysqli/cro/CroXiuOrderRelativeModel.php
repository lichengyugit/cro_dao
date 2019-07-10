<?php
/**
 * cro保险订单和修铺订单关系表
 */
class CroXiuOrderRelativeModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_xiu_cro_order'); 
        $this->log->log_debug('CroXiuOrderRelativeModel  model be initialized'); 
    }
    
    /**
     * 根据属性获取单信息
     */
    public function getCXRelativeByAttr($parames)
    {
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     * 添加新新数据
     */
    public function addCXRelative($data)
    {
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }


    public function updateCXRelative($data)
    {
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return true;
        }
        else{
            return false;
        }
    }


}
?>