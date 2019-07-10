<?php
class FixStatusModel extends DB_Model {
    protected $tables = array();

    protected $radiusLimit=5;//搜索距离限制

    public function __construct() {
        parent::__construct($this->dbname, 'sx_fix_status');
        $this->log->log_debug('FixStatusModel  model be initialized');
        $this->tables['shop_attr']=$this->dbname.'.sx_shop_attr_center';
        $this->tables['shop']=$this->dbname.'.sx_shop';
    }

    /**
     * 根据修哥ID获取修哥状态
     */
    public function getFixStatusByFixId($fix_id)
    {
        $sql = " SELECT id,fix_id,status,order_status,fix_location_longitude,fix_location_latitude, count(*) as num FROM ".$this->tablename." WHERE fix_id = ? ";
        return $this->getCacheRowArray($sql,array($fix_id));
    }

    /**
     * [getFixInfoByFixId 获取修哥状态信息]
     * @param  [type] $fix_id [description]
     * @return [type]         [description]
     */
    public function getFixInfoByFixId($fix_id)
    {
        $sql = " SELECT * FROM ".$this->tablename." WHERE fix_id = ? ";
        return $this->getCacheRowArray($sql,array($fix_id));
    }

    /**
     * 添加修哥状态
     */
    public function addFixStatus($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 
     * 修改修哥状态
     */
    public function updateFixStatus($data,$wheres){
        return $this->update($data, $wheres);
    }
    
    /**
     * 获取附近修哥
     */
    public function getNearFix($data,$radius,$orderStatus,$status){
        $sql = "SELECT
                    *
                FROM
                    ".$this->tablename."
                WHERE
                    order_status = ?
                AND status = ?
                AND fix_location_latitude > ? - 1/111*?
                AND fix_location_latitude < ? + 1/111*?
                AND fix_location_longitude > ? - 1/111*?
                AND fix_location_longitude < ? + 1/111*?
                ORDER BY
                    ACOS(
                        SIN((? * 3.1415) / 180) * SIN((fix_location_latitude * 3.1415) / 180) + COS((? * 3.1415) / 180) * COS((fix_location_latitude * 3.1415) / 180) * COS(
                            (? * 3.1415) / 180 - (fix_location_longitude * 3.1415) / 180
                        )
                    ) * 6380 ASC
                Limit 10";
        $list=$this->getCacheResultArray($sql,array($orderStatus,$status,$data[1],$radius,$data[1],$radius,$data[0],$radius,$data[0],$radius,$data[1],$data[1],$data[0]));
        if(!empty($list)){
            foreach($list as $k=>$v){
                $idArray[$k]=$v['fix_id'];
            }
            return $idArray;exit;
        }  
        return $list;
    }

    /**
     * [atuoNearFix 自动派单获取符合条件的修哥]
     * @param  [type] $data        [description]
     * @param  [type] $radius      [description]
     * @param  [type] $orderStatus [description]
     * @param  [type] $status      [description]
     * @return [type]              [description]
     */
    // public function  atuoNearFix($data,$radius,$orderStatus,$fixStatus,$shopFixStatus,$brandId,$serviceId='')
    // {
    //         $sql = "SELECT
    //                     `fix_id`,sp.`id` AS shop_id
    //                 FROM
    //                     ".$this->tablename." AS fs,".$this->tables['shop']." AS sp,".$this->tables['shop_attr']." AS spa
    //                 WHERE
    //                 fs.shop_id=sp.id
    //                 AND spa.shop_id=sp.id
    //                 AND fs.order_status = ?
    //                 AND fs.status = ?
    //                 AND sp.fix_status = ?
    //                 AND spa.type = ?
    //                 AND spa.attr_id = ?
    //                 AND fix_location_latitude > ? - 1/111*?
    //                 AND fix_location_latitude < ? + 1/111*?
    //                 AND fix_location_longitude > ? - 1/111*?
    //                 AND fix_location_longitude < ? + 1/111*?
    //                 ORDER BY
    //                     ACOS(
    //                         SIN((? * 3.1415) / 180) * SIN((fix_location_latitude * 3.1415) / 180) + COS((? * 3.1415) / 180) * COS((fix_location_latitude * 3.1415) / 180) * COS(
    //                             (? * 3.1415) / 180 - (fix_location_longitude * 3.1415) / 180
    //                         )
    //                     ) * 6380 ASC
    //                 ";
    //         $list=$this->getCacheResultArray($sql,array($orderStatus,$fixStatus,$shopFixStatus,1,$brandId,$data[1],$radius,$data[1],$radius,$data[0],$radius,$data[0],$radius,$data[1],$data[1],$data[0]));

    //         if(empty($list))//没有修哥 扩大搜索范围
    //         {
    //             $radius=$radius+1;
    //             if ($radius>$this->radiusLimit) 
    //             {
    //                 return ;//超过限制距离后停止搜索返回空
    //             }
    //             $this-> atuoNearFix($data,$radius,$orderStatus,$fixStatus,$shopFixStatus,$brandId,$serviceId);
    //         }
    //         //有指定修哥验证是否有指定的服务项目
    //         $shop_id_list=array_unique(array_column($list,'shop_id'));
    //         $sql = " SELECT `id`,`shop_id` FROM ".$this->tables['shop_attr']." WHERE shop_id IN (".implode(',',$shop_id_list).") AND attr_id = ? AND type = ?";
    //         $final_shop=$this->getCacheRowArray($sql,array($serviceId,2));
    //         if (empty($final_shop)) //没有指定服务项目的商家则扩大范围重新搜索
    //         {
    //              $radius=$radius+1;
    //             if ($radius>$this->radiusLimit) 
    //             {
    //                 return ;//超过限制距离后停止搜索返回空
    //             }
    //             $this-> atuoNearFix($data,$radius,$orderStatus,$fixStatus,$shopFixStatus,$brandId,$serviceId);
    //         }
    //         //有则返回对应的修哥id
    //         foreach ($list as $k => $v) 
    //         {
    //             if ($final_shop['shop_id']==$v['shop_id']) 
    //             {
    //                 return $v['fix_id'];//返回最终的符合条件修哥id
    //             }
    //         }
    // }


    /**
     * [atuoNearAgentFix 自动派单寻找修哥(代理商)]
     * @param  [type] $data        [description]
     * @param  [type] $radius      [description]
     * @param  [type] $orderStatus [description]
     * @param  [type] $fixStatus   [description]
     * @return [type]              [description]
     */
    public function  atuoNearAgentFix($data,$radius,$orderStatus,$fixStatus)
    {
            $sql = "SELECT
                        *
                    FROM
                        ".$this->tablename." 
                    WHERE
                    order_status = ?
                    AND status = ?
                    AND fix_location_latitude > ? - 1/111*?
                    AND fix_location_latitude < ? + 1/111*?
                    AND fix_location_longitude > ? - 1/111*?
                    AND fix_location_longitude < ? + 1/111*?
                    ORDER BY
                        ACOS(
                            SIN((? * 3.1415) / 180) * SIN((fix_location_latitude * 3.1415) / 180) + COS((? * 3.1415) / 180) * COS((fix_location_latitude * 3.1415) / 180) * COS(
                                (? * 3.1415) / 180 - (fix_location_longitude * 3.1415) / 180
                            )
                        ) * 6380 ASC Limit 1
                    ";
            $list=$this->getCacheRowArray($sql,array($orderStatus,$fixStatus,$data[1],$radius,$data[1],$radius,$data[0],$radius,$data[0],$radius,$data[1],$data[1],$data[0]));

            if(empty($list))//没有修哥 扩大搜索范围
            {
                $radius=$radius+1;
                if ($radius>$this->radiusLimit) 
                {
                    return ;//超过限制距离后停止搜索返回空
                }
                $this-> atuoNearAgentFix($data,$radius,$orderStatus,$fixStatus);
            }
            //有指
            return $list;
    }
}
?>
