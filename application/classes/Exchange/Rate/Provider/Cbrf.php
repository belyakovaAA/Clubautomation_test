<?php

/**
 * Провайдер данных курса валют от ЦБРФ
 */
class Class_Exchange_Rate_Provider_Cbrf extends Class_Exchange_Rate_Provider_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_link = "http://www.cbr.ru/scripts/XML_daily.asp";
    
    /**
     * @inheritdoc
     */
    protected function _prepareData() 
    {
        $link = $this->_makeLink();
        $responseXml = $this->_getData($link);
        $dom = dom_import_simplexml(simplexml_load_string($responseXml));
        $valuteList = $dom->getElementsByTagName('Valute');
        foreach ($valuteList as $node) {
            $nodeId = $node->getAttribute('ID');
            $quotation = $node->getElementsByTagName('Value')->item(0)->nodeValue;
            $name = strtoupper(
                $node->getElementsByTagName('CharCode')->item(0)->nodeValue
            );
            $title = $node->getElementsByTagName('Name')->item(0)->nodeValue;
            $data = [
                'providerCurrencyId'    => $nodeId,
                'quotation'             => $quotation,
                'name'                  => $name,
                'title'                 => $title
            ];
            $quotations[$nodeId] = $data;
        }
        return $quotations;
    }

    /**
     * @inheritdoc
     */
    protected function _makeLink()
    {
        $now = Zend_Date::now();
        $nowFormated = $now->toString('d/M/Y');
        $link = $this->_link . '?date_req=' . $nowFormated;
        return $link;
    }
}
