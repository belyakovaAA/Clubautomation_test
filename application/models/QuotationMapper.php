<?php

class Application_Model_QuotationMapper
{
    /**
     * Application_Model_DbTable_Quotation
     */
    protected $_dbTable;
    
    /**
     * Префикс для кэширования данных
     */
    protected $_cachePrefix = 'tableQuotation_';
 
    /**
     * Установать объект таблицы
     * 
     * @param string|\Application_Model_DbTable_Quotation $dbTable Таблица
     * 
     * @return \Application_Model_DbTable_Quotation  
     * @throws Exception                 
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    /**
     * Получить объект таблицы
     * 
     * @return \Application_Model_DbTable_Quotation  Объект таблицы
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Quotation');
        }
        return $this->_dbTable;
    }
 
    /**
     * Сохранить
     * 
     * @param \Application_Model_Quotation $quotation Модель quotatuion
     */
    public function save(Application_Model_Quotation $quotation)
    {
        $data = array(
            'baseCurrencyId'        => $quotation->getBaseCurrencyId(),
            'quotatedCurrencyId'    => $quotation->getQuotatedCurrencyId(),
            'quotation'             => $quotation->getQuotation(),
            'updatedAt'             => $quotation->getUpdatedAt()
        );
 
        if (null === ($id = $quotation->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
    /**
     * Найти по ключу
     * 
     * @param int                           $id          id записи
     * @param \Application_Model_Quotation  $quotation   Модель quotation
     * 
     * @return bull|null                                 false, если ничего не найдено
     */
    public function find($id, Application_Model_Quotation $quotation)
    {
        $cacheId = $this->_cachePrefix . 'id_' . $id;
        $cache = Zend_Registry::get('cache');
        if (!($result = $cache->load($cacheId))) {
            $result = $this->getDbTable()->find($id);
            $cache->save($result, $cacheId);
        }
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $quotation->setId($row->id)
            ->setBaseCurrencyId($row->baseCurrencyId)
            ->setQuotatedCurrencyId($row->quotatedCurrencyId)
            ->setQuotation($row->quotation)
            ->setUpdatedAt($row->updatedAt);
    }
 
    /**
     * Получить все данные
     * 
     * @return \Application_Model_Quotation  Результат выборки данных
     */
    public function fetchAll()
    {
        $cacheId = $this->_cachePrefix . 'all';
        $cache = Zend_Registry::get('cache');
        if (!($resultSet = $cache->load($cacheId))) {
            $resultSet = $this->getDbTable()->fetchAll();
            $cache->save($resultSet, $cacheId);
        }
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Quotation();
            $entry->setId($row->id)
                ->setBaseCurrencyId($row->baseCurrencyId)
                ->setQuotatedCurrencyId($row->quotatedCurrencyId)
                ->setQuotation($row->quotation)
                ->setUpdatedAt($row->updatedAt);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Удалить данные по ключу
     * 
     * @param int   $id     id записи
     * 
     * @return int          Количество удаленных строк
     */
    public function delete($id = null)
    {
        $table = $this->getDbTable();
        $where = $id
            ? $table->select()->where('id = ?', strtoupper($id))
            : null;
        $table->delete($where);
        $result = $table->delete($where);
        return $result;
    }
    
    /**
     * Очистить кэш для данных
     */
    public function clearCache()
    {
        $cacheId = $this->_cachePrefix . 'all';
        $cache = Zend_Registry::get('cache');
        $cache->save(null, $cacheId);
    }
}

