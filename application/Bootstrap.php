<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Инициализация автозагрузки
     */
    protected function _initAutoload() 
    {
        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH,
        ));
        $loader->addResourceType('classes_type', 'classes', 'Class');
    }
    
    /**
     * Инициализация кэша
     */
    protected function _initCache()
    {
        $config     = $this->getApplication()->getOptions();
        Zend_Registry::set('config', $config);
        $cacheConfig = $config['resources']['cachemanager']['database'];
        $cache = Zend_Cache::factory(
            $cacheConfig['frontend']['name'],
            $cacheConfig['backend']['name'],
            $cacheConfig['frontend']['options'],
            $cacheConfig['backend']['options']
        );
        Zend_Registry::set('cache', $cache);
    }
}

