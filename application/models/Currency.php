<?php

class Application_Model_Currency
{
    protected $_title;
    protected $_name;
    protected $_providerCurrencyId;
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
            throw new Exception('Invalid currency property');
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
            throw new Exception('Invalid currency property');
        }
        return $this->$method();
    }
 
    /**
     * Установить значения полей
     * 
     * @param array $options                Массив значений полей
     * @return \Application_Model_Currency  Модель currency
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
     * Установить значение поля title
     * 
     * @param string                        $title  Заголовок
     * @return \Application_Model_Currency          Модель currency
     */
    public function setTitle($title)
    {
        $this->_title = (string) $title;
        return $this;
    }
 
    /**
     * Получить значение поля title
     * 
     * @return string          Значение поля title
     */
    public function getTitle()
    {
        return $this->_title;
    }
 
    /**
     * Установить значение поля name
     * 
     * @param string                        $name   Название
     * @return \Application_Model_Currency          Модель currency
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }
 
    /**
     * Получить значение поля name
     * 
     * @return string          Значение поля name
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Установить значение поля providerCurrencyId
     * 
     * @param string                        $providerCurrencyId   id записи на стороне провайдера
     * @return \Application_Model_Currency                        Модель currency
     */
    public function setProviderCurrencyId($providerCurrencyId)
    {
        $this->_providerCurrencyId = (string) $providerCurrencyId;
        return $this;
    }
 
    /**
     * Получить значение поля providerCurrencyId
     * 
     * @return string          Значение поля providerCurrencyId
     */
    public function getProviderCurrencyId()
    {
        return $this->_providerCurrencyId;
    }
 
    /**
     * Установить значение поля id
     * 
     * @param string                        $id   id записи
     * @return \Application_Model_Currency        Модель currency
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

