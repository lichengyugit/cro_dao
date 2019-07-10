<?php
class SmsLogModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_user_sms' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_sms_log');
        $this->log->log_debug('SmsLogModel  model be initialized');
    }
    
    /**
     * 添加验证码日志
     */
    public function addSmsLog($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
}
?>