<?php
class OrderEvaluationModel extends DB_Model {
    protected $tables = array(
            //'user' => 'cro.sx_xiu_service' 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_order_evaluation');
        $this->log->log_debug('OrderEvaluationModel  model be initialized');
    }
    
    /**
     * 获得单条评价信息
     */
    public function getEvaluationInfoById($id){
        $sql = " SELECT * FROM ".$this->tablename." WHERE id = ? ";
        return $this->getCacheRowArray($sql,array($id));
    }

    public function getEvaluationInfoByAtt($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1".$where;
        return $this->getCacheRowArray($sql);
    }
    
    /**
     * 
     */
    public function checkOrderEvaluation($parames){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE platform_id = ? AND order_id = ?";
        return $this->getCacheRowArray($sql,array($parames['platform_id'],$parames['order_id']))['c'];
    }
    
    /**
     * 添加评价
     */
    public function addOrderEvaluation($data){
        $data['create_time']=time();
        $data['create_date']=date("Y-m-d H:i:s",$data['create_time']);
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }

    /**
      *  根据条件获取评价的平均值
      */
    public function evaluationAvg($parames)
    {
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = "SELECT AVG(technology_star) as thy_avg,AVG(service_star) as sve_avg,AVG(speed_star) as apd_avg FROM ".$this->tablename." WHERE platform_id = ? ".$where;
        return $this->getCacheRowArray($sql,array($parames['platform_id']));
    }
}
?>
