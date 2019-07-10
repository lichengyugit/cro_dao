<?php
class VersionModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_version');
        $this->log->log_debug('VersionModel  model be initialized');
    }

    /**
     * 根据获取版本信息
     */
    public function getVersionByAtt($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where." ORDER BY create_time DESC";
        return $this->getCacheRowArray($sql);
    }
}
?>