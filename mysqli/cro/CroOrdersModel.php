<?php
class CroOrdersModel extends DB_Model {
    protected $tables = array();

    public function __construct() {
        parent::__construct($this->dbname, 'sx_order');
        $this->log->log_debug('CroOrdersModel  model be initialized');
        $this->tables['car_insure']=$this->dbname.'.sx_car_insure';
        // $this->tables['shop']=$this->dbname.'.sx_shop';
        // $this->tables['user_wallet_log']=$this->dbname.'.sx_user_wallet_log';
    }
 
    /**
     * 获得单条订单信息
     */
    public function getOrderInfoById($id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE id = ? ";
        return $this->getCacheRowArray($sql,array($id));
    }

    /**
     * [getDqInsureOrderInfo 获取用户盗抢险订单(未激活已付款)]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getDqInsureOrderInfo($parames)
    {
        $sql = " SELECT od.`id` as order_id,`car_num`, `car_name`,`usertel_name`,`receiver_address`,ci.`status` as insure_status
        FROM 
        ".$this->tablename." as od,
        ".$this->tables['car_insure']." as ci
        WHERE od.`carinid`=ci.`id` AND ci.`insure_id`=? AND od.`pay_status`=? AND ci.`status`=? AND od.`user_id`=?";
        return $this->getCacheResultArray($sql,array($parames['insure_id'],$parames['pay_status'],$parames['status'],$parames['user_id']));
    }

}
?>
