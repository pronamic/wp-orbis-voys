<?php

header( 'Content-Type: application/json' );

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

$parse_version = '2.0.0';

$query = '
	SELECT
		*
	FROM
		orbis_voys_notifications
	WHERE
		parse_version IS NULL
			OR
		parse_version != :parse_version
	;
';

$statement = $pdo->prepare( $query );

$statement->execute( array(
	'parse_version' => $parse_version,
) );

$notifications = $statement->fetchAll();

$query = '
	UPDATE
		orbis_voys_notifications
	SET
		call_id = :call_id,
		merged_id = :merged_id,
		generated_at = :generated_at,
		status = :status,
		version = :version,
		direction = :direction,
		caller_number = :caller_number,
		caller_name = :caller_name,
		caller_account_number = :caller_account_number, 
		destination_number = :destination_number,
		parse_version = :parse_version
	WHERE
		id = :id
	;
';

$statement = $pdo->prepare( $query );

foreach ( $notifications as $notification ) {
	if ( ! array_key_exists( 'post_data', $notification ) ) {
		continue;
	}

	$post_data_json = json_decode( $notification['post_data'] );

	if ( ! is_object( $post_data_json ) ) {
		continue;
	}

	$generated_at = new DateTime( $post_data_json->timestamp );

	$result = $statement->execute( array(
		'call_id'               => $post_data_json->call_id,
		'merged_id'             => isset( $post_data_json->merged_id ) ? $post_data_json->merged_id : null,
		'generated_at'          => $generated_at->format( 'Y-m-d H:i:s.u' ),
		'status'                => $post_data_json->status,
		'version'               => $post_data_json->version,
		'direction'             => $post_data_json->direction,
		'caller_number'         => $post_data_json->caller->number,
		'caller_name'           => $post_data_json->caller->name,
		'caller_account_number' => $post_data_json->caller->account_number,
		'destination_number'    => $post_data_json->destination->number,
		'parse_version'         => $parse_version,
		'id'                    => $notification['id'],
	) );
}

$response = (object) array(
	'success' => true,
	'data'    => (object) array(
		'notifications' => $notifications,
	),
);

echo json_encode( $response );
