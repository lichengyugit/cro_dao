<?php
/**
 * 修哥绑定解绑商铺流失表
 */
class FixShopLogModel extends DB_Model {
    protected $tables = array(
    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_fix_shop_log'); 
        $this->log->log_debug('FixShopLogModel  model be initialized'); 
    }

    /**
     * 验证修哥绑定流水信息是否存在
     */
    public function checkFixShopLog($fix_id,$type,$status)
    {
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE fix_id=? AND type =? AND status =?";
        return $this->getCacheRowArray($sql,array(
                $fix_id,
                $type,
                $status
        ))['c'];
    }
    
    /**
     * 添加修哥绑定解绑流水信息
     */
    public function addFixShopLog($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 根据ID修改修哥绑定解绑流水信息信息
     */
    public function updateFixShopLog($data){
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return $update;
        }
        else{
            return false;
        }
    }
    
    /**
     * 修改修哥绑定解绑流水信息信息
     */
    public function updateFixShopLogByAttr($data,$wheres){
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