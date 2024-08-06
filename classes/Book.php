<?php

class Book extends Product {
    private $weight;

    /**
     * @param $sku
     * @param $name
     * @param $price
     * @param $type
     * @param $weight
     */
    public function __construct(
        $sku,
        $name,
        $price,
        $type,
        $weight
    ) {
        parent::__construct(
            $sku,
            $name,
            $price,
            $type
        );

        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * @param $errors
     * @return mixed
     */
    protected function validateSpecificFields($errors) {
        // Checking, if in parent class already are errors
        if (empty($errors)) {
            if (empty($this->weight)) {
                $errors[] = "Please, submit required data.";
            } elseif (!is_numeric($this->weight) || $this->weight <= 0) {
                $errors[] = "Please, provide the data of indicated type.";
            }
        }
    
        return $errors;
    }

    /**
     * @param $sku
     * @param $name
     * @param $price
     * @return self
     */
    public static function createFromPost($sku, $name, $price) {
        $weight = $_POST['weight'] ?? ''; //get weight from form input

        return new self($sku, $name, $price, 'Book', $weight);
    }

    /**
     * @param $pdo
     * @return void
     */
    public function saveToDatabase($pdo) {
        // Use ON DUPLICATE KEY UPDATE for table - products
        $query = "INSERT INTO products (sku, name, price, type) 
                  VALUES (:sku, :name, :price, :type)
                  ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price), type = VALUES(type)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'sku' => $this->getSKU(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'type' => $this->getType()
        ]);

        $productId = $pdo->lastInsertId();

        // Use ON DUPLICATE KEY UPDATE for table - books
        $query = "INSERT INTO books (product_id, weight) 
                  VALUES (:product_id, :weight)
                  ON DUPLICATE KEY UPDATE weight = VALUES(weight)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'product_id' => $productId,
            'weight' => $this->getWeight()
        ]);
    }
}
