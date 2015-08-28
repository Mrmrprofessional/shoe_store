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
            $id = 1;
            $brand = "Wash the dog";
            $test_store = new Brand($brand, $id);

            //Act
            $result = $test_store->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $brand = "Wash the dog";
            $id = 1;
            $test_store = new Brand($brand, $id);

            //Act
            $test_store->save();

            //Assert
            $result = Brand::getAll();
            $this->assertEquals($test_store, $result[0]);
        }

        function testSaveSetsId()
        {
            //Arrange
            $brand = "Wash the dog";
            $id = 1;
            $test_store = new Brand($brand, $id);

            //Act
            $test_store->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_store->getId()));
        }

        function testGetAll()
        {
            //Arrange
            $brand = "Wash the dog";
            $id = 1;
            $test_store = new Brand($brand, $id);
            $test_store->save();


            $brand2 = "Water the lawn";
            $id2 = 2;
            $test_store2 = new Brand($brand2, $id2);
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
            $id = 1;
            $test_store = new Brand($brand, $id);
            $test_store->save();

            $brand2 = "Water the lawn";
            $id2 = 2;
            $test_store2 = new Brand($brand2, $id2);
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
            $id = 1;
            $test_store = new Brand($brand, $id);
            $test_store->save();

            $brand2 = "Water the lawn";
            $id2 = 2;
            $test_store2 = new Brand($brand2, $id2);
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
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $brand = "File reports";
            $id2 = 2;
            $test_store = new Brand($brand, $id2);
            $test_store->save();

            //Act
            $test_store->addStore($test_store);

            //Assert
            $this->assertEquals($test_store->getStores(), [$test_store]);
        }

        function testGetStores()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $name2 = "Volunteer stuff";
            $id2 = 2;
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();

            $brand = "File reports";
            $id3 = 3;
            $test_store = new Brand($brand, $id3);
            $test_store->save();

            //Act
            $test_store->addStore($test_store);
            $test_store->addStore($test_store2);

            //Assert
            $this->assertEquals($test_store->getStores(), [$test_store, $test_store2]);
        }

    }
?>
