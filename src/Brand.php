<?php
    class Brand
    {
        private $brand;
        private $id;

        function __construct($brand, $id = null)
        {
            $this->brand = $brand;
            $this->id = $id;
        }


        function setBrand($new_brand)
        {
            $this->brand = (string) $new_brand;
        }

        function getBrand()
        {
            return $this->brand;
        }



        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO brands (brand) VALUES ('{$this->getBrand()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM brands;");
        }


        static function getAll()
        {
            $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
            $brands = array();
            foreach($returned_brands as $brand) {
                $brandname = $brand['brand'];
                $id = $brand['id'];
                $new_brand = new Brand($brandname,  $id);
                array_push($brands, $new_brand);
            }
            return $brands;
        }

        static function find($search_id)
        {
            $found_brand = null;
            $brands = Brand::getAll();
            foreach($brands as $brand) {
                $brand_id = $brand->getId();
                if ($brand_id == $search_id) {
                  $found_brand = $brand;
                }
            }
            return $found_brand;
        }


        function addStore($store)
        {
            $GLOBALS['DB']->exec("INSERT INTO brands_stores (store_id, brand_id) VALUES ({$store->getId()}, {$this->getId()});");
        }

        function getStores()
        {
           $returned_stores = $GLOBALS['DB']->query("SELECT stores.* FROM brands
                JOIN brands_stores ON (brands.id = brands_stores.brand_id)
                JOIN stores ON (brands_stores.store_id = stores.id)
                WHERE brands.id = {$this->getId()};");

           $stores = array();
           foreach($returned_stores as $store) {
               $storename = $store['name'];
               $id = $store['id'];
               $new_store = new Store($storename, $id);
               array_push($stores, $new_store);
           }
           return $stores;
        }
    }
?>
