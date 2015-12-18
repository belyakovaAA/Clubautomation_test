<?php

class IndexController extends Zend_Controller_Action
{
    /**
     * Имя провайдера данных
     */
    protected $exchangeRatesProviderName = 'cbrf';

    /**
     * Инициализация
     */
    public function init()
    {
        $ajaxContext = $this->_helper->AjaxContext();
        $ajaxContext->addActionContext('index', 'html')
            ->addActionContext('update', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('delete', 'json')
            ->initContext();
    }

    /**
     * Отображение данных
     */
    public function indexAction()
    {
        $quotation = new Application_Model_QuotationMapper();
        $quotationsFetched = $quotation->fetchAll();
        $currency = new Application_Model_CurrencyMapper();
        $currenciesFetched = $currency->fetchAll();
        $currencyArray = [];
        foreach ($currenciesFetched as $currency) {
            $currencyId = $currency->getId();
            $currencyArray[$currencyId] = $currency;
        }
        $quotationArray = [];
        foreach ($quotationsFetched as $quotation) {
            $baseCurrencyId = $quotation->getBaseCurrencyId();
            $quotatedCurrencyId = $quotation->getQuotatedCurrencyId();
            $quotationArray[$baseCurrencyId][$quotatedCurrencyId] = $quotation;
        }
        $this->view->title = 'Курсы валют';
        $this->view->currencies = $currencyArray;
        $this->view->quotations = $quotationArray;
    }

    /**
     * Обновление данных
     */
    public function updateAction()
    {
        $provider = $this->_getExchangeRatesProvider();
        $result = $provider->updateQuotations();
        echo Zend_Json::encode($result);
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
    }
    
    /**
     * Добавление валюты по буквенному коду
     */
    public function addAction()
    {
        $currencyLetterCode = $this->_request->getParam('currencyLetterCode');
        $provider = $this->_getExchangeRatesProvider();
        $result = $provider->addCurrency(strtoupper($currencyLetterCode));
        echo Zend_Json::encode($result);
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
    }
    
    /**
     * Удаление валюты по идентификатору
     */
    public function deleteAction()
    {
        $currencyId = $this->_request->getParam('currencyId');
        $provider = $this->_getExchangeRatesProvider();
        $currencyMapper = new Application_Model_CurrencyMapper();
        $currencyMapper->delete($currencyId);
        $result = $provider->updateQuotations();
        echo Zend_Json::encode($result);
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
    }
    
    /**
     * Получить объект провайдера данных
     * 
     * @return Class_Exchange_Rate_Provider_Abstract
     */
    protected function _getExchangeRatesProvider()
    {
        $providerName = $this->exchangeRatesProviderName;
        $providerManager = new Class_Exchange_Rate_Provider_Manager();
        $provider = $providerManager->get($providerName);
        return $provider;
    }
}

