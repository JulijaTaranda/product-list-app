<?php

class Furniture extends Product {
    private $height;
    private $width;
    private $length;

    /**
     * @param $sku
     * @param $name
     * @param $price
     * @param $type
     * @param $height
     * @param $width
     * @param $length
     */
    public function __construct(
        $sku,
        $name,
        $price,
        $type,
        $height,
        $width,
        $length
    ) {
        parent::__construct(
            $sku,
            $name,
            $price,
            $type
        );

        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    /**
     * @return mixed
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getLength() {
        return $this->length;
    }

    /**
     * @param $errors
     * @return mixed
     */
    protected function validateSpecificFields($errors) {
        // Checking, if in parent class already are errors
        if (empty($errors)) {
            if (empty($this->height) || empty($this->width) || empty($this->length)) {
                $errors[] = "Please, submit required data.";
            } elseif ((!is_numeric($this->height) || $this->height <= 0) || 
                      (!is_numeric($this->width) || $this->width <= 0) || 
                      (!is_numeric($this->length) || $this->length <= 0)) {
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
        $height = $_POST['height'] ?? '';
        $width = $_POST['width'] ?? '';
        $length = $_POST['length'] ?? '';

        return new self($sku, $name, $price, 'Furniture', $height, $width, $length);
    }

    /**
     * @param $pdo
     * @return void
     */
    public function saveToDatabase($pdo) {
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
    
        $query = "INSERT INTO furniture (product_id, height, width, length) 
                  VALUES (:product_id, :height, :width, :length)
                   ON DUPLICATE KEY UPDATE height = VALUES(height), width = VALUES(width), length = VALUES(length)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'product_id' => $productId,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
        ]);
    }
}
