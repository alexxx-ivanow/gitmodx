#!/usr/bin/env php
<?php

/**
 * Automaticaly replace include path and class name to gitmodx in index.php files and change absolute path in core.inc.php files
 */

include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/'.'config.core.php';
include MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';

$files = array(
    MODX_BASE_PATH.'index.php',
    MODX_CONNECTORS_PATH.'index.php',
    MODX_MANAGER_PATH.'index.php'
);

foreach($files as $file){
    $content = file_get_contents($file);
    $content = str_replace('model/modx/modx.class.php','components/gitmodx/model/gitmodx/gitmodx.class.php',$content);
    $content = str_replace('new modX(','new gitModx(',$content);
    file_put_contents($file,$content);
}

$coreIncFiles = array(
    MODX_BASE_PATH.'config.core.php' => "__DIR__.'/core/'",
    MODX_CONNECTORS_PATH.'config.core.php' => "dirname(__DIR__).'/core/'",
    MODX_MANAGER_PATH.'config.core.php' => "dirname(__DIR__).'/core/'",
);

foreach($coreIncFiles as $file => $replace){
    $content = file_get_contents($file);
    $content = preg_replace("#define\('MODX_CORE_PATH', '([^']+)'\);#mu", "define('MODX_CORE_PATH', {$replace});", $content);
    file_put_contents($file,$content);
}