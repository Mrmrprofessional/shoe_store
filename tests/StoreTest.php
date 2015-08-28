<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Store.php";
    require_once "src/Brand.php";

    $server = 'mysql:host=localhost;dbname=shoe_store_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class StoreTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
          Store::deleteAll();
          Brand::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "Kitchen chores";
            $test_store = new Store($name);

            //Act
            $result = $test_store->getName();

            //Assert
            $this->assertEquals($name, $result);

        }

        function testSetName()
        {
            //Arrange
            $name = "Kitchen chores";
            $test_store = new Store($name);

            //Act
            $test_store->setName("Home chores");
            $result = $test_store->getName();

            //Assert
            $this->assertEquals("Home chores", $result);
        }

        function testGetId()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_store = new Store($name, $id);

            //Act
            $result = $test_store->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            //Act
            $result = Store::getAll();

            //Assert
            $this->assertEquals($test_store, $result[0]);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $new_name = "Home stuff";

            //Act
            $test_store->update($new_name);

            //Assert
            $this->assertEquals("Home stuff", $test_store->getName());
        }

        function testDeleteStore()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $name2 = "Home stuff";
            $id2 = 2;
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();


            //Act
            $test_store->delete();

            //Assert
            $this->assertEquals([$test_store2], Store::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $name2 = "Home stuff";
            $id2 = 2;
            $test_store = new Store($name, $id);
            $test_store->save();
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();

            //Act
            $result = Store::getAll();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Wash the dog";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $name2 = "Water the lawn";
            $id2 = 2;
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();

            //Act
            Store::deleteAll();

            //Assert
            $result = Store::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $name = "Wash the dog";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $name2 = "Home stuff";
            $id2 = 2;
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();

            //Act
            $result = Store::find($test_store->getId());

            //Assert
            $this->assertEquals($test_store, $result);
        }

        // function testAddBrand()
        // {
        //     //Arrange
        //     $name = "Work stuff";
        //     $id = 1;
        //     $test_store = new Store($name, $id);
        //     $test_store->save();
        //
        //     $description = "File reports";
        //     $id2 = 2;
        //     $test_brand = new Brand($description, $id2);
        //     $test_brand->save();
        //
        //     //Act
        //     $test_store->addBrand($test_brand);
        //
        //     //Assert
        //     $this->assertEquals($test_store->getBrands(), [$test_brand]);
        // }
        //
        // function testGetBrands()
        // {
        //     //Arrange
        //     $name = "Work stuff";
        //     $id = 1;
        //     $test_store = new Store($name, $id);
        //     $test_store->save();
        //
        //     $description = "Wash the dog";
        //     $id2 = 2;
        //     $test_brand = new Brand($description, $id2);
        //     $test_brand->save();
        //
        //     $description2 = "Take out the trash";
        //     $id3 = 3;
        //     $test_brand2 = new Brand($description2, $id3);
        //     $test_brand2->save();
        //
        //     //Act
        //     $test_store->addBrand($test_brand);
        //     $test_store->addBrand($test_brand2);
        //
        //     //Assert
        //     $this->assertEquals($test_store->getBrands(), [$test_brand, $test_brand2]);
        // }
        //
        // function testDelete()
        // {
        //     //Arrange
        //     $name = "Work stuff";
        //     $id = 1;
        //     $test_store = new Store($name, $id);
        //     $test_store->save();
        //
        //     $description = "File reports";
        //     $id2 = 2;
        //     $test_brand = new Brand($description, $id2);
        //     $test_brand->save();
        //
        //     //Act
        //     $test_store->addBrand($test_brand);
        //     $test_store->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_brand->getCategories());
        // }
    }

?>
