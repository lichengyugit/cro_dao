<?php
class UserWalletLogModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_user_sms' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_user_wallet_log');
        $this->log->log_debug('UserWalletLogModel  model be initialized');
    }
    
    /**
     * 添加用户余额操作日志
     */
    public function addUserWalletLog($data){
        $data['create_time'] = time();
        $data['create_date'] = date('Y-m-d H:i:s');
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    public function getAllUserWalletLog($parames,$page=1, $pageSize=10)
    {
        $where="";
        foreach($parames as $k=>$v){
            $where.= " AND ".$k." = '".$v."'";
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where." ORDER BY id DESC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array(($page - 1) * $pageSize, $pageSize));
    }

    public function getUserWalletLogInfoById($id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE id=".$id;
        return $this->getCacheRowArray($sql);
    }

    public function getUserWalletLogInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }
    
}
?>