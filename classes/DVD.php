<?php

class DVD extends Product {
    private $size;

    /**
     * @param $sku
     * @param $name
     * @param $price
     * @param $type
     * @param $size
     */
    public function __construct(
        $sku,
        $name,
        $price,
        $type,
        $size
    ) {
        parent::__construct(
            $sku,
            $name,
            $price,
            $type
        );

        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @param $errors
     * @return mixed
     */
    protected function validateSpecificFields($errors) {
         // Checking, if in parent class already are errors
        if (empty($errors)) {
            if (empty($this->size)) {
                $errors[] = "Please, submit required data.";
            } elseif (!is_numeric($this->size) || $this->size <= 0) {
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
        $size = $_POST['size'] ?? '';  // Get size from form input

        return new self($sku, $name, $price, 'DVD', $size);
    }

    /**
     * @param $pdo
     * @return void
     */
    public function saveToDatabase($pdo) {
        // Use ON DUPLICATE KEY UPDATE
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
    
        $query = "INSERT INTO dvds (product_id, size) 
                  VALUES (:product_id, :size)
                  ON DUPLICATE KEY UPDATE size = VALUES(size)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'product_id' => $productId,
            'size' => $this->size
        ]);
    }
}
