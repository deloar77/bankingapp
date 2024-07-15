<?php
namespace App\Models;
class Customer{
   public $id;
   public $name;
   public $email;
   public $password;
   public $balance;
   public $isAdmin;
   public $is_approved;
   public function __construct($name,$email,$password,$balance=0)
   {
     $this->id=uniqid();
     $this->name=$name;
     $this->email=$email;
     $this->password=$password;
     $this->balance=$balance;
     $this->is_approved=false;
     $this->isAdmin=false;
     
   }
}
