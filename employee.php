<?php

class Employee
{
    private $id;
    private $name;
    private $age;
    private $address;
    private $tax;
    private $salary;

    public function __construct($name, $age, $tax, $salary, $address)
    {
        $this->name = $name;
        $this->age = $age;
        $this->tax= $tax;
        $this->salary = $salary;
        $this->address = $address;

    }
    public function __get($prop)
    {
        return $this->$prop;
    }


    public function calculateSalary()
    {
        return $this->salary - ($this->salary * $this->tax / 100);
    }

}