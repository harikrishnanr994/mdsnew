<?php
namespace AngularFilemanager\LocalBridge;

include 'LocalBridge/Response.php';
include 'LocalBridge/Rest.php';
include 'LocalBridge/Translate.php';
include 'LocalBridge/FileManagerApi.php';

//Takes two arguments - base path without last slash (default: '$currentDirectory/../files'); language (default: 'en'); mute_errors (default: true, will call ini_set('display_errors', 0))
$fileManagerApi = new FileManagerApi(__DIR__ . '/../../files/EEDC1600047');

$rest = new Rest();
$rest->post([$fileManagerApi, 'postHandler'])
     ->get([$fileManagerApi, 'getHandler'])
     ->handle();
