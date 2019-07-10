<?php
class InsureModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_insure'); 
        $this->log->log_debug('InsureModel  model be initialized'); 
    }


    /**
     * [getInsuresInfoByIds 获取多保险信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getInsuresInfoByIds($parames){
        $where="";
        $ids=implode(",", $parames);
        $where.= " AND id in (".$ids.")";
        $sql = " SELECT `id`,`insure_name`,`price_1`,`rebate_status`,`rebate_money` FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

     public function getInsureInfoByAttr($parames)
     {
         $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * [getInsuresInfo 获取单条保险信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getInsuresInfo($parames)
    {
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }
}
?>