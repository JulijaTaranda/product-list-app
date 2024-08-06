<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    /**
     * @param $host
     * @param $db_name
     * @param $username
     * @param $password
     */
    public function __construct(
        $host = "localhost",
        $db_name = "products",
        $username = "root",
        $password = "new_cookies"
    ) {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "DB connection failed: " . $e->getMessage();
        }

        return $this->conn;
    }

    /**
     * Check if SKU is unique
     *
     * @param $sku
     * @return bool
     */
    public function isSkuUnique($sku)
    {
        $query = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['sku' => $sku]);

        return $stmt->fetchColumn() == 0;
    }

    /**
     * Get product list with all products
     *
     * @return mixed
     */
    public function getAllProducts() {
        $query = "SELECT p.id, p.sku, p.name, p.price, p.type, 
                         CASE 
                             WHEN p.type = 'Book' THEN IFNULL(b.weight, 'N/A')
                             WHEN p.type = 'DVD' THEN IFNULL(d.size, 'N/A')
                             WHEN p.type = 'Furniture' THEN 
                                CONCAT(
                                IFNULL(f.height,'N/A'), 'x',
                                IFNULL(f.width,'N/A'), 'x',
                                IFNULL(f.length,'N/A')
                            )
                             ELSE 'N/A'
                         END AS specific_info
                  FROM products p
                  LEFT JOIN books b ON p.id = b.product_id
                  LEFT JOIN dvds d ON p.id = d.product_id
                  LEFT JOIN furniture f ON p.id = f.product_id
                  ORDER BY p.id"; // List of products ORDERED BY ID in database
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete products
     *
     * @param $ids
     * @return void
     * @throws Exception
     */
    public function deleteProducts($ids) {
        //if no checkboxes is selected
        if (empty($ids)) {
            return;
        }

        $this->conn->beginTransaction();

        try {
            // delete from child tables
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $queries = [
                "DELETE FROM books WHERE product_id IN ($placeholders)",
                "DELETE FROM dvds WHERE product_id IN ($placeholders)",
                "DELETE FROM furniture WHERE product_id IN ($placeholders)",
                "DELETE FROM products WHERE id IN ($placeholders)"
            ];

            foreach ($queries as $query) {
                $stmt = $this->conn->prepare($query);
                $stmt->execute($ids);
            }
            
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
