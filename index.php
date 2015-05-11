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
		<style>
		table, th, td {
			border: 1px solid black;
		}
		</style>
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

            public function retrieveResults($query) {
                $stmt = $this->connection->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll();
                return $result;
            }
			
			public function getConnection() {
				return $this->connection;
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
				if(isset($_POST['submit'])) {
					$this->generateResult(12);
				}
				else if(isset($_POST['mod'])) {
					$this->generateResult(15);
				}
                else if (!(isset($_GET["entry"]))) {
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
						<li><a href='{$_SERVER['PHP_SELF']}?entry=11'>Add Employee</a></li>
						<li><a href='{$_SERVER['PHP_SELF']}?entry=13'>Modify Employee</a></li>
                    </ul>
INS;
            }

            public function generateResult($num) {
                switch ($num) {
                    case 1:
                        $query = 'select first_name, last_name, max(salary) from (select first_name, last_name, salary from employees right join salaries on employees.emp_no=salaries.emp_no) as employeesalaries';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 1: Who is the highest paid employee?</h3>';
                        echo '<p> The highest paid is ' . $result['first_name'] . ' ' . $result['last_name'] . ' with a salary of $' . $result['max(salary)'] . '</p>';
                        break;
                    case 2:
                        $query = 'select first_name, last_name, max(salary) from (select first_name, last_name, salary from employees right join salaries on employees.emp_no=salaries.emp_no where salaries.from_date between \'1981-01-01\' and \'1985-12-31\') as employeesalaries';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 2: Who is the highest paid employee between 1985 and 1981?</h3>';
                        echo '<p> The highest paid is between 1985 and 1981 ' . $result['first_name'] . ' ' . $result['last_name'] . ' with a salary of $' . $result['max(salary)'] . '</p>';
                        break;
                    case 3:
                        $query = 'select first_name, last_name, max(salary), dept_name from (select first_name, last_name, salary, dept_name from employees join dept_manager on employees.emp_no=dept_manager.emp_no join salaries on employees.emp_no=salaries.emp_no join departments on departments.dept_no=dept_manager.dept_no) as employeesalaries';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 3: Which department has the highest paid department head</h3>';
                        //echo '<p> The highest paid is between 1985 and 1981 ' . $result['first_name'] . ' ' . $result['last_name'] . ' with a salary of $' . $result['max(salary)'] . '</p>';

                        echo '<p>The department with the highest paid department head is ' . $result['dept_name'] . '.</p>';
                        break;
                    case 4:
                        $query = 'select * from departments';
                        $result = $this->dbUtil->retrieveResults($query);
                        echo '<h3>Task 4: What are the titles of all the departments?</h3>';
                        echo '<ul>';
                        foreach ($result as $row) {
                            echo '<li>' . $row['dept_name'] . '</li>';
                        }
                        echo '</ul>';
                        break;
                    case 5:
                        $query = 'select first_name, last_name, dept_name, MAX(hire_date) as hire_date from (employees join dept_manager on dept_manager.emp_no=employees.emp_no join departments on dept_manager.dept_no=departments.dept_no) group by dept_name';
                        $result = $this->dbUtil->retrieveResults($query);
                        echo '<h3>Task 5: Who are the current department heads?</h3>';
                        echo '<ul>';
                        foreach ($result as $row) {
                            echo '<li>' . $row['first_name'] . ' ' . $row['last_name'] . ' for ' . $row['dept_name'] . '</li>';
                        }
                        echo '</ul>';
                        break;
                    case 6:
                        $query = 'select first_name, last_name, max(salary) from (select first_name, last_name, salary from employees right join salaries on employees.emp_no=salaries.emp_no where employees.emp_no not in (select emp_no from dept_manager)) as employeesalaries';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 6: Who is the highest paid employee that is not a department head?</h3>';
                        echo '<p> The highest paid employee that is not a department head is ' . $result['first_name'] . ' ' . $result['last_name'] . ' with a salary of $' . $result['max(salary)'] . '</p>';
                        break;
                    case 7:
                        $query = 'select first_name, last_name, min(salary) from (select first_name, last_name, salary from employees right join salaries on employees.emp_no=salaries.emp_no) as employeesalaries';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 7: Who is the lowest paid employee?</h3>';
                        echo '<p> The lowest paid is ' . $result['first_name'] . ' ' . $result['last_name'] . ' with a salary of $' . $result['min(salary)'] . '</p>';
                        break;
                    case 8:
                        $query = "select dept_name, count(emp_no) from departments left join dept_emp on dept_emp.dept_no=departments.dept_no where dept_emp.to_date='9999-01-01' group by departments.dept_no";
                        $result = $this->dbUtil->retrieveResults($query);
                        echo '<h3>Task 8:  How many employees currently work in each department?</h3>';
                        echo '<ul>';
                        foreach ($result as $row) {
                            echo '<li>' . $row['dept_name'] . ' has ' . $row['count(emp_no)'] . ' employees </li>';
                        }
                        echo '</ul>';
                        break;
                    case 9:
                        $query = "select dept_name, count(salary) as totalSalaries from departments left join dept_emp on dept_emp.dept_no=departments.dept_no join salaries on salaries.emp_no=dept_emp.emp_no where dept_emp.to_date='9999-01-01' group by departments.dept_no";
                        $result = $this->dbUtil->retrieveResults($query);
                        echo '<h3>Task 9: How much does each department currently spend on salaries?</h3>';
                        echo '<ul>';
                        foreach ($result as $row) {
                            echo '<li>' . $row['dept_name'] . ' spends $' . $row['totalSalaries'] . '.</li>';
                        }
                        echo '</ul>';
                        break;
                    case 10:
                        $query = 'select sum(salary) from salaries where salaries.to_date = \'9999-01-01\'';
                        $result = $this->dbUtil->retrieveSingleResult($query);
                        echo '<h3>Task 10: How much is currently spent for all salaries?</h3>';
                        echo '<p> The amount currently spent for all salries is $' . $result['sum(salary)'] .'</p>';
                        break;
					case 11: 
						echo <<< frm
						<h3>Task: Add Employee</h3>
						<form name="input" action="" method="post">
						Employee Number: <input type="text" name="empno" value=""><br>
						First Name: <input type="text" name="fname" value=""><br>
						Last Name:" <input type="text" name="lname" value=""><br>
						Birth Date: <input type="text" name="bday" value=""><br>						
						Gender: <input type="text" name="gender" value="M"><br>
						Hire Date: <input type="text" name="hdate" value=""><br>
						<input type="submit" value="Submit" name="submit">
						</form> 						
frm;
						break;
					case 12:
						echo '<h3>Task: Add Employee</h3>';
						$en=$_POST['empno'];
						$bd=$_POST['bday']; 
						$fn=$_POST['fname']; 
						$ln=$_POST['lname']; 
						$g=$_POST['gender']; 
						$hd=$_POST['hdate']; 
						$query = "insert into employees values(:en, :bd, :fn, :ln, :g, :hd)";
						$st = $this->dbUtil->getConnection()->prepare($query);
						$st->execute(array(
							':en' => $en,
							':bd' => $bd,
							':fn' => $fn,
							':ln' => $ln,
							':g' =>  $g,
							':hd' => $hd
						));
						if($st->rowCount() == 1) {
							echo '<h4> Employee '.$en.' Successfully Added.</h4>';
						} else {
							echo '<h4> Error Adding Employee.</h4>';
						}
						break;
					case 13:
						$query = "select * from employees limit 5";
                        $result = $this->dbUtil->retrieveResults($query);
						echo '<table>';
						echo '<tr><td>Employee Number</td><td>First Name</td><td>Last Name</td><td>Birth Date</td><td>Gender</td><td>Hire Date</td><td>Edit</td></tr>';
						foreach ($result as $row) {
                            echo "<tr><td>" . $row['emp_no'] . "</td><td>" . $row['first_name'] . "</td><td>" . $row['last_name'] . "</td><td>" . $row['birth_date'] . "</td><td>" . $row['gender'] . "</td><td>" . $row['hire_date'] . "</td><td><a href='{$_SERVER['PHP_SELF']}?entry=14&id=".$row['emp_no']."'>Edit</a></td></tr>";
						}
						echo '</table>';
						break;
					case 14:
						$query = "select * from employees where emp_no=".$_GET["id"];
						$row = $this->dbUtil->retrieveSingleResult($query);
						echo <<< edt
						<h3>Task: Edit Employee</h3>
						<form name="input" action="" method="post">
						Employee Number: <input type="text" name="empno" value="{$row['emp_no']}" readonly><br>
						First Name: <input type="text" name="fname" value="{$row['first_name']}"><br>
						Last Name:" <input type="text" name="lname" value="{$row['last_name']}"><br>
						Birth Date: <input type="text" name="bday" value="{$row['birth_date']}"><br>						
						Gender: <input type="text" name="gender" value="{$row['gender']}"><br>
						Hire Date: <input type="text" name="hdate" value="{$row['hire_date']}"><br>
						<input type="submit" value="Submit" name="mod">
						</form> 						
edt;
						break;
					case 15:
						$en=$_POST['empno'];
						$bd=$_POST['bday']; 
						$fn=$_POST['fname']; 
						$ln=$_POST['lname']; 
						$g=$_POST['gender']; 
						$hd=$_POST['hdate']; 
						$query = "update employees set birth_date=:bd, first_name=:fn, last_name=:ln, gender=:g, hire_date=:hd where emp_no=:en";
						$st = $this->dbUtil->getConnection()->prepare($query);
						$st->execute(array(
							':en' => $en,
							':bd' => $bd,
							':fn' => $fn,
							':ln' => $ln,
							':g' =>  $g,
							':hd' => $hd
						));
						if($st->rowCount() == 1) {
							echo '<h4> Employee '.$en.' Successfully Modified.</h4>';
							$query = "select * from employees where emp_no=".$_POST['empno'];
							$result = $this->dbUtil->retrieveResults($query);
							echo '<table>';
							echo '<tr><td>Employee Number</td><td>First Name</td><td>Last Name</td><td>Birth Date</td><td>Gender</td><td>Hire Date</td><td>Edit</td></tr>';
							foreach ($result as $row) {
								echo "<tr><td>" . $row['emp_no'] . "</td><td>" . $row['first_name'] . "</td><td>" . $row['last_name'] . "</td><td>" . $row['birth_date'] . "</td><td>" . $row['gender'] . "</td><td>" . $row['hire_date'] . "</td><td><a href='{$_SERVER['PHP_SELF']}?entry=14&id=".$row['emp_no']."'>Edit</a></td></tr>";
							}
						} else {
							echo '<h4> Error Modifying Employee.</h4>';
						}
						
					break;
                }
				
				
                echo "<a href='" . $_SERVER['PHP_SELF'] . "'>Return to Menu</a>";
            }

        }

        class Constants {

            public static $SQLHOST = 'localhost';
            public static $SQLUSER = 'test';
            public static $SQLPASS = 'test';
            public static $SQLDB = 'employees';

        }

        $page = new Page();
        ?>
    </body>
</html>