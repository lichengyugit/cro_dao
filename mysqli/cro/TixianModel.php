<?php
class TixianModel extends DB_Model {
   protected $tables = array();
    public function __construct() {
        parent::__construct($this->dbname, 'sx_tixian');
        $this->log->log_debug('TixianModel  model be initialized');
        $this->tables['user_wallet']=$this->dbname.'.sx_user_wallet';
    }

    public function getTixianInfoByAttr($id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE id=".$id;
        return $this->getCacheRowArray($sql);
    }

    /**
     * 根据条件获取所有提现
     */
    public function getAllTixian($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * 添加提现
     */
    public function addTixian($data){
        $data['createtime'] = time();
        $data['createdate'] = date('Y-m-d H:i:s');
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 修改提现
     */
    public function updateTixianByAttr($data,$where){
        $update=$this->update($data, $wheres);
        return $update;
    }
    
    /**
     * 提现
     */
    public function tixiantrans($parames)
    {
        $this->trans_start();//开启事务
        $primary_id = $this->addTixian($parames);

        $sql_wallet = " SELECT * FROM ".$this->tables['user_wallet']." WHERE user_id = ".$parames['user_id'];
        $userWallet = $this->getCacheRowArray($sql_wallet);
        if ($userWallet['balance']>=$parames['money']) {
            $sql = " UPDATE ".$this->tables['user_wallet']." SET balance = balance-".$parames['money']." WHERE user_id=".$parames['user_id'];
        }elseif (($userWallet['balance']+$userWallet['giving_balance'])>=$parames['money']) {
             $giving_balance = $userWallet['giving_balance']-($parames['money']-$userWallet['balance']);

            $sql = " UPDATE ".$this->tables['user_wallet']." SET balance = 0, giving_balance=".$giving_balance." WHERE user_id=".$parames['user_id'];
        }
        $this->read_db->query($sql);

        $this->trans_complete();//提交数据
        $return['status'] = $this->trans_status();
        $return['primary_id'] = $primary_id;
         return $return;
    }
}
?>