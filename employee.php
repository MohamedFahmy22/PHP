<?php

class Employee
{
    public $id;
    public $name;
    public $age;
    public $address;
    public $tax;
    public $salary;

   /* public function __construct($name, $age, $tax, $salary)
    {
        $this->name = $name;
        $this->age = $age;
        $this->tax= $tax;
        $this->salary = $salary;

    }
*/

    public function calculaateSalary()
    {
        return $this->salary - ($this->salary * $this->tax);
    }

}