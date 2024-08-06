<?php

abstract class Product {
    protected $sku;
    protected $name;
    protected $price;
    protected $type;

    /**
     * @param $sku
     * @param $name
     * @param $price
     * @param $type
     */
    public function __construct(
        $sku,
        $name,
        $price,
        $type
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSKU() {
        return $this->sku;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param $db
     * @return mixed
     */
    public function validate($db) {
        $errors = [];

        if (empty($this->sku) || empty($this->name) || empty($this->price) || empty($this->type)) {
            $errors[] = "Please, submit required data. ";
        }

        if (!$db->isSkuUnique($this->sku) || !is_numeric($this->price) || $this->price <= 0 ) {
            if(!$db->isSkuUnique($this->sku)) {
                $errors[] = "Please, provide the data of indicated type. Sku must be unique. ";
            } else {
            $errors[] = "Please, provide the data of indicated type. ";
            }
        }
        return $this->validateSpecificFields($errors);
    }

    /**
     * @param $errors
     * @return mixed
     */
    abstract protected function validateSpecificFields($errors);

    /**
     * @param $db
     * @return mixed
     */
    abstract public function saveToDatabase($db);//create product and save to db
}
