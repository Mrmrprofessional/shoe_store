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
              $GLOBALS['DB']->exec("INSERT INTO categories (name) VALUES ('{$this->getName()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE store_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $categories = array();
            foreach($returned_categories as $store) {
                $name = $store['name'];
                $id = $store['id'];
                $new_store = new Store($name, $id);
                array_push($categories, $new_store);
            }
            return $categories;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM categories;");
        }

        static function find($search_id)
        {
            $found_store = null;
            $categories = Store::getAll();
            foreach($categories as $store) {
                $store_id = $store->getId();
                if ($store_id == $search_id) {
                  $found_store = $store;
                }
            }
            return $found_store;
        }

        function addTask($task)
        {
            $GLOBALS['DB']->exec("INSERT INTO categories_tasks (store_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
        }

        function getTasks()
        {
           $query = $GLOBALS['DB']->query("SELECT task_id FROM categories_tasks WHERE store_id = {$this->getId()};");
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
