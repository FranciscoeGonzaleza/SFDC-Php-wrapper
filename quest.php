<?php
/**
 * Autor: Francisco Gonzalez
 * version: 1.0
 * Created on: May 2019 
 * quest class file.
 *
 * Perform calls to Apex REST services that allow to perform any kind of query to the data 
 *
 * 
 */

namespace sForce;

/**
 * Class quest
 *
 * @package sForce
 */
class quest extends Core {
    
    
    /**
    * sObjects constructor.
    *
    * @param object $access_token
    *   access token 
    */
    public function __construct($access_token) {
        parent::__construct($access_token);
        
        $this->endPoint = $this->access_token->instance_url."/services/data/v".$this->apiVersion."/";

    } 
    
    
    /**
    * function query: Executes the specified SOQL query.
    *
    * @param string $pQuery
    *   Query to be executed: "SELECT Id, Name FROM Account LIMIT 2"
    *
    * 
    */
    function query($pQuery) {
        $lStr_endpoint = $this->endPoint."query/?q=" . urlencode($pQuery);

        return $this->makeCall($lStr_endpoint);
    }
    
    /**
    * function search: Executes the specified SOSL search.
    *
    * @param string $pSearch
    *   SOSL search string: "FIND {FRANCISCO}"
    *   More info: https://developer.salesforce.com/docs/atlas.en-us.soql_sosl.meta/soql_sosl/sforce_api_calls_sosl_syntax.htm
    *  
    */
    function search($pSearch) {
        $lStr_endpoint = $this->endPoint."search/?q=" . urlencode($pSearch);

        return $this->makeCall($lStr_endpoint); 
        
    }
    
    /**
    * function parameterizedSearch: Executes a simple RESTful search using parameters instead of a SOSL clause. 
    *
    * @param string $pSearch
    *   Search string: "sobject=Account&Account.fields=id,Name&q=Francisco"
    *   More info: https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_search_parameterized.htm

    */
    function parameterizedSearch($pSearch) {
        $lStr_endpoint = $this->endPoint."parameterizedSearch/?$pSearch" ;
        //$lStr_endpoint = $this->endPoint."parameterizedSearch/?q=Acme";
        
        return $this->makeCall($lStr_endpoint); 
        
    }
    
    
    

}
?>