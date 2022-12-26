<?php 

require_once('dbconnection.php');
require_once('employee.php');
$message = null;



if (isset($_POST['sub'])) {

    $name = filter_input(INPUT_POST, 'name');
    $address = filter_input(INPUT_POST, 'address');
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT);
    $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

   $employee = new Employee();
    $employee->name = $name;
    $employee->age = $age;
    $employee->salary = $salary;
    $employee->tax = $tax;
    $employee->address = $address;

    // insert into database
    $sql = 'insert into employees SET name = "' . $name . '",
            address = "' . $address . '",
            salary = " ' . $salary . '", 
            tax = " ' . $tax . '", 
            age = "' . $age . '" ';


    if ($pdo->exec($sql)) {
            $message = "Employee, " . $name ." inserted successfully";
        }else{
            $error = true;
            $message = "Error Inserting Employee " . $name;
        }

    // Reading from database


    }
    $sql = 'select * from employees';
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Employee');
    $result = (is_array($result) && !empty($result)) ? $result : false;
?>


<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PDO</title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
        <div class="wrapper">
            <div class="empForm">
                <form class="appForm" method="POST" enctype="application/x-www-form-urlencoded">

                    <fieldset>
                        <legend>
                            Employee Information
                        </legend>
                        <?php if (isset( $message )){?>
                            <p class="message <?= isset($error) ? 'error' : '' ?>"> <?= $message ?> </p>
                        <?php } ?>
                        <table>
                            <tr>
                                <td>
                                    <label>
                                        Employee Name
                                        <input required type="text" name="name" maxlength="50">
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Employee Age
                                        <input required type="number" name="age" min="22" max="60">
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Employee Address
                                        <input required type="text" name="address">
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Employee Salary
                                        <input required type="number" step="0.01" name="salary" min="1500" max="9000" >
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Employee Tax (%)
                                        <input required type="number" step="0.01" name="tax" min="1" max="5" >
                                    </label>
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    <input type="submit" name="sub" value="save">
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </div>
            <div class="employees">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Salary</th>
                            <th>Tax</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (false !== $result){
                                foreach ($result as $employee){
                        ?>
                        <tr>
                                <td><?= $employee->id ?></td>
                                <td><?= $employee->name ?></td>
                                <td><?= $employee->age ?></td>
                                <td><?= $employee->address ?></td>
                                <td><?= $employee->calculateSalary() ?></td>
                                <td><?= $employee->tax ?></td>
                        </tr>
                                            <?php
                                }
                            }else{
                        ?>
                            <td style="text-align: center;color: red" colspan="6">No Employees to list</td>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>