<?php
class StarTagModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_star_tag');
        $this->log->log_debug('StarTagModel  model be initialized');
    }
    
    /**
     * 根据条件获得星级tag
     */
    public function getStarTagByAttr($parames){
        $where="";
        if(is_array($parames)){
            foreach($parames as $k=>$v){
                if(is_array($v)){
                    $v=implode(",", $v);
                    $where.= " AND ".$k." in (".$v.")";
                }else{
                   $where.= " AND ".$k." = '".$v."'";
                }
            }
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }
}
?>