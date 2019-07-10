<?php
/**
 * 订单申诉
 */
class OrderAppealModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_xiu_order_appeal'); 
        $this->log->log_debug('OrderAppealModel  model be initialized'); 
    }
    
    /**
     * 根据属性获取单用户信息
     */
    public function getOrderAppealInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }




    /**
     * [getOrderAppealsInfoByAttr 获取多用户信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getOrderAppealsInfoByAttr($parames){
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

    public function checkAppealByorderid($order_id){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE order_id = ? ";
        return $this->getCacheRowArray($sql,array(
                $order_id
        ))['c'];
    }
    

    /**
     * 根据类型获得所有用户
     */
    public function getAllOrderAppeal($page, $pageSize,$role_flag){
        $sql = " SELECT * FROM ".$this->tablename." AS p WHERE p.status <2 AND p.role_flag=? ORDER BY id ASC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array(
                $role_flag,
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }
    

    
    /**
     * 添加用户
     */
    public function addOrderAppeal($data){
        $data['create_time']=time();
        $data['create_date']=date('Y-m-d,H:i:s',$data['create_time']);
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 根据ID修改用户信息
     */
    public function updateOrderAppeal($data){
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
     * 修改用户信息
     */
    public function updateOrderAppealByAttr($data,$wheres){
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