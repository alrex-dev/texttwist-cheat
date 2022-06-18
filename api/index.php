<?php
define('ROOT', dirname( __FILE__ ));
define( 'SEP', DIRECTORY_SEPARATOR );

//importing the library/file importer
require_once( ROOT.SEP.'mysql.php' );

$db = new Database();

function validate_words() {
    global $received_data, $db;
    
    $words = $received_data->words;
    $group = $received_data->grp;
    
    $sql = "SELECT word FROM d_english WHERE word IN ('".implode("', '", $words)."')";
    $results = $db->getResults($sql);
    
    $data = array('results' => $results, 'grp' => $group);
    
    echo json_encode($data);
}

function log_visitor() {
    global $received_data, $db;
    
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $sql = "INSERT INTO visitors(v_ip) VALUES ('".$ip."')";
    
    $result = $db->execute($sql);
    
    $r = ($result) ? 1 : 0;
    
    echo json_encode( array('status' => $r) );
}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');
header('Content-Type: application/json; charset=utf-8');

$received_data = json_decode(file_get_contents("php://input"));

$fn = ($received_data && isset($received_data->fn)) ? $received_data->fn : '';

switch($fn) {
    case 'log_visitor':
        log_visitor();
    break;
    
    case 'validate_words':
    default:
        validate_words();
    break;
}

?>