<?php
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Book.php';
require_once 'classes/Furniture.php';
require_once 'classes/DVD.php';

//Get database configuration
$config = require 'config/config.php';

// Database connection
$db = new Database($config['host'], $config['dbname'], $config['username'], $config['password']);
$pdo = $db->getConnection();

$name = $sku = $price = $type = $weight = $size = $height = $width = $length = $specific_info = '';
$errorMessage = '';

// Factories with product objects
$productFactories = [
    'Book' => function ($sku, $name, $price) {
        return Book::createFromPost($sku, $name, $price);
    },
    'DVD' => function ($sku, $name, $price) {
        return DVD::createFromPost($sku, $name, $price);
    },
    'Furniture' => function ($sku, $name, $price) {
        return Furniture::createFromPost($sku, $name, $price);
    }
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    // Create product
    if (array_key_exists($type, $productFactories)) {
        $product = $productFactories[$type]($sku, $name, $price);

        // Product validation (from Product.php abstract class)
        $errors = $product->validate($db);

        if (empty($errors)) {
            try {
                $product->saveToDatabase($pdo);
                header('Location: index.php');
                exit();
            } catch (Exception $e) {
                $errorMessage = "An error occurred while saving the product: " . $e->getMessage();
            }
        } else {
            // Show error message from error array in validate()
            $errorMessage = implode('', $errors);
        }
    } else {
        $errorMessage = "Please submit required data."; //if type not selected
    }
}

// Product adding form
include 'templates/add_product_form.php';
