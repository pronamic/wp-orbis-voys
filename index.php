<?php

header( 'Content-Type: application/json' );

$post_data = file_get_contents( 'php://input' );

if ( empty( $post_data ) ) {
	$response = (object) array(
		'success' => false,
		'message' => 'Post data is empty.',
	);

	echo json_encode( $response );

	return;
}

$ip_address   = null;
$user_agent   = null;
$request_time = null;

if ( array_key_exists( 'REMOTE_ADDR', $_SERVER ) ) {
	$ip_address = $_SERVER['REMOTE_ADDR'];
}

if ( array_key_exists( 'HTTP_USER_AGENT', $_SERVER ) ) {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
}

if ( array_key_exists( 'REQUEST_TIME_FLOAT', $_SERVER ) ) {
	$request_time = $_SERVER['REQUEST_TIME_FLOAT'];
}

$host    = '127.0.0.1';
$db      = '';
$user    = '';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
);

$pdo = new PDO( $dsn, $user, $pass, $options );

$query = 'INSERT INTO orbis_voys_notifications ( post_data, ip_address, user_agent, request_time ) VALUES ( ?, ?, ?, FROM_UNIXTIME( ? ) );';

$statement = $pdo->prepare( $query );

$success = $statement->execute( array(
	$post_data,
	$ip_address,
	$user_agent,
	$request_time,
) );

$id = $pdo->lastInsertId();

$response = (object) array(
	'success' => $success,
	'data'    => (object) array(
		'id'         => $id,
		'ip_address' => $ip_address,
		'user_agent' => $user_agent,
	),
);

echo json_encode( $response );
