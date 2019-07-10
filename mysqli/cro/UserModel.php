<?php
class UserModel extends DB_Model {
    protected $tables = array(

    );

    public function __construct() { 
        parent::__construct($this->dbname,'sx_user'); 
        $this->log->log_debug('UserModel  model be initialized'); 
    }
    
    /**
     * 根据属性获取单用户信息
     */
    public function getUserInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     * [getSearchUser 搜索用户]
     * @param  [type] $status [description]
     * @param  [type] $search   [description]
     * @return [type]         [description]
     */
    public function getSearchUser($condition,$search,$page=1,$pageSize=10){
        $where="";
        foreach ($condition as $k=>$v){
            $where.=" AND `".$k."` = ".$v;
        }
        if($search){
            $where.=" AND (`username` like '%".$search."%' OR `name` like '%".$search."%')";
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1 = 1".$where." limit ?,?";
        return $this->getCacheResultArray($sql,array(
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }

    /**
     * [getConditionUser 根据条件获取用户群体]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    public function getConditionUser($condition){
        $where="";
        foreach ($condition as $k=>$v){
            $where.=" AND `".$k."` = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1 = 1".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * [getConditionXg 根据条件获取修哥 并分页]
     * @param  [type] $condition [description]
     * @param  [type] $page      [description]
     * @param  [type] $pageSize  [description]
     * @return [type]            [description]
     */
    public function getConditionXg($condition,$page=1,$pageSize=10){
        $where="";
        foreach ($condition as $k=>$v){
            $where.=" AND `".$k."` = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1 = 1".$where." limit ?,?";
        return $this->getCacheResultArray($sql,array(
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }

    /**
     * [countAgentXg 获取商家下的修哥数量]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    public function countAgentXg($agent_id){
        $sql = " SELECT count(`id`) AS c FROM ".$this->tablename." WHERE `parent_id` = ? AND `role_flag` = ? AND `status` = ?";
        return $this->getCacheRowArray($sql,array(
                $agent_id,
                3,
                1
        ))['c'];
    }

    /**
     * [getUsersInfoByAttr 获取多用户信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getUsersInfoByAttr($parames){
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
     * 验证用户名密码
     */
    public function checkUserByNameAndPass($username,$passWord){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE username = ? AND passwd = ?";
        return $this->getCacheRowArray($sql,array(
                $username,
                $passWord
        ))['c'];
    }
    
    /**
     * 验证手机号用户是否存在
     */
    public function checkUserName($username,$role_flag){
        $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE username=? AND role_flag =? ";
        return $this->getCacheRowArray($sql,array(
                $username,
                $role_flag
        ))['c'];
    }

    /**
     * [countXgNumbers 计算每个店铺下修哥人数]
     * @param  [type] $username  [description]
     * @param  [type] $role_flag [description]
     * @return [type]            [description]
     */
    // public function countXgNumbers($shop_id){
    //     $sql = " SELECT COUNT(1) as c FROM ".$this->tablename." WHERE parent_id=? AND status =? AND role_flag =? ";
    //     return $this->getCacheRowArray($sql,array(
    //             $shop_id,
    //             1,
    //             3
    //     ))['c'];
    // }

    
    /**
     * 根据类型获得所有用户
     */
    public function getAllUser($page, $pageSize,$role_flag){
        $sql = " SELECT * FROM ".$this->tablename." AS p WHERE p.status <2 AND p.role_flag=? ORDER BY id ASC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array(
                $role_flag,
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }
    

    
    /**
     * 添加用户
     */
    public function addUser($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 根据ID修改用户信息
     */
    public function updateUser($data){
        $data['update_time']=time();
        $data['update_date']=date('Y-m-d,H:i:s',$data['update_time']);
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
     * 修改用户信息
     */
    public function updateUserByAttr($data,$wheres){
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