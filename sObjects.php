<?php
/**
 * Autor: Francisco Gonzalez
 * version: 1.0
 * Created on: May 2019 
 * sObjects class file.
 *
 * Provides sObjects manupulation like query records, search, create, delete, etc. 
 *
 */

namespace sForce;

/**
 * Class sObjects
 *
 * @package sForce
 */
class sObjects extends Core {
    
    
    /**
    * sObjects constructor.
    *
    * @param object $access_token
    *   access token 
    */
    public function __construct($access_token) {
        parent::__construct($access_token);
        
        $this->endPoint = $this->access_token->instance_url."/services/data/v".$this->apiVersion."/sobjects/";
    } 
    
    
    /**
    * function getRecord.
    *
    * @param string $sObject
    *   Salesforce object name: Account
    * @param string $objectId
    *   Object Id: 001f400000GKvGCAA1
    * 
    *  returns an object
    */
    function getRecord($sObject, $objectId) {
        $lStr_endpoint = $this->endPoint."$sObject/$objectId";
        
        return $this->makeCall($lStr_endpoint);
        
        
    }
    
    /**
    * function createRecord.
    *
    * @param string $sObject
    *   Salesforce object name: Account
    * @param string $record
    *   Record to insert: json_encode( array("Name" => "New account" ) )
    * 
    *  returns an object
    */
    function createRecord($sObject, $record) {
        $lStr_endpoint = $this->endPoint."$sObject/";
        
        return $this->makeCall($lStr_endpoint, 'INSERT', $record);
        
        
    }
    
    /**
    * function updateRecord.
    *
    * @param string $sObject
    *   Salesforce object name: Account
    * @param string $objectId
    *   Object Id: 001f400000GKvGCAA1
    * @param string $fieldsValues
    *   Record to insert: json_encode( array("Name" => "New name" ) )
    * 
    *  returns an object
    */
    function updateRecord($sObject, $objectId, $fieldsValues) {
        $lStr_endpoint = $this->endPoint."$sObject/$objectId/";
        
        return $this->makeCall($lStr_endpoint, 'UPDATE', $fieldsValues);
        
        
    }
    
    /**
    * function deleteRecord.
    *
    * @param string $sObject
    *   Salesforce object name: Account
    * @param string $objectId
    *   Object Id: 001f400000GKvGCAA1
    * 
    *  returns an object
    */
    function deleteRecord($sObject, $objectId) {
        $lStr_endpoint = $this->endPoint."$sObject/$objectId";
        
        return $this->makeCall($lStr_endpoint, 'DELETE');
        
        
    }
    
    /**
    * function describe.
    *
    * @param string $sObject
    *   Salesforce object name: Account
    * 
    *  returns an object
    */
    function describe($sObject) {
        $lStr_endpoint = $this->endPoint."$sObject/describe/";
        
        return $this->makeCall($lStr_endpoint);
        
        
    }
    
    /**
    * function getPicklistValues.
    *
    * @param string $sObject
    *   Salesforce object name: Contact
    * @param string $fieldName
    *   Field Name: Salutation 
    * 
    *  returns an object
    */
    function getPicklistValues($sObject, $fieldName) {
        $lObj_describe = $this->describe($sObject);
        
        foreach($lObj_describe->fields as $key => $value) {
            
            if ($value['name'] == $fieldName){
                return $value['picklistValues'];
            }
            
           
        }
        
        return Null;
        
        
    }
    

}
?>