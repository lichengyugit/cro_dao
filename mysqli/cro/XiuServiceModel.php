<?php
class XiuServiceModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_xiu_service');
        $this->log->log_debug('XiuServiceModel  model be initialized');
    }
    
    
    
    /**
     * 根据类型获得所有服务
     */
    public function getAllService($status)
    {
        $sql = " SELECT * FROM ".$this->tablename." WHERE status = ? ORDER BY sort";
        return $this->getCacheResultArray($sql,array($status));
    }

    /**
     * 根据ID获取服务
     */
    public function getServerById($id)
    {
        $sql = " SELECT * FROM ".$this->tablename." WHERE id= ?";
        return $this->getCacheRowArray($sql,array($id));
    }
}
?>
