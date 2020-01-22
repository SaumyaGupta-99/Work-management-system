<?php
require_once 'vendor/autoload.php';
//making connection to graph database
use Neoxygen\NeoClient\ClientBuilder;
$client = ClientBuilder::create()
    ->addConnection('default','http','localhost',7474,true, 'neo4j', 'abcd')
    ->setAutoFormatResponse(true)
    ->build();
$name = testinput($_POST["name"]);
//checking if the name is in correct format or not
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
       echo "Only letters and white space allowed"."</br>"; 
    }
    else{
 header("Location:register.html");
    }
  $empid=testinput($_POST['empid']);
  $password=testinput($_POST['password']);
  //hashing the password for storing it in secure way
	$hashedpassword=password_hash($password,PASSWORD_DEFAULT);
//removing spaces from the input data and converting it to the optimized format to prevent sql injections 
function testinput($data){
		$data=trim($data);
		$data=stripslashes($data);
		$data=htmlspecialchars($data);
		return $data;
	}
  //insertig data in the graph database
$client->sendCypherQuery('CREATE (n:Emp) SET n += {infos}', ['infos' => ['name' => $name, 'password' =>$hashedpassword, 'empid'=>$empid ,'wc'=>0]]);
//since employee id is the primary key so attching unique constraint to it
$q1='CREATE CONSTRAINT ON (n:Emp) ASSERT n.empid IS UNIQUE';
//creating a relationship between admin and employee
$q='MATCH (admin:ad),(n:Emp) where n.empid={empid} CREATE (admin)-[r:employer_of]->(n)';
$params = ['empid' => $empid];
//executing the query
$client->sendCypherQuery($q1,$params);
$client->sendCypherQuery($q,$params);
//if successful execution of all the queries then returning to the index page
header("Location:index.html");

?>