<?php
    header("content-type: image/png");
    require_once 'config.php';
    $data = file_get_contents("http://" . CAM_USERNAME . ':' . CAM_PASSWORD . '@dlink.breizhcat.fr/1/image');
    echo $data;
?>