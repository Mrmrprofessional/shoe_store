<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";
    require_once "src/Store.php";

    $server = 'mysql:host=localhost;dbname=shoe_store_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Brand::deleteAll();
            Store::deleteAll();
        }

        function testGetBrand()
        {
            //Arrange
            $brand = "Do dishes.";
            $test_store = new Brand($brand);

            //Act
            $result = $test_store->getBrand();

            //Assert
            $this->assertEquals($brand, $result);
        }

        function testSetBrand()
        {
            //Arrange
            $brand = "Do dishes.";
            $test_store = new Brand($brand);

            //Act
            $test_store->setBrand("Drink coffee.");
            $result = $test_store->getBrand();

            //Assert
            $this->assertEquals("Drink coffee.", $result);
        }

        function testGetId()
        {
            //Arrange
            $brand = "Wash the dog";
            $test_store = new Brand($brand);

            //Act
            $result = $test_store->getId();

            //Assert
            $this->assertEquals($test_store->getId(), $result);
        }

        function testSave()
        {
            //Arrange
            $brand = "Wash the dog";
            $test_brand = new Brand($brand);

            //Act
            $test_brand->save();

            //Assert
            $result = Brand::getAll();
            $this->assertEquals($test_brand, $result[0]);
        }

        function testSaveSetsId()
        {
            //Arrange
            $brand = "Wash the dog";
            $test_store = new Brand($brand);

            //Act
            $test_store->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_store->getId()));
        }

        function testGetAll()
        {
            //Arrange
            $brand = "Wash the dog";
            $test_store = new Brand($brand);
            $test_store->save();


            $brand2 = "Water the lawn";
            $test_store2 = new Brand($brand2);
            $test_store2->save();

            //Act
            $result = Brand::getAll();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $brand = "Wash the dog";
            $test_store = new Brand($brand);
            $test_store->save();

            $brand2 = "Water the lawn";
            $test_store2 = new Brand($brand2);
            $test_store2->save();

            //Act
            Brand::deleteAll();

            //Assert
            $result = Brand::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $brand = "Wash the dog";
            $test_store = new Brand($brand);
            $test_store->save();

            $brand2 = "Water the lawn";
            $test_store2 = new Brand($brand2);
            $test_store2->save();

            //Act
            $result = Brand::find($test_store->getId());

            //Assert
            $this->assertEquals($test_store, $result);
        }


        function testAddStore()
        {
            //Arrange
            $name = "Work stuff";
            $test_store = new Store($name);
            $test_store->save();

            $brand = "File reports";
            $test_brand = new Brand($brand);
            $test_brand->save();

            //Act
            $test_brand->addStore($test_store);

            //Assert
            $this->assertEquals($test_brand->getStores(), [$test_store]);
        }

        function testGetStores()
        {
            //Arrange
            $name = "Work stuff";
            $test_store = new Store($name);
            $test_store->save();

            $name2 = "Volunteer stuff";
            $test_store2 = new Store($name2);
            $test_store2->save();

            $brand = "File reports";
            $test_brand = new Brand($brand);
            $test_brand->save();

            //Act
            $test_brand->addStore($test_store);
            $test_brand->addStore($test_store2);

            //Assert
            $this->assertEquals($test_brand->getStores(), [$test_store, $test_store2]);
        }

    }
?>
