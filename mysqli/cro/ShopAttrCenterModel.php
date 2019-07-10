<?php

/**
 * 商家品牌 服务中间表模型
 */
class ShopAttrCenterModel extends DB_Model 
{
    protected $tables = array(
            // 'shop' => 'cro.sx_shop' 
    );

    public function __construct() 
    {
        parent::__construct($this->dbname, 'sx_shop_attr_center');
        $this->log->log_debug('ShopAttrCenterModel  model be initialized');
    }
    
    /**
     * 根据属性获取商家经验品牌和提供服务信息
     */
    public function getShopAttrCenterInfoByAttr($parames)
    {
        $where="";
        foreach ($parames as $k=>$v){
            $where.=" AND ".$k." = ".$v;
        }
        $sql = " SELECT * FROM ".$this->tablename." WHERE 1=1 ".$where;
        return $this->getCacheResultArray($sql);
    }

    /**
     * 批量添加店铺服务和品牌
     */
    public function addShopAttrCenterPatch($data)
    {
        $insertBatch=$this->insertBatch($data);
        return $insertBatch;
    }

    /**
     * 批量删除店铺服务和品牌
     */
    public function deleteShopAttrCenterPatch($Arr)
    {
        return $this->deleteBath($Arr);
    }
}
?>