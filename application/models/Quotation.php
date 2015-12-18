<?php

class Application_Model_Quotation
{
    protected $_baseCurrencyId;
    protected $_quotatedCurrencyId;
    protected $_quotation;
    protected $_updatedAt;
    protected $_id;
 
    /**
     * Конструктор
     * 
     * @param array $options Опции
     */
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    /**
     * На установку значения поля
     * 
     * @param string    $name   Имя поля
     * @param mixed     $value  Значение поля
     * 
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid quotation property');
        }
        $this->$method($value);
    }
 
    /**
     * На получение значения поля
     * 
     * @param strint $name  Имя поля
     * 
     * @return mixed        Значение поля
     * @throws Exception
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid quotation property');
        }
        return $this->$method();
    }
 
    /**
     * Установить значения полей
     * 
     * @param array $options                 Массив значений полей
     * @return \Application_Model_Quotation  Модель quotation
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
    /**
     * Установить значение поля baseCurrencyId
     * 
     * @param string                            $baseCurrencyId     id базовой валюты
     * @return \Application_Model_Quotation                         Модель quotation
     */
    public function setBaseCurrencyId($baseCurrencyId)
    {
        $this->_baseCurrencyId = (string) $baseCurrencyId;
        return $this;
    }
 
    /**
     * Получить значение поля baseCurrencyId
     * 
     * @return string          Значение поля baseCurrencyId
     */
    public function getBaseCurrencyId()
    {
        return $this->_baseCurrencyId;
    }
    
    /**
     * Установить значение поля quotatedCurrencyId
     * 
     * @param string                            $quotatedCurrencyId     id котируемой валюты
     * @return \Application_Model_Quotation                             Модель quotation
     */
    public function setQuotatedCurrencyId($quotatedCurrencyId)
    {
        $this->_quotatedCurrencyId = (string) $quotatedCurrencyId;
        return $this;
    }
 
    /**
     * Получить значение поля quotatedCurrencyId
     * 
     * @return string          Значение поля quotatedCurrencyId
     */
    public function getQuotatedCurrencyId()
    {
        return $this->_quotatedCurrencyId;
    }
 
    /**
     * Установить значение поля quotation
     * 
     * @param string                            $quotation     Котировка
     * @return \Application_Model_Quotation                    Модель quotation
     */
    public function setQuotation($quotation)
    {
        $this->_quotation = (string) $quotation;
        return $this;
    }
 
    /**
     * Получить значение поля quotation
     * 
     * @return string          Значение поля quotation
     */
    public function getQuotation()
    {
        return $this->_quotation;
    }
    
    /**
     * Установить значение поля updatedAt
     * 
     * @param string                            $updatedAt     Дата обновления
     * @return \Application_Model_Quotation                    Модель quotation
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->_updatedAt = (string) $updatedAt;
        return $this;
    }
 
    /**
     * Получить значение поля updatedAt
     * 
     * @return string          Значение поля updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->_updatedAt;
    }
    
    /**
     * Установить значение поля id
     * 
     * @param string                            $id     id записи
     * @return \Application_Model_Quotation             Модель quotation
     */
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    /**
     * Получить значение поля id
     * 
     * @return string          Значение поля id
     */
    public function getId()
    {
        return $this->_id;
    }
}

