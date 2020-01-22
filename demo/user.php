<?php
session_start();
require_once 'vendor/autoload.php';
//connecting to the database
use Neoxygen\NeoClient\ClientBuilder;
$client = ClientBuilder::create()
    ->addConnection('default','http','localhost',7474,true, 'neo4j', 'abcd')
    ->setAutoFormatResponse(true)
    ->build();
    // fetching input by the user using post request
    $name = ($_POST["name"]);
    $userpassword=($_POST['password']);
   //fetching details of all employees and the admin
$q = 'MATCH (n) RETURN n';
$result = $client->sendCypherQuery($q)->getResult()->getTableFormat();
 $k=0;
 foreach($result as $row){
 	//converting all special characters in the input string to html characters
	$username=htmlentities($row['n']['name']);
	$password=htmlentities($row['n']['password']);
	$empid=htmlentities($row['n']['empid']);
	$wc=htmlentities($row['n']['wc']);
//checking if the user is employee
if($name!='admin'){	
	//verifying username and password
if(password_verify($userpassword,$password)){
if($username==$name){
//after verfying the user logging him or her in to home page and setting session variables 
$_SESSION['name']=$name;
$_SESSION['empid']=$empid;
$_SESSION['wc']=$wc;
$_SESSION['password']=$password;		
$_SESSION['status']="active";
$k=1;
header("Location:home.html");
}
}
}
//if user is admin
else{
	//verfying the admin
if(($userpassword==$password)){
if($username==$name){
	// after verfying the admin logging him in and directing to admin page
$_SESSION['name']=$name;
$_SESSION['status']="active";
$k=1;
header("Location:admin.html");
}
}
}
}
if($k==0)
	{
		echo"Invalid Username or Password";	
		
	}
?>
