<?php

/**
 * Simple class that just incapsulates Pinba configuration and methods for Yii
 * Just load it on start of application and prepare configurations
 * (default one would be goo enought)
 *
 * @author Last G <last-g@skbkontur.ru>
 * 
 */

/**
 * Simple internal class that incapsulates pinba core functions
 * @author Last G <last-g@skbkontur.ru>
 */
class PinbaApi extends CComponent {
    public function timerStart(array $tags, array $data=array())
    {
        return pinba_timer_start($tags, $data);
    }
    public function timerStop($timer)
    {
        return pinba_timer_stop($timer);
    }
    public function timerAdd(array $tags, $value, array $data=array())
    {
        return pinba_timer_add($tags, $value, $data);
    }
    public function timerDelete($timer)
    {
        return pinba_timer_delete($timer);
    }
    public function timerTagsMerge($timer, array $tags) {
        return pinba_timer_tags_merge($timer, $tags);
    }
    public function timerTagsReplace($timer, array $tags) {
        return pinba_timer_tags_replace($timer, $tags);
    }
    public function timerDataMerge($timer, array $data) {
        return pinba_timer_data_merge($timer, $data);
    }
    public function timerDataReplace($timer, array $data) {
        return pinba_timer_data_replace($timer, $data);
    }
    public function timerGetInfo($timer) {
        return pinba_timer_get_info($timer);
    }
    
    public function timersStop() {
        return pinba_timers_stop();
    }
    
    public function getInfo() {
        return pinba_get_info();
    }
    
    public function setSchema($schema) {
        return pinba_schema_set($schema);
    }
    
    public function setScriptName($scriptName) {
        return pinba_script_name_set($scriptName);
    } 
    
    public function setServerName($serverName) {
        return pinba_server_name_set($serverName);
    }
    
    public function setRequestTime($requestTime) {
        return pinba_request_time_set($requestTime);
    }
    public function setHostname($hostname) {
        return pinba_hostname_set($hostname);
    }
}
 

class Pinba extends CApplicationComponent {
    
    private $_internalCaller;

    public $on = true;
    public $fixScriptName   = true;
    
    public $scriptName      = null;
    public $hostName        = null;
    public $serverName      = null;
    public $schema          = null;


    public function getEnabled() {
        return (extension_loaded("pinba") && $this->on);
    }
    

    public function init() {
        parent::init();
        $this->_internalCaller = new PinbaApi();
        
        if(! is_null($this->scriptName) ) {
            $this->setScriptName($this->scriptName);
        } elseif ($this->fixScriptName) {
            if(php_sapi_name() === 'cli') {
                $argv = implode(' ', $_SERVER['argv']);
                $this->setScriptName($argv);
            }
            else {
                $path = Yii::app()->urlManager->parseUrl(Yii::app()->request);
                $this->setScriptName($path);
            }
        }
        if(! is_null($this->hostName))
        {
            $this->setHostname($this->hostName);
        }
        if(! is_null($this->serverName))
        {
            $this->setServerName($this->serverName);
        }
        if(! is_null($this->schema))
        {
            $this->setSchema($this->schema);
        }
    }
    
    public function __call($name, $parameters) {
        
        // Translating all to real API if enabled
        if(method_exists($this->_internalCaller, $name)) {
            Yii::log("Calling: $name with " . print_r($parameters, true));

            if($this->enabled) { 
                    try {
                        return call_user_func_array(array($this->_internalCaller, $name),$parameters);
                    } catch (CException $ex) {
                         // Pass (that was no such method)
                    }
            }
        }
        else
        {
            return parent::__call($name, $parameters);
        }
    }

    /// Callers for pinba functions 

 } 