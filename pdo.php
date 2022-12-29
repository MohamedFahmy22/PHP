<?php 
session_start();

require_once('dbconnection.php');
require_once('employee.php');
$message = null;



if (isset($_POST['sub'])) {

    $name = filter_input(INPUT_POST, 'name');
    $address = filter_input(INPUT_POST, 'address');
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT);
    $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

    // insert into database or update
    $params = array(
        ':name' => $name,
        ':age'  => $age,
        ':address' => $address,
        ':salary'   => $salary,
        ':tax'  => $tax
    );
    if (isset($_GET['action']) && isset($_GET['action']) == 'edit' && isset($_GET['id'])){
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $sql = 'UPDATE employees SET name = :name ,
            address = :address,
            salary = :salary, 
            tax = :tax, 
            age = :age WHERE id = :id';
            $params[':id'] = $id;
    }else{
        $sql = 'INSERT INTO employees SET name = :name ,
            address = :address,
            salary = :salary, 
            tax = :tax, 
            age = :age ';
    }

    $stm = $pdo->prepare($sql);  
    // prepare function to prevent any attackers to inject any code to database before storing operations

    if ($stm->execute($params) === true
    ) {
            $_SESSION['message'] = "Employee, " . $name ." Saved Successfully";
            header('Location:http://localhost/advancedphp/pdo.php');
            session_write_close();
            exit;
        }else{
            $error = true;
            $_SESSION['message'] = "Error Saving Employee " . $name;
        }

}

         //edit by action and id
if (isset($_GET['action']) && isset($_GET['action']) == 'edit' && isset($_GET['id'])){
    $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    if ($id > 0){
        $sql = 'SELECT * FROM employees WHERE id = :id';
        $result = $pdo->prepare($sql);  // thats is the result we canmake the fetch of the result
        $resultFounded = $result->execute(array(':id'=>$id));   // return bool value true id the id is founded we can't make fetch on bool value
        if ($resultFounded === true)
        {
            $user = $result->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', ['name', 'age', 'address', 'salary', 'tax' ]);

            $user = array_shift($user);
            // return the first element from array that will be object element number zero on array type of Employee Class
            // and object contains all of values of fields

        }
    }
}


// Reading from database

    $sql = 'select * from employees';
    $stmt = $pdo->query($sql);  // reading from database
    $result = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee', ['name', 'age', 'address', 'salary', 'tax' ]);
    // fetchall => عمليه استخلاص البيانات 
    // pdo::fetch_class => إستخلاص الداتا وجليها ع هيئه اوبجكت 
    // pdo::fetch_props_late =>  جلب الكونستركتور الخاص بالكلاس الذي ربطنا به البيانات المجلوبة قبل تعيين الخصائص للاعمده المذكورة فى المصفوفة التالية
    $result = (is_array($result) && !empty($result)) ? $result : false;
    // check if the result is array and the result not empty 
?>


<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PDO</title>
        <link rel="stylesheet" href="main.css">
        <script src="https://kit.fontawesome.com/ae1a31a05c.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="empForm">
                <form class="appForm" method="POST" enctype="application/x-www-form-urlencoded">

                    <fieldset>
                        <legend>
                            Employee Information
                        </legend>
                        <?php if (isset( $_SESSION['message'] )){?>
                            <p class="message <?= isset($error) ? 'error' : '' ?>"> <?= $_SESSION['message'] ?> </p>
                        <?php
                        unset($_SESSION['message']);
                    } ?>
                        <table>
                            <tr>
                                <td>
                                    <label>
                                        Employee Name
                                        <input required type="text" name="name" maxlength="50" value="<?= isset($user)? $user->name: ''  ?>">
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        Employee Age
                                        <input required type="number" name="age" min="22" max="60" value="<?= isset($user)? $user->age: ''  ?>">
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Employee Address
                                        <input required type="text" name="address" value="<?= isset($user)? $user->address: ''  ?>">
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Employee Salary
                                        <input required type="number" step="0.01" name="salary" min="1500" max="9000" value="<?= isset($user)? $user->salary: ''  ?>" >
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>
                                        Employee Tax (%)
                                        <input required type="number" step="0.01" name="tax" min="1" max="5" value="<?= isset($user)? $user->tax: ''  ?>" >
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
                            <th>Control</th>
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
                                <td>
                                    <a href="/advancedphp/pdo.php?action=edit&id=<?= $employee->id ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                    |||
                                    <a href="/advancedphp/pdo.php?action=delete&id=<?= $employee->id ?>" onclick="if(!confirm('Do You Want to Delete Employee?')return false;" ><i class="fa-sharp fa-solid fa-times"></i></a>
                                </td>
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