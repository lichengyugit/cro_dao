<?php
/**
 * 活动模型
 */
class ActivityModel extends DB_Model 
{
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_xiu_activity'); 
        $this->log->log_debug('ActivityModel  model be initialized'); 
    }
    
    /**
     * 根据属性获取单用户信息
     */
    public function getActivitysInfoByAttr($parames)
    {
        $where="";
        foreach ($parames as $k=>$v)
        {
            if(is_array($v)){
                $v=implode(",", $v);
                $where.= " AND ".$k." in (".$v.")";
            }else{
               $where.= " AND ".$k." = '".$v."'";
            }
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        // return $this->getCacheResultArray($sql);
        return $this->getCacheRowArray($sql);
    }


}
?>