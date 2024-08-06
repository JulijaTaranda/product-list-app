<?php
require_once 'config/config.php';  
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Book.php';
require_once 'classes/Furniture.php';
require_once 'classes/DVD.php';

// Get database configuration
$config = require 'config/config.php';

// Db connection
$db = new Database($config['host'], $config['dbname'], $config['username'], $config['password']);
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_products'])) {
    $ids = $_POST['product_ids'] ?? [];

    if (!empty($ids)) {
        $db->deleteProducts($ids);
    }
}

$products = $db->getAllProducts();

//html of product list
include 'templates/product_list.php';
