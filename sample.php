<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

require_once('sForce.php');

/***************************/
echo '<h1>Getting access token</h1>';
$sForceAccessToken = sForce\Core::getAccessToken();
echo 'Token: '.json_encode($sForceAccessToken).'<br>'; 
echo 'Access Token: '.$sForceAccessToken->access_token.'<br>'; 
echo 'Instance URL: '.$sForceAccessToken->instance_url.'<br>'; 


/***************************/
echo '<hr><h1>sObject</h1>';
$sObjects = new sForce\sObjects($sForceAccessToken);


echo '<h2>New contact</h2>';
$lObj_newContact = json_encode( array("FirstName" => "Francisco", "LastName" => "Gonzalez-".rand(1, 1000000) ) );
$sObjectsNewContact = $sObjects->createRecord('Contact', $lObj_newContact);
echo json_encode($sObjectsNewContact).'<br>';
echo 'New object id: '.$sObjectsNewContact->id.'<br>';


echo '<h2>Get Contact</h2>';
$lObj_contact = $sObjects->getRecord('Contact', $sObjectsNewContact->id);
//echo json_encode($lObj_contact).'<br>';
echo 'Contact Name: '.$lObj_contact->FirstName.' '.$lObj_contact->LastName.'<br>'; 
echo 'Account Id: '.$lObj_contact->AccountId.'<br>'; 


echo '<h2>Update Account</h2>';
$lObj_updateFields = json_encode( array("Name" => "Francisco Gonzalez ".date("Y-m-d h:i:sa") ) );
$sObjects->updateRecord('Account', $lObj_contact->AccountId, $lObj_updateFields);


echo '<h2>Delete Contact</h2>';
$sObjects->deleteRecord('Contact', $sObjectsNewContact->id);



/***************************/
echo '<hr><h1>Quest</h1>';
$sfQuery = new sForce\quest($sForceAccessToken);

echo '<h2>Query</h2>';
$soqlResult = $sfQuery->query("SELECT Id, Name FROM Account LIMIT 2");
if (isset($soqlResult->totalSize)){
    
    if ($soqlResult->totalSize > 0){
        echo 'Total of records'.$soqlResult->totalSize.'<br>';
        echo 'First record'.json_encode($soqlResult->records[0]).'<br>';
        echo 'First record id'.$soqlResult->records[0]['Id'].'<br>';
    }else{
        echo 'query has no results';
    }
    
}else{
    echo 'soslSearchResults [ERR]: '.json_encode($soqlResult).'<br>';
}


echo '<h2> Search </h2>';
$soslSearchResults = $sfQuery->search('FIND {"Francisco"} IN ALL FIELDS RETURNING Contact (Id, Name)');
if (isset($soslSearchResults->searchRecords)){
    echo 'soslSearchResults first record: '.json_encode($soslSearchResults->searchRecords[0]).'<br>';
}else{
    echo 'soslSearchResults [ERR]: '.json_encode($soslSearchResults).'<br>';
}

echo '<h2> Parameterized Search </h2>';
$parameterizedSearchResults = $sfQuery->parameterizedSearch('q=Francisco&sobject=Account&Account.fields=id,name&Account.limit=10');
if (isset($parameterizedSearchResults->searchRecords)){
    echo 'soslSearchResults first record: '.json_encode($parameterizedSearchResults->searchRecords[0]).'<br>';
}else{
    echo 'soslSearchResults [ERR]: '.json_encode($parameterizedSearchResults).'<br>';
}






?>


