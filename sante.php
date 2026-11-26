<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');
ob_start();
$termine = false;
register_shutdown_function(function () use (&$termine) {
    if($termine){
        return;
    }
    ob_end_clean();
    http_response_code(503);
    echo json_encode(array('ok' => false));
});
require 'inc/const.php';
require 'inc/functions.php';
$ret = MyPDO::getInstance()->query("SELECT 1 AS un");
if(!is_array($ret) || count($ret) !== 1){
    exit();
}
$termine = true;
ob_end_clean();
echo json_encode(array('ok' => true));
