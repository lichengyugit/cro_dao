<?php
class PushLogModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_push_log');
        $this->log->log_debug('PushLogModel  model be initialized');
    }
    
    /**
     * 添加Jpush操作log
     */
    public function addJpushLog($data){
        $data['create_time']=time();
        $data['create_date']=date("Y-m-d H:i:s",$data['create_time']);
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
}
?>