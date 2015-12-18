<?php

/**
 * Абстрактный провайдер данных курса валют
 */

abstract class Class_Exchange_Rate_Provider_Abstract 
{
    /**
     * Ссылка для получения данных
     */
    protected $_link;
    
    /**
     * Выполнить запрос к серверу провайдера
     * 
     * @param string $link ссылка
     * @return mixed
     */
    protected function _getData($link)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    /**
     * Обработка полученных данных
     * 
     * @return array массив данных, сгруппированных по id на стороне провайдера
     */
    abstract protected function _prepareData();

    /**
     * Обновить данные котирововк
     * 
     * @return string
     */
    public function updateQuotations()
    {
        $quotationMapper = new Application_Model_QuotationMapper();
        $quotationModel = new Application_Model_Quotation();
        $currencyMapper = new Application_Model_CurrencyMapper();
        $quotationMapper->clearCache();
        $currencyMapper->clearCache();
        $now = Zend_Date::now();
        $nowUnixFormated = $now->toString('Y-M-d H:m:s');
        $currenciesFetched = $currencyMapper->fetchAll();
        $currencyArray = [];
        foreach ($currenciesFetched as $currency) {
            $providerCurrencyId = $currency->getProviderCurrencyId();
            $currencyArray[$providerCurrencyId] = $currency;
        }
        $quotationMapper->delete();
        $quotations = $this->_prepareData();
        foreach ($currencyArray as $baseProviderCurrencyId => $baseCurrency) {
            foreach ($currencyArray as $quotatedProviderCurrencyId  => $quotatedCurrency) {
                if ($baseProviderCurrencyId == $quotatedProviderCurrencyId) {
                    continue;
                }
                $quotation = round($quotations[$baseProviderCurrencyId]['quotation']
                        / $quotations[$quotatedProviderCurrencyId]['quotation'], 2);
                $data = [
                    'baseCurrencyId'        => $baseCurrency->getId(),
                    'quotatedCurrencyId'    => $quotatedCurrency->getId(),
                    'quotation'             => $quotation,
                    'updatedAt'             => $nowUnixFormated
                ];
                $quotationModel->setOptions($data);
                $quotationMapper->save($quotationModel);
            }
        }
        return 'success';
    }
    
    /**
     * Добавить валюту по значению буквенного кода
     * 
     * @param string $currencyLetterCode буквенный код валюты
     * @return string
     */
    public function addCurrency($currencyLetterCode)
    {
        $currencyMapper = new Application_Model_CurrencyMapper();
        $existsCurrency = $currencyMapper->fetchByName($currencyLetterCode);
        if ($existsCurrency) {
            return 'exists';
        }
        $currencyModel = new Application_Model_Currency();
        $quotations = $this->_prepareData();
        foreach ($quotations as $data) {
            if ($data['name'] !== $currencyLetterCode) {
                continue;
            }
            $currencyModel->setOptions($data);
            break;
        }
        if (!$currencyModel->getProviderCurrencyId()) {
            return 'notFound';
        }
        $currencyMapper->save($currencyModel);
        $result = $this->updateQuotations();
        return $result;
    }
}
