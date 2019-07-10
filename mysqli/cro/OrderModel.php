<?php
/**
 * CRO订单
 */
class OrderModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_order'); 
        $this->log->log_debug('OrderModel  model be initialized'); 
    }

    public function addOrder($data){
        $data['createtime'] = time();
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }

    /**
     * [ 获取订单单条信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getOrderInfoByIds($parames){
        $where="";
        foreach ($parames as $k=>$v){
            // $where.= " AND '".$k."' = '".$v."'";
            $where.= " AND `".$k."` = '".$v."'";
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }
    


    /**
     * [updateOrderById  根据id修改订单]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateOrderById($data)
    {
        $data['updatetime']=time();
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return $update;
        }
        else{
            return false;
        }
    }
    
    public function updateOrderByAttr($data,$where){
        $update=$this->update($data, $where);
        return $update;
    }



}
?>