<?php

/**
 * Менеджер провайдеров данных курса валют
 */
class Class_Exchange_Rate_Provider_Manager
{
    /**
     * Получить объект провайдера по имени
     * 
     * @param string $providerName Имя провайдера
     * 
     * @return \Class_Exchange_Rate_Provider_Abstract
     */
    public function get($providerName)
    {
        $providerNameNormilized = 'Class_Exchange_Rate_Provider_' . ucfirst(strtolower($providerName));
        if (class_exists($providerNameNormilized)) {
            return new $providerNameNormilized();
        } else {
            return null;
        }
    }
}
