<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

//get all customers
$app->get('/api/customers',function (Request $request, Response $response){
    //echo All CUSTOMERS;
	$sql = "SELECT * FROM customers";
	try {
		//get the DB Object
		$db = new db();
		//call the connect
		$db = $db->connect();   
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
	} catch (Exception $e) {
		echo '{"error":{"text": '.$e->getMessage().'}}';
	}
}); 

//get single customer
$app->get('/api/customer/{id}',function (Request $request, Response $response){
	$id = $request->getAttribute('id');
	$sql = "SELECT * FROM customers WHERE id = $id ";
	try {
		//get the DB Object
		$db = new db();
		//call the connect
		$db = $db->connect();   
        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);	
	} catch (Exception $e) {
		echo '{"error":{"text": '.$e->getMessage().'}}';
	}
});

//Insert new customer
$app->post('/api/customer/add',function (Request $request, Response $response){
	$first_name = $request->getParam('first_name');
	$last_name = $request->getParam('last_name');
	$phone = $request->getParam('phone');
	$email = $request->getParam('email');
	$address = $request->getParam('address');
	$city = $request->getParam('city');
	$state = $request->getParam('state');
	$sql = "INSERT INTO `customers` (`first_name`, `last_name`, `phone`, `email`, `address`, `city`, `state`) VALUES (:first_name,:last_name,:phone,:email,:address,:city,:state)";
	// $sql = "INSERT INTO `customers`(`first_name`, `last_name`, `phone`, `email`, `address`, `city`, `state`) VALUES (:first_name,:last_name,:phone,:$email,:address,:city,:state)";
	try {
		//get the DB Object
		$db = new db();
		//call the connect
		$db = $db->connect();   
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':phone', $phone);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':city', $city);
		$stmt->bindParam(':state', $state);
		$stmt->execute();
		echo '{"notice":{"text": "Customer Added"} }';
	} catch (Exception $e) {
		echo '{"error":{"text": '.$e->getMessage().'}}';
	}
});

//Update customer
$app->put('/api/customer/update/{id}',function (Request $request, Response $response){
	$id = $request->getAttribute('id');
	$first_name = $request->getParam('first_name');
	$last_name = $request->getParam('last_name');
	$phone = $request->getParam('phone');
	$email = $request->getParam('email');
	$address = $request->getParam('address');
	$city = $request->getParam('city');
	$state = $request->getParam('state');
	$sql = "UPDATE `customers` SET `first_name`=:first_name,`last_name`=:last_name,`phone`=:phone,`email`=:email,`address`=:address,`city`=:city,`state`=:state WHERE `id` = $id";
	try {
		//get the DB Object
		$db = new db();
		//call the connect
		$db = $db->connect();   
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':phone', $phone);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':city', $city);
		$stmt->bindParam(':state', $state);
		$stmt->execute();
		echo '{"notice":{"text": "Customer Updated"} }';
	} catch (Exception $e) {
		echo '{"error":{"text": '.$e->getMessage().'}}';
	}
});

//Delete customer
$app->delete('/api/customer/delete/{id}',function (Request $request, Response $response){
	$id = $request->getAttribute('id');
	$sql = "DELETE FROM `customers` WHERE id = $id ";
	try {
		//get the DB Object
		$db = new db();
		//call the connect
		$db = $db->connect();   
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice":{"text": "Customer Deleted"} }';
	} catch (Exception $e) {
		echo '{"error":{"text": '.$e->getMessage().'}}';
	}
});