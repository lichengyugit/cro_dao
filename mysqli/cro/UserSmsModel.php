<?php
class UserSmsModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_user_sms' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_user_sms');
        $this->log->log_debug('UserSmsModel  model be initialized');
    }
    
    
    /**
     * 验证短信
     */
    public function checkUserSms($mobile,$code,$code_type){
        $sql = " SELECT over_time, id, COUNT(1) as c FROM ".$this->tablename." WHERE mobile = ? AND code = ? AND code_type = ?";
        $row = $this->getCacheRowArray($sql,array(
                $mobile,
                $code,
                $code_type
        ));
        $result['timeOut']="out";
        $result['id']=$row['id'];
        $result['c']=$row['c'];
        if($row['c']>0){
            if($row['over_time']>time()){
                $result['timeOut']="in";
            }
        }
        return $result;
    }
    
    /**
     * 查询短信
     */
    public function getUserSms($mobile,$code_type){
        $sql = " SELECT over_time, id, COUNT(1) as c FROM ".$this->tablename." WHERE mobile = ? AND code_type = ?";
        $row = $this->getCacheRowArray($sql,array(
                $mobile,
                $code_type
        ));
        $result['timeOut']="out";
        $result['id']=$row['id'];
        $result['c']=$row['c'];
        if($row['c']>0){
            if(time()>$row['over_time']-60*14){
                $result['timeOut']="over";
            }elseif($row['over_time']>time()){
                $result['timeOut']="in";
            }
        }
        return $result;
    }
    
    /**
     * 添加验证码
     */
    public function addSms($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 修改用户
     */
    public function updateSms($data){
        $wheres=array('id'=>$data['id']);
        $update=$this->update($data, $wheres);
        if($update){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * 删除验证码
     */
    public function deleteUserSms($id){
        $delete=$this->delete($id);
        if($delete){
            return true;
        }
        else{
            return false;
        }
    }
}
?>