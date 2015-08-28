<?php
    class Store
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO stores (name) VALUES ('{$this->getName()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE stores SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM stores_tasks WHERE store_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_stores = $GLOBALS['DB']->query("SELECT * FROM stores;");
            $stores = array();
            foreach($returned_stores as $store) {
                $name = $store['name'];
                $id = $store['id'];
                $new_store = new Store($name, $id);
                array_push($stores, $new_store);
            }
            return $stores;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM stores;");
        }

        static function find($search_id)
        {
            $found_store = null;
            $stores = Store::getAll();
            foreach($stores as $store) {
                $store_id = $store->getId();
                if ($store_id == $search_id) {
                  $found_store = $store;
                }
            }
            return $found_store;
        }

        function addTask($task)
        {
            $GLOBALS['DB']->exec("INSERT INTO stores_tasks (store_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
        }

        function getTasks()
        {
           $query = $GLOBALS['DB']->query("SELECT task_id FROM stores_tasks WHERE store_id = {$this->getId()};");
           $task_ids = $query->fetchAll(PDO::FETCH_ASSOC);

           $tasks = array();
           foreach($task_ids as $id) {
               $task_id = $id['task_id'];
               $result = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE id = {$task_id};");
               $returned_task = $result->fetchAll(PDO::FETCH_ASSOC);
               $description = $returned_task[0]['description'];
               $id = $returned_task[0]['id'];
               $mark = $returned_task[0]['mark'];
               $new_task = new Task($description, $mark, $id);
               array_push($tasks, $new_task);
           }
           return $tasks;
        }
    }
?>
