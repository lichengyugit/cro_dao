<?php
/**
 * 广告模型
 */
class AdvModel extends DB_Model 
{
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_xiu_advertisement'); 
        $this->log->log_debug('AdvModel  model be initialized'); 
    }
    
    /**
     * 根据属性获取单用户信息
     */
    public function getAdvsInfoByAttr($parames)
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
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where." ORDER BY id DESC";
        if ($parames['adv_type']==2) //引导页只有一个广告
        {
            $data[0]=$this->getCacheRowArray($sql);
            return $data;
            exit;
        }
        //首页弹出可以为多个广告
        return $this->getCacheResultArray($sql);
    }


}
?>