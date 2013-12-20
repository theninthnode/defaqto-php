<?php 
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../../vendor/autoload.php'; // Autoload files using Composer autoload
use TheNinthNode\Defaqto;

echo 'hello';
echo Defaqto::world();