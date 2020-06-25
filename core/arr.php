<?php

function extractFields($target, $fields) {
	$arr = [];
	foreach ($fields as $field) {
		$arr[$field] = $target[$field];
	}
	return $arr;
}