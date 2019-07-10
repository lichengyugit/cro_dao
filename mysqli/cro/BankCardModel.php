<?php
class BankCardModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_bank'); 
        $this->log->log_debug('BankCardModel  model be initialized'); 
    }
    
    /**
     * 根据属性获取单银行卡信息
     */
    public function getBankcardByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    public function getBankcardListByUids($uidArr)
    {
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 AND `status` =1 AND `user_id` IN (".implode(',',$uidArr).")";
        return $this->getCacheResultArray($sql);
    }

    /**
     * [getBankcardListByAttr 获取多银行卡信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getBankcardListByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }
    
    /**
     * 验证银行卡名密码
     */
    public function checkBankcardByNameAndPass($username,$passWord){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE username = ? AND passwd = ?";
        return $this->getCacheRowArray($sql,array(
                $username,
                $passWord
        ))['c'];
    }
    
    /**
     * 验证手机号银行卡是否存在
     * 状态为1 正常
     */
    public function checkBankcard($bank_num){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE zhanghao=? AND status =? ";
        return $this->getCacheRowArray($sql,array(
                $bank_num,
                1
        ))['c'];
    }

    /**
     * [checkUserBankcard 验证用户的银行卡状态]
     * @param  [type] $bank_num [description]
     * @return [type]           [description]
     */
    public function checkUserBankcard($user_id,$bank_num,$status){
        $sql = " SELECT `id` FROM ".$this->tablename." WHERE user_id=? AND zhanghao=? AND status =? ";
        return $this->getCacheRowArray($sql,array(
                $user_id,
                $bank_num,
                $status
        ))['id'];
    }

    
    /**
     * 添加银行卡
     */
    public function addBankcard($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 根据ID修改银行卡信息
     */
    public function updateBankcard($data){
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return $update;
        }
        else{
            return false;
        }
    }
    
    /**
     * 修改银行卡信息
     */
    public function updateBankcardByAttr($data,$wheres){
        $update=$this->update($data, $wheres);
        if($update){
            return true;
        }
        else{
            return false;
        }
    }
}
?>