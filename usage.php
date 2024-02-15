<?php

/*
 * Example of usage below
 */

require './NP.php';

$np = new \NovaPost\NP('');

$data = [
  'method' => 'getAreas', //  getAreas |   getCitiesByArea   |   getWarehousesByCity
  'query' => '',  // for search, not obligatory. Should be used for getCitiesByArea | getWarehousesByCity
  'properities' => [
    'city' => '', // obligatory for getWarehousesByCity
    'area' => '' // obligatory for getCitiesByArea
  ]
];




if(!$data['method'] || !strlen($data['method']) > 0) {
    // make error throwing
    return;
}

if($data['method'] == 'getCitiesByArea' && !strlen($data['properities']['area']) > 0) {
    // make error throwing
    return;
}

if($data['method'] == 'getWarehousesByCity' && !strlen($data['properities']['city']) > 0) {
    // make error throwing
    return;
}


// switching 3 possible methods
switch ($data['method']) {
    case 'getAreas':
        $response = $np->getAreas();
        break;
    case 'getCitiesByArea':
        $response = $np->getCitiesByArea($data['properities']['area'], $data['query']);
        break;
    case 'getWarehousesByCity':
        $response = $np->getWarehousesByCity($data['properities']['city'], $data['query']);
        break;
}


//Outputting
if($response && count($response) > 0) {
    foreach ($response as $item ) {
        echo $item['Description'] . '<br>';
    }
    return;
}


return $response;

