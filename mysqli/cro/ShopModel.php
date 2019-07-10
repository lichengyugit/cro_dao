<?php
class ShopModel extends DB_Model {
    protected $tables = array();

    public function __construct() {
        parent::__construct($this->dbname, 'sx_shop');
        $this->log->log_debug('ShopModel  model be initialized');
        $this->tables['partner']=$this->dbname.'.sx_partner';
    }
    
    /**
     * 
     * 根据属性获取商家信息
     */
    public function getShopInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     * [getShopsInfoByAttr 获取多商家信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getShopsInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            if(is_array($v)){
                $v=implode(",", $v);
                $where.= " AND ".$k." in (".$v.")";
            }else{
               $where.= " AND ".$k." = '".$v."'";
            }
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * 验证商家名是否存在
     */
    public function checkShopName($name){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE name=? ";
        return $this->getCacheRowArray($sql,array(
                $name
        ))['c'];
    }
    
    /**
     * 
     * 根据类型获得所有商家
     */
    public function getAllShop($page, $pageSize,$role_flag){
        $sql = " SELECT * FROM ".$this->tablename." AS p WHERE p.status <2 AND p.role_flag=? ORDER BY id ASC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array(
                $role_flag,
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }

    /**
     * 添加商家
     * 根据店铺名获取所有店铺
     */
    public function getSearchShop($status,$name){
        $where="";
        if($name){
            $where.=" AND name like '%".$name."%'";
        }
        
        $sql = " SELECT * FROM ".$this->tablename." WHERE status = ?".$where." ORDER BY id DESC";
        return $this->getCacheResultArray($sql,array($status));
    }
 
    /**
     * 添加店铺
     */
    public function addShop($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 
     * 根据ID修改店铺信息
     */
    public function updateShop($data){
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
     * 
     * 根据条件修改商家信息
     */
    public function updateShopByAttr($data,$where){
        $update=$this->update($data, $where);
        if($update){
            return true;
        }
        else{
            return false;
        }
    }

     /**
     * 
     * 根据属性获取合伙人信息
     */
    public function getPartnerInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tables['partner']." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     *  钱包余额变动
     */
    public function updateBalance($parames,$id)
    {
        $set ="";
        foreach($parames as $k=>$v){
            $set.= $k." = ".$v.", ";
        }
        $set = trim($set, ", ");
        $sql = "UPDATE ".$this->tablename." SET ".$set." WHERE id= ".$id;
        $this->read_db->query($sql);
        return $this->read_db->affected_rows();
    }
}
?>