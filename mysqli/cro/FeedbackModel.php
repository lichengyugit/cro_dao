<?php
class FeedbackModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_feedback'); 
        $this->log->log_debug('FeedbackModel  model be initialized'); 
    }

    
    /**
     * 添加反馈
     */
    public function addFeedback($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 根据ID修改反馈信息
     */
    public function updateFeedback($data){
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
     * 修改反馈信息
     */
    public function updateFeedbackByAttr($data,$wheres){
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