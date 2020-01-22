<?php
//starting the session
session_start();
require_once 'vendor/autoload.php';
//connecting to the database
use Neoxygen\NeoClient\ClientBuilder;
$client = ClientBuilder::create()
    ->addConnection('default','http','localhost',7474,true, 'neo4j', 'abcd')
    ->setAutoFormatResponse(true)
    ->build();
  // updating the value of work count variable
$_SESSION['wc']=$_SESSION['wc']+1;
$emp=$_SESSION['empid'];
//updating the value of work count variable in the database
$q=' MERGE (n{empid:{emp}}) ON MATCH SET n.wc =n.wc+1 RETURN n';
$params = ['emp' => $emp];
//running the query
if($client->sendCypherQuery($q,$params)){
	//after successful execution of the query returning to the home page
	$_SESSION['status']="active";
	header("Location:home.html");
}
?>
