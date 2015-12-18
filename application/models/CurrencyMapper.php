<?php

class Application_Model_CurrencyMapper
{
    /**
     * Application_Model_DbTable_Currency
     */
    protected $_dbTable;
    
    /**
     * Префикс для кэширования данных
     */
    protected $_cachePrefix = 'tableCurrency_';

    /**
     * Установать объект таблицы
     * 
     * @param string|\Application_Model_DbTable_Currency $dbTable Таблица
     * 
     * @return \Application_Model_DbTable_Currency    
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
     * @return \Application_Model_DbTable_Currency  Объект таблицы
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Currency');
        }
        return $this->_dbTable;
    }
 
    /**
     * Сохранить
     * 
     * @param \Application_Model_Currency $currency Модель currency
     */
    public function save(Application_Model_Currency $currency)
    {
        $data = [
            'title'                 => $currency->getTitle(),
            'name'                  => $currency->getName(),
            'providerCurrencyId'    => $currency->getProviderCurrencyId()
        ];
 
        if (null === ($id = $currency->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, ['id = ?' => $id]);
        }
    }
 
    /**
     * Найти по ключу
     * 
     * @param int                         $id           id записи
     * @param \Application_Model_Currency $currency     Модель currency
     * 
     * @return bull|null                                false, если ничего не найдено
     */
    public function find($id, Application_Model_Currency $currency)
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
        $currency->setId($row->id)
            ->setTitle($row->title)
            ->setName($row->name)
            ->setProviderCurrencyId($row->providerCurrencyId);
    }
 
    /**
     * Получить все данные
     * 
     * @return \Application_Model_Currency  Результат выборки данных
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
            $entry = new Application_Model_Currency();
            $entry->setId($row->id)
                ->setTitle($row->title)
                ->setName($row->name)
                ->setProviderCurrencyId($row->providerCurrencyId);
            $entries[] = $entry;
        }
        return $entries;
    }
    
    /**
     * Получить данные по полю name
     * 
     * @param string                            $name   Значение поля name
     * 
     * @return Zend_Db_Table_Row_Abstract|null          Найденные данные ил null
     */
    public function fetchByName($name)
    {
        $table = $this->getDbTable();
        $cacheId = $this->_cachePrefix . 'name_' . $name;
        $cache = Zend_Registry::get('cache');
        if (!($row = $cache->load($cacheId))) {
            $row = $table->fetchRow($table->select()->where('name = ?', strtoupper($name)));
            $cache->save($row, $cacheId);
        }
        return $row;
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
            ? 'id = ' . $id
            : null;
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

