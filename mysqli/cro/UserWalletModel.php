<?php
class UserWalletModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_user_wallet');
        $this->log->log_debug('UserWalletModel  model be initialized');
    }
    
    
    
    /**
     * 获取单条用户钱包信息
     */
    public function getWalletByUserId($user_id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE user_id = ?";
        return $this->getCacheRowArray($sql,array($user_id));
    }

    /**
     * 修改钱包
     */
    // public function updateWalletByAttr($data,$where){
    //     $update=$this->update($data, $wheres);
    //     return $update;
    // }

    /**
     *  钱包余额变动
     */
    public function updateWalletBalance($parames,$user_id)
    {
        $wallet = $this->getWalletByUserId($user_id);
        if ($wallet<1) {
            $data['user_id'] = $user_id;
            $data['create_time'] = time();
            $data['create_date'] = date('Y-m-d H:i:s');
            $insert=$this->insert($data);
        }else{
            $set ="";
            $parames['update_time'] = time();
            $parames['update_date'] = "'".date("Y-m-d H:i:s")."'";
            foreach($parames as $k=>$v){
                $set.= $k." = ".$v.", ";
            }
            $set = trim($set, ", ");
            $sql = "UPDATE ".$this->tablename." SET ".$set." WHERE user_id= ".$user_id;
            $this->read_db->query($sql);
             return $this->read_db->affected_rows();
        }
    }
        
        public function actionWalletBalance($parames,$user_id,$actionBalance){

            $wallet = $this->getWalletByUserId($user_id);
            if ($wallet<1) {
                if(array_key_exists('balance', $parames)){
                    $data['balance'] = $parames['balance'];
                }
                if (array_key_exists('giving_balance', $parames)) {
                    $data['giving_balance'] = $parames['giving_balance'];
                }
                if(array_key_exists('total_balance', $parames)){
                    $data['total_balance'] = $parames['total_balance'];
                }
                $data['user_id'] = $user_id;
                $data['create_time'] = time();
                $data['create_date'] = date('Y-m-d H:i:s');
                $insert=$this->insert($data);
                return $insert;
            }else{
                $set ="";
                if(array_key_exists('balance', $parames)){
                    $parames['balance'] = 'balance '.$actionBalance.$parames['balance'];
                }
                if (array_key_exists('giving_balance', $parames)) {
                    $parames['giving_balance'] = 'giving_balance '.$actionBalance.$parames['giving_balance'];
                }
                if(array_key_exists('total_balance', $parames)){
                    $parames['total_balance'] = 'total_balance '.$actionBalance.$parames['total_balance'];
                }
                $parames['update_time'] = time();
                $parames['update_date'] = "'".date("Y-m-d H:i:s")."'";
                foreach($parames as $k=>$v){
                    $set.= $k." = ".$v.", ";
                }
                $set = trim($set, ", ");
                $sql = "UPDATE ".$this->tablename." SET ".$set." WHERE user_id= ".$user_id;
                $this->read_db->query($sql);
                 return $this->read_db->affected_rows();
            }
        }
}
?>