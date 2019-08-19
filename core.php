<?php
/**
* Autor: Francisco Gonzalez
* version: 1.0
* Created on: May 2019 
* Core class file.
*
* Provides definitions of common variables and functions to connect to SalesForce 
*
*

*/

namespace sForce;

/**
* Class Core
*
* @package sForce
*/
class Core {


    /**
    * @var string $access_token
    *   Access token
    */
    public $access_token = null;

    /**
    * @var string $access_token
    *   Access token
    */
    public $apiVersion = '45.0';
    
    /**
    * @var string $endPoint
    *   End point: full url of the service to be called
    *   
    */
    public $endPoint = '';




    /**
    * sForce class constructor.
    *
    * @param object $access_token
    *   sForce params to obtain the access token
    *
    */
    public function __construct($access_token = null) {
        $this->access_token = $access_token;
    }


    /**
    * sForce getAccessToken.
    *
    */

    public static function getAccessToken() {


        $lStr_endpoint = 'https://'.SERVER_DOMAIN."/services/oauth2/token" ;

        $postData = 'username='.urlencode(USERNAME).'&';
        $postData .= 'password='.urlencode(PASSWORD).'&';
        $postData .= 'client_id='.urlencode(CLIENT_ID).'&';
        $postData .= 'client_secret='.urlencode(CLIENT_SECRET).'&';
        $postData .= 'grant_type=password';
        $postData = rtrim($postData, '&');
        
        $curl = curl_init($lStr_endpoint);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER,'Content-Type: application/x-www-form-urlencoded');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData );
        $lResponse = curl_exec($curl);
        curl_close($curl);


        $lObj_reponse = (object)json_decode($lResponse,true);

        return $lObj_reponse; 


    }

    public function makeCall($pResource, $pMethod = 'GET', $pData = null) {
        
        $curl = curl_init($pResource);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Authorization: OAuth ".$this->access_token->access_token,
                     "Content-type: application/json"));
        
        
        switch (strtoupper($pMethod)) {
			case 'INSERT' : 
				// Set the cURL custom header
				curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $pData);
                //success status == 201 
            break;
            case 'UPDATE' : 
				// Set the cURL custom header
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $pData);
                //success status == 204 
            break;
            case 'DELETE' : 
				// Set the cURL custom header
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                //success status == 204 
            break;
        }

        $json_response = curl_exec($curl);
        
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if ( $status != 200 //OK
            &&  $status != 201 //Created
           ) {
            
            $json_response = json_decode($json_response, true);
         
            $response = (object) array (
                "errorCode" => $json_response[0]["errorCode"],
                "message" => $json_response[0]["message"],
                "fields" => $json_response[0]["fields"],
                "resource" => $pResource,
                "status" => $status,
                "curl_error" => curl_error($curl),
                "curl_errno" => curl_errno($curl),
                "response" => $json_response,
            );
        }else{
            $response = (object)json_decode($json_response, true);
        }
        
        
        curl_close($curl);

        
        
        return $response; 
      
    }


}