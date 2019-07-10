<?php
class XiuAgentModel extends DB_Model {
    protected $tables = array(
            // 'partner' => 'cro_test.sx_partner', 
    );

    public function __construct() {
        parent::__construct($this->dbname, 'sx_xiu_agent');
        $this->log->log_debug('XiuAgentModel  model be initialized');
    }
    
    /**
     * 
     * 根据属性获取代理商信息
     */
    public function getAgentInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     * [getShopsInfoByAttr 获取多代理商信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getAgentsInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            if(is_array($v)){
                $v=implode(",", $v);
                $where.= " AND ".$k." in (".$v.")";
            }else{
               $where.= " AND ".$k." = '".$v."'";
            }
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * 验证代理商名是否存在
     */
    public function checkAgentName($name){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE name=? ";
        return $this->getCacheRowArray($sql,array(
                $name
        ))['c'];
    }
    
    /**
     * 
     * 根据类型获得所有代理商
     */
    public function getAllAgent($page, $pageSize,$role_flag){
        $sql = " SELECT * FROM ".$this->tablename." AS p WHERE p.status <2 AND p.role_flag=? ORDER BY id ASC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array(
                $role_flag,
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }

    /**
     * 添加代理商
     * 根据代理商名获取所有代理商
     */
    public function getSearchAgent($status,$name){
        $where="";
        if($name){
            $where.=" AND (`name` like '%".$name."%' OR `phone` like '%".$name."%')";
            // $where.=" AND name like '%".$name."%'";
        }
        
        $sql = " SELECT * FROM ".$this->tablename." WHERE status = ?".$where." ORDER BY id DESC";
        return $this->getCacheResultArray($sql,array($status));
    }
 
    /**
     * 添加代理商
     */
    public function addAgent($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 
     * 根据ID修改代理商信息
     */
    public function updateAgent($data){
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
     * 
     * 根据条件修改代理商信息
     */
    public function updateAgentByAttr($data,$where){
        $update=$this->update($data, $wheres);
        if($update){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     *  钱包余额变动
     */
    public function updateBalance($parames,$id)
    {
        $set ="";
        foreach($parames as $k=>$v){
            $set.= $k." = ".$v.", ";
        }
        $set = trim($set, ", ");
        $sql = "UPDATE ".$this->tablename." SET ".$set." WHERE id= ".$id;
        $this->read_db->query($sql);
        return $this->read_db->affected_rows();
    }
}
?>