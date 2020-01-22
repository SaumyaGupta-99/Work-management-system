<?php
require_once 'vendor/autoload.php';
use Neoxygen\NeoClient\ClientBuilder;
//connecting to graph database
$client = ClientBuilder::create()
    ->addConnection('default','http','localhost',7474,true, 'neo4j', 'abcd')
    ->setAutoFormatResponse(true)
    ->build();
$a=0;
//intialising the array
$object=array();
//fetching array having details of all the employees
$q = 'MATCH (n:Emp) RETURN n';
$result = $client->sendCypherQuery($q)->getResult()->getTableFormat();
foreach($result as $row){
$obj[$a++]=$row['n'];
}
//returning the array requested by the client side through ajax call
echo json_encode($obj);  
?>
