<?php
class AddressModel extends DB_Model {
    protected $tables = array(
            //'role' => 'mssp_user.role' 
    );

    public function __construct() {
        parent::__construct('cro_test', 'sx_address');
        $this->log->log_debug('AddressModel  model be initialized');
    }
    
    
    /**
     * 咨询页-列表查询
     */
    public function getAllConsultByType(){
        $sql = "SELECT *  FROM ".$this->tablename." ORDER BY id DESC ";
        return $this->getCacheResultArray($sql);
    }
    
    
    /**
     * 根据类型获得所有文章
     */
    public function getAllArticle($page, $pageSize,$type){
        $sql = " SELECT id,article_title,type,child_type,article,create_time,create_time_str,update_time,status,introduction,hot_point,author FROM ".$this->tablename." AS p WHERE p.status <2 AND p.type=? ORDER BY id ASC LIMIT ?,?";
        return $this->getCacheResultArray($sql,array(
                $type,
                ($page - 1) * $pageSize,
                $pageSize
        ));
    }
    
    /**
     * 咨询数量
     */
    public function countArticle(){
        $sql = 'SELECT COUNT(1) AS c FROM ' . $this->tablename . ' AS p WHERE p.status < 2 ';
        return $this->getCacheRowArray($sql)['c'];
    }

    /**
     * 获得单条文章信息
     */
    public function getOneArticle($id){
        $sql = " SELECT id,article_title,article,type,child_type,article,create_time,create_time_str,update_time,status,hot_point,author,introduction FROM ".$this->tablename." WHERE id = ".$id;
        return $this->getCacheRowArray($sql);
    }
    /**
     * 验证文章标题是否存在
     */
    public function checkArticleTitle($data){
        $sql = " SELECT COUNT(1) FROM ".$this->tablename." WHERE article_title='".$data['article_title']."' AND type ='".$data['type']."' ";
        return $this->getCacheRowArray($sql);
    }
    /**
     * 添加文章
     */
    public function addArticle($data){
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 修改角色
     */
    public function updateArticle($data){
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
     * 逻辑删除角色
     */
    public function deleteArticle($data){
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
     * 上下架
     */
    public function updataStatusById($id,$status){
        $sql = "UPDATE ".$this->tablename." SET status = ? WHERE id = ? AND status < 2";
        return $this->writeQuery($sql, array(
                $status,
                $id
        ));
    }

    /**
     * [testFun 测试方法]
     * @return [type] [description]
     */
    public function testFun($id)
    {
        $sql="SELECT id,user_id,receiver,receiver_area FROM ".$this->tablename." WHERE id =?";
        return $this->getCacheResultArray($sql,array(
            $id
            ));
    }
}
?>