<?php
class FixShopModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_fix_shop'); 
        $this->log->log_debug('FixShopModel  model be initialized'); 
    }

    public function getFixShopInfoById($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     * 根据条件获取所有申请
     */
    public function getAllFixShop($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }
    /**
     * 新增绑定申请
     */
    public function addFixShop($data){
        $data['create_time'] = time();
        $data['create_date'] = date('Y-m-d H:i:s');
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }

    /**
     * [updateFixShop 更新fixshop申请绑定信息]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateFixShop($data){
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return $update;
        }
        else{
            return false;
        }
    }
}
?>