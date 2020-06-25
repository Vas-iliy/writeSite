<?php

function dbConnect () {
	static $connection;
	if ($connection === null) {
		$connection = new PDO('mysql:host=localhost; dbname=write; charset=UTF8', 'root', 'root',
			[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
	}
	return $connection;
}

function dbQuery ($sql, $params) {
	$connection = dbConnect();
	$data = $connection->prepare($sql);
	$data->execute($params);

	return $data;
}