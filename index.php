<!DOCTYPE html>
<!--
  Jan Chris Tacbianan
  IS218-102 - Spring 2015
  Homework 3
  

-->
<html>
    <head>
        <title>
            IS 218 - Homework 3
        </title>
    </head>
    <body>
        <?php

        class DatabaseAccess {

            private $connection;

            public static function getInstance() {
                static $instance = null;
                if (null === $instance) {
                    $instance = new DatabaseAccess();
                    $instance->connect();
                }
                return $instance;
            }

            private function connect() {
                $this->connection = new PDO('mysql:host=' . Constants::$SQLHOST . ';dbname=' . Constants::$SQLDB, Constants::$SQLUSER, Constants::$SQLPASS);
            }

            public function retrieveSingleResult($query) {
                $stmt = $this->connection->prepare($query);
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;
            }

        }

        class Page {

            private $dbUtil;

            /**
             * Constructor
             * Constructs the essential components of the page.
             */
            public function __construct() {
                $this->dbUtil = DatabaseAccess::getInstance();
                echo '<h2>IS 218 Homework Assignment 3</h2>';
            }

            /**
             * Destructor
             * Outputs the page onces the page object is disposed of.
             */
            public function __destruct() {
                if (!(isset($_GET["entry"]))) {
                    $this->menu();
                } else {
                    $id = $_GET["entry"];
                    $this->generateResult($id);
                }
            }

            public function menu() {
                echo <<< INS
                    <p>Click on the Query that you would like to access:</p>
                    <ul>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=1'>Query 1</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=2'>Query 2</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=3'>Query 3</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=4'>Query 4</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=5'>Query 5</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=6'>Query 6</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=7'>Query 7</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=8'>Query 8</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=9'>Query 9</a></li>
                        <li><a href='{$_SERVER['PHP_SELF']}?entry=10'>Query 10</a></li>
                    </ul>
INS;
            }

            public function generateResult($num) {
                switch ($num) {
                    case 1:
                        $query = 'select first_name, last_name, max(salary) from (select first_name, last_name, salary from employees right join salaries on employees.emp_no=salaries.emp_no) as employeesalaries';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 1: Who is the highest paid employee?</h3>';
                        echo '<p> The highest paid is '.$result['first_name'].' '.$result['last_name'].' with a salary of $'.$result['max(salary)'].'</p>';
                        break;
                    case 2:
                        break;
                    case 3:
                        break;
                    case 4:
                        break;
                    case 5:
                        break;
                    case 6:
                        break;
                    case 7:
                        break;
                    case 8:
                        break;
                    case 9:
                        break;
                    case 10:
                        break;
                }
                echo "<a href='".$_SERVER['PHP_SELF']."'>Return to Menu</a>";
            }

        }

        class Constants {

            public static $SQLHOST = 'localhost';
            public static $SQLUSER = 'test';
            public static $SQLPASS = 'password';
            public static $SQLDB = 'employees';

        }

        $page = new Page();
        ?>
    </body>
</html>