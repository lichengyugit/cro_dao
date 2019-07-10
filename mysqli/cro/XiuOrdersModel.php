<?php
class XiuOrdersModel extends DB_Model {
    protected $tables = array();

    public function __construct() {
        parent::__construct($this->dbname, 'sx_xiu_orders');
        $this->log->log_debug('XiuOrdersModel  model be initialized');
        $this->tables['user_wallet']=$this->dbname.'.sx_user_wallet';
        $this->tables['shop']=$this->dbname.'.sx_shop';
        $this->tables['user_wallet_log']=$this->dbname.'.sx_user_wallet_log';
    }
    
    /**
     * 修改订单信息
     */
    public function updateOrderAttr($data){
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return true;
        }
        else{
            return false;
        }
    }

    public function updateOrderAttrs($data,$wheres){
        $update=$this->update($data, $wheres);
        return $update;
    }
    //修改
    public function updateOrderByAttr($data,$where){
        $update=$this->update($data, $where);
        return $update;
    }
    /**
     * 修改订单状态
     */
    public function updateOrderStatus($data){
        $where="";
        if($data['order_status']==4){
            $where.=" AND order_status <= 1";
        }else{
            $where.=" AND order_status < ".$data['order_status'];
        }
        $sql = " UPDATE ".$this->tablename." SET order_status = ? WHERE id=? AND order_status!=? ".$where;

        $this->read_db->query($sql,array($data['order_status'],$data['id'],$data['order_status']));
        return $this->read_db->affected_rows();
    }
    
    /**
     * 添加订单
     */
    public function addXiuOrder($data){
        $data['create_time']=time();
        $data['create_date']=date("Y-m-d H:i:s",$data['create_time']);
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 获得单条订单信息
     */
    public function getOrderInfoById($id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE id = ? ";
        return $this->getCacheRowArray($sql,array($id));
    }

    /**
     * [getOrderFieldById 获取指定字段的单订单信息]
     * @param  [array] $fields [description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getOrderFieldById($fields,$id)
    {
        if (empty($fields)) {
          $fields='*';
        }else{
          $fields=implode(',',$fields);
        }
        $sql = " SELECT ".$fields." FROM ".$this->tablename." WHERE id = ? ";
        return $this->getCacheRowArray($sql,array($id));
    }
    
    /**
     * 获取指定半径内的坐标
     */
    // public function getNearLocation($data,$radius,$orderStatus,$payStatus){
    //     $sql = "SELECT
    //                 *
    //             FROM
    //                 sx_xiu_orders
    //             WHERE
    //                 order_status = ?
    //             AND pay_status = ?
    //             AND user_location_latitude > ? - 1/111*?
    //             AND user_location_latitude < ? + 1/111*?
    //             AND user_location_longitude > ? - 1/111*?
    //             AND user_location_longitude < ? + 1/111*?
    //             ORDER BY
    //                 ACOS(
    //                     SIN((? * 3.1415) / 180) * SIN((user_location_latitude * 3.1415) / 180) + COS((? * 3.1415) / 180) * COS((user_location_latitude * 3.1415) / 180) * COS(
    //                         (? * 3.1415) / 180 - (user_location_longitude * 3.1415) / 180
    //                     )
    //                 ) * 6380 ASC
    //             LIMIT 10";
    //     return $this->getCacheResultArray($sql,array($orderStatus,$payStatus,$data[1],$radius,$data[1],$radius,$data[0],$radius,$data[0],$radius,$data[1],$data[1],$data[0]));
    // }

    /**
     * [getTimeShopOrderList 获取指定时间内的订单]
     * @return [type] [description]
     */
    // public function getTimeShopOrderList ($condition,$start_time,$end_time,$page=1,$pageSize=10)
    // {
    //      $where="";
    //     foreach ($condition as $k=>$v){
    //         $where.=" AND ".$k." = ".$v;
    //     }
    //     $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where." AND create_time >=? AND create_time <=? ORDER BY id DESC LIMIT ?,?";
    //     return $this->getCacheResultArray($sql,array($start_time,$end_time,($page - 1) * $pageSize, intval($pageSize)));
    // }

    /**
     * [getTimeXgTotal 获取修哥指定时间内的的订单量和订单总金额]
     * @param  [type] $condition  [description]
     * @param  [type] $start_time [description]
     * @param  [type] $end_time   [description]
     * @return [type]             [description]
     */
    public function getTimeXgTotal ($condition,$fix_id_list,$start_time,$end_time)
    {
         $where="";
        foreach ($condition as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT `fix_id` , count(`id`) as xg_total_orders , SUM(`order_amount`) as xg_total_order_amount FROM ".$this->tablename." WHERE `fix_id` IN (".implode(',',$fix_id_list).") ".$where." AND create_time >=? AND create_time <=? GROUP BY `fix_id`";
        return $this->getCacheResultArray($sql,array($start_time,$end_time));
    }


    /**
     * [getTotalShopOrder 获取商家总的订单数目和营业额]
     * @param  [type] $shop_id      [description]
     * @param  [type] $order_status [description]
     * @return [type]               [description]
     */
    // public function getTotalShopOrder($shop_id,$order_status)
    // {
    //     $sql = " SELECT count(`id`) as total_order_num,SUM(`order_amount`) as total_shop_amount   FROM ".$this->tablename." WHERE shop_id = ? AND order_status = ?";
    //     return $this->getCacheRowArray($sql,array($shop_id,$order_status));
    // }

    /**
     * [getTodayShopOrder description]
     * @param  [type] $shop_id      [description]
     * @param  [type] $order_status [description]
     * @return [type]               [description]
     */
    // public function getTodayShopOrder($shop_id,$order_status,$start_time,$end_time)
    // {
    //     $sql = " SELECT count(`id`) as today_order_num,SUM(`order_amount`) as today_shop_amount FROM ".$this->tablename." WHERE shop_id = ? AND order_status = ? AND complete_time >=? AND complete_time <=?";
    //     return $this->getCacheRowArray($sql,array($shop_id,$order_status,$start_time,$end_time));
    // }

    /**
     * [getTimeAgentOrderList 获取指定时间内的订单]
     * @return [type] [description]
     */
    public function getTimeAgentOrderList ($condition,$start_time,$end_time,$page=1,$pageSize=10)
    {
         $where="";
        foreach ($condition as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where." AND create_time >=? AND create_time <=? ORDER BY id DESC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array($start_time,$end_time,($page - 1) * $pageSize, intval($pageSize)));
    }

    /**
     * [getTotalAgentOrder 获取代理商总的有效订单数目和营业额]
     * @param  [type] $agent_id      [description]
     * @param  [type] $order_status [description]
     * @return [type]               [description]
     */
    public function getTotalAgentOrder($agent_id,$order_status)
    {
        // $sql = " SELECT count(`id`) as total_order_num,SUM(`order_amount`) as total_agent_amount   FROM ".$this->tablename." WHERE agent_id = ? AND order_status = ?";
        $sql = " SELECT count(`id`) as total_order_num   FROM ".$this->tablename." WHERE agent_id = ? AND order_status = ?";
        return $this->getCacheRowArray($sql,array($agent_id,$order_status));
    }

    /**
     * [getTodayAgentOrder 获取代理商当日有效订单数量和总额]
     * @param  [type] $agent_id      [description]
     * @param  [type] $order_status [description]
     * @return [type]               [description]
     */
    public function getTodayAgentOrder($agent_id,$order_status,$start_time,$end_time)
    {
        // $sql = " SELECT count(`id`) as today_order_num,SUM(`order_amount`) as today_agent_amount FROM ".$this->tablename." WHERE agent_id = ? AND order_status = ? AND complete_time >=? AND complete_time <=?";
        $sql = " SELECT count(`id`) as today_order_num FROM ".$this->tablename." WHERE agent_id = ? AND order_status = ? AND complete_time >=? AND complete_time <=?";
        return $this->getCacheRowArray($sql,array($agent_id,$order_status,$start_time,$end_time));
    }

    /**
     * [getTodayAgentOrder 获取代理商当日有效订单数量和总额]
     * @param  [type] $agent_id      [description]
     * @param  [type] $order_status [description]
     * @return [type]               [description]
     */
    public function getTodayAgentCancelOrder($agent_id,$order_status,$start_time,$end_time)
    {
        $sql = " SELECT count(`id`) as today_order_num FROM ".$this->tablename." WHERE agent_id = ? AND order_status = ? AND create_time >=? AND create_time <=?";
        return $this->getCacheRowArray($sql,array($agent_id,$order_status,$start_time,$end_time));
    }

    /**
     * [getAgentOrderNums 获取代理商所有的订单总计数量]
     * @param  [type] $agent_id [description]
     * @return [type]           [description]
     */
    public function getAgentOrderNums($agent_id)
    {
        $sql = " SELECT count(`id`) as c  FROM ".$this->tablename." WHERE agent_id = ? ";
        return $this->getCacheRowArray($sql,array($agent_id))['c'];
    }


    /**
     * [getTodayAgentOrderNums  获取当日代理商所有的订单总计数量]
     * @param  [type] $agent_id   [description]
     * @param  [type] $start_time [description]
     * @param  [type] $end_time   [description]
     * @return [type]             [description]
     */
    public function getTodayAgentOrderNums($agent_id,$start_time,$end_time)
    {
        $sql = " SELECT count(`id`) as c FROM ".$this->tablename." WHERE agent_id = ? AND complete_time >=? AND complete_time <=?";
        return $this->getCacheRowArray($sql,array($agent_id,$start_time,$end_time))['c'];
    }

    public function getTodayAgentOrderNum($agent_id,$start_time,$end_time)
    {
        $sql = " SELECT count(`id`) as c FROM ".$this->tablename." WHERE agent_id = ? AND create_time >=? AND create_time <=?";
        return $this->getCacheRowArray($sql,array($agent_id,$start_time,$end_time))['c'];
    }
    /**
      * 
      */
    public function getFixOrder($fix_id,$order_status)
    {
        if(is_array($order_status)){
            $order_status=implode(",", $order_status);
        }
        $sql = " SELECT count(`id`) as num FROM ".$this->tablename." WHERE fix_id = ? AND order_status in (".$order_status.")";
        return $this->getCacheRowArray($sql,array($fix_id));
    }
    /**
      * 根据属性获取订单
      */
    public function getAllFixOrders($parames,$page=1, $pageSize=10)
    {
        $where="";
        foreach($parames as $k=>$v){
            if(is_array($v)){
                $v=implode(",", $v);
                $where.= " AND ".$k." in (".$v.")";
            }else{
               $where.= " AND ".$k." = '".$v."'";
            }
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where." ORDER BY id DESC LIMIT ?,?";

        return $this->getCacheResultArray($sql,array(($page - 1) * $pageSize, $pageSize));
    }

    /**
     * [searchAgentOrders 搜索经销商订单]
     * @return [type] [description]
     */
    public function searchAgentOrders($agent_id,$search,$page=1, $pageSize=10)
    {
        $sql = " SELECT * FROM ".$this->tablename." WHERE `agent_id`=? AND (`fix_name` like '%".$search."%' OR `service_name` like '%".$search."%') ORDER BY id DESC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array($agent_id,($page - 1) * $pageSize, $pageSize));
    }

    /**
     * 超时订单自动更新
     */
    public function getTimeoutOrders()
    {
        $time = time();
        $date = date('Y-m-d H:i:s');
        $lastday_time = strtotime(date("Y-m-d",strtotime("-1 day")));
        $sql = " SELECT * FROM ".$this->tablename." WHERE order_status=3 AND arrive_time<".$time;
        $orderResult = $this->getCacheResultArray($sql);
        if (count($orderResult)<1) {
            return false;
        }
        $orderIds = array_column($orderResult,'id');
        $orderFixId = array_column($orderResult,'fix_id');
        $orderShopId = array_column($orderResult,'shop_id');

        $queryFixWalletSql = " SELECT * FROM ".$this->tables['user_wallet']." WHERE user_id in(".implode(",", $orderFixId).")";
        $fixWallet = $this->getCacheResultArray($queryFixWalletSql);//查询修哥用户钱包

        $queryShopWalletSql = " SELECT * FROM ".$this->tables['shop']." WHERE id in(".implode(",", $orderShopId).")";
        $shopWallet = $this->getCacheResultArray($queryShopWalletSql);//查询商家钱包

        $update_order_sql = "UPDATE ".$this->tablename." SET order_status=4, complete_time = ".$time.", complete_date = '".$date."' , pay_amount = order_amount-sale_amount WHERE id in (".implode(",", $orderIds).")";//修改订单
        $this->read_db->query($update_order_sql);
        $this->read_db->affected_rows();

        $update_fix_sql = " UPDATE ".$this->tables['user_wallet']." as w inner join ".$this->tablename." as o on w.user_id=o.fix_id SET w.balance = balance+(o.order_amount-sale_amount)*0.6, total_balance = total_balance+(o.order_amount-sale_amount)*0.6 WHERE order_status=3 AND arrive_time<".$time;//分佣--修改修哥账户余额
        $this->read_db->query($update_fix_sql);
        $this->read_db->affected_rows();

        $update_shop_sql = "UPDATE ".$this->tables['shop']." as s inner join ".$this->tablename." as o on s.id=o.shop_id SET s.balance = balance+(o.order_amount-sale_amount)*0.3, balance1 =(o.order_amount-sale_amount)*0.3 WHERE order_status=3 AND arrive_time<".$time;//分佣--修改商家余额
        $this->read_db->query($update_shop_sql);
        $this->read_db->affected_rows();

        $fixData = array();
        foreach ($orderResult as $key => $value) {
            foreach ($fixWallet as $k => $v) {
                if ($orderResult[$key]['fix_id']==$fixWallet[$k]['user_id']) {
                    $amount = ($orderResult[$key]['order_amount'] - $orderResult[$key]['sale_amount'])*0.6;
                    $fixData[$key]['user_id'] = $orderResult[$key]['fix_id'];
                    $fixData[$key]['wallet_id'] = $fixWallet[$k]['id'];
                    $fixData[$key]['amount'] = $amount;
                    $fixData[$key]['before_balance'] = $fixWallet[$k]['balance'];
                    $fixData[$key]['after_balance'] = $fixWallet[$k]['balance']+$amount;
                    $fixData[$key]['income_type'] = '1';
                    $fixData[$key]['type'] = '3';
                    $fixData[$key]['create_time'] = $time;
                    $fixData[$key]['create_date'] = $date;
                }
            }
        }
        $this->write_db->insert_batch($this->tables['user_wallet_log'], $fixData);//修哥钱包日志
        parent::afterInsert($fixData);
        $this->write_db->insert_id();

        $shopData = array();
        foreach ($orderResult as $key => $value) {
            foreach ($shopWallet as $k => $v) {
                if ($orderResult[$key]['shop_id']==$shopWallet[$k]['id']) {
                    $amount = ($orderResult[$key]['order_amount'] - $orderResult[$key]['sale_amount'])*0.3;
                    $shopData[$key]['user_id'] = $shopWallet[$k]['user_id'];
                    $shopData[$key]['wallet_id'] = 0;
                    $shopData[$key]['amount'] = $amount;
                    $shopData[$key]['before_balance'] = $shopWallet[$k]['balance'];
                    $shopData[$key]['after_balance'] = $shopWallet[$k]['balance']+$amount;
                    $shopData[$key]['income_type'] = '1';
                    $shopData[$key]['type'] = '3';
                    $shopData[$key]['create_time'] = $time;
                    $shopData[$key]['create_date'] = $date;
                }
            }
        }
        $this->write_db->insert_batch($this->tables['shop'], $shopData);//店铺余额日志
        parent::afterInsert($shopData);
        $this->write_db->insert_id();
        return true;
    }
}
?>
