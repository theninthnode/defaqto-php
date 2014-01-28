<?php 
use TheNinthNode\Defaqto\Defaqto;
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../../vendor/autoload.php'; // Autoload files using Composer autoload
$page = Defaqto::setup(2, '3b873a67ca0be246a83c5d09c09a2fef')->get('pages', array('page_id'=>1)); // replace with your app id, access token and page ID.
echo '---' . $page['name'] . '---';