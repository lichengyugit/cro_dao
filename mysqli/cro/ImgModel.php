<?php
class ImgModel extends DB_Model {
   
    protected $tables = array();

    public function __construct() { 
        parent::__construct($this->dbname,'sx_img'); 
        $this->log->log_debug('ImgModel  model be initialized'); 
        $user=$this->dbname.'.sx_user' ;
        $shop=$this->dbname.'.sx_shop' ;
        $this->tables['user']=$user;
        $this->tables['shop']=$shop;
    }

    /**
     * [getImgByAttr 获取图片统一入口]
     * @param  [type] $parames [传入图片类型attr_id 类型attr 图片种类type]
     * @return [type]          [description]
     */
    public function getImgByAttr($parames){
        $return=['hash'=>'','key'=>''];
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        $result=$this->getCacheRowArray($sql);
        if (!empty($result)) {
            $return['hash']=$result['img_hash'];
            $return['key']=$result['img_key'];
            return $return;
        }
        if ($parames['attr']==3) {//代理商图
            return $return;
        }
        if ($parames['attr']==1) {//用户表图片
            $where='AND id='.$parames['attr_id'];//搜索条件
            $field='';
            if ($parames['type']==1) {
                $field='icon';
            }
            if ($parames['type']==2) {
                $field='sfzphoto_z';
            }
            if ($parames['type']==3) {
                $field='sfzphoto_f';
            }
            $sql= " SELECT ".$field." FROM ".$this->tables['user']." WHERE 1=1 ".$where;
            $result=$this->getCacheRowArray($sql);
            $return['key']=CRO_IMG.$result[$field];
            return $return;
        }
        if ($parames['attr']==2) {//商家表图片
            $where='AND id='.$parames['attr_id'];//检索条件
            $field='';
            if ($parames['type']==4) {
                $field='dianpu_img';
            }
            if ($parames['type']==5) {
                $field='charter_img';
            }
            $sql= " SELECT ".$field." FROM ".$this->tables['shop']." WHERE 1=1 ".$where;
            $result=$this->getCacheRowArray($sql);
            $return['key']=CRO_IMG.$result[$field];
            return $return;
        }
        
    }
    
    /**
     * 根据属性获取单图片信息
     */
    public function getImgInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheRowArray($sql);
    }

    /**
     * [getImgsInfoByAttr 获取多图片信息]
     * @param  [type] $parames [description]
     * @return [type]          [description]
     */
    public function getImgsInfoByAttr($parames){
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * [getXgIconList  批量获取图片]
     * @param  [type] $attr_id [description]
     * @param  [type] $attr    [description]
     * @param  [type] $type    [description]
     * @return [type]          [description]
     */
    public function getXgIconList($attr_id,$attr,$type)
    {
        $sql = " SELECT * FROM ".$this->tablename." WHERE attr = ? AND type = ? AND attr_id IN (".implode(',',$attr_id).")";
        return $this->getCacheResultArray($sql,array($attr,$type));
    }

    /**
     * 添加图片 信息
     */
    public function addImg($data){
        $data['create_time']=time();
        $data['create_date']=date('Y-m-d,H:i:s',time());
        $insert=$this->insert($data);
        return $this->lastInsertId();
    }
    
    /**
     * 批量添加图片信息
     */
    public function addImgPatch($data)
    {
        $insertBatch=$this->insertBatch($data);
        return $insertBatch;
    }

    /**
     * 根据ID修改图片信息
     */
    public function updateImg($data){
        $data['update_time']=time();
        $data['update_date']=date('Y-m-d,H:i:s',time());
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
     * 修改图片信息
     */
    public function updateImgByAttr($data,$wheres){
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