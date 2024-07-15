<?php
namespace App\Controllers;
use App\Models\Bank;

class loginController{
   
     private $bank;
     public function __construct($data_file,$transactions_file)
     {
        $this->bank=new Bank($data_file,$transactions_file);
     }

     public function login($email,$password){
       
       if($id=$this->bank->loginCustomer($email,$password)){
         
         $_SESSION['email']=$email;
         $_SESSION['id']=$id;
           header("Location: index.php?action=customer_dashboard");
           exit();
             
       } else {
           header("Location: index.php?action=login_form");
           exit();
       }
          
     }
     public function adminLogin($email,$password){
        if($this->bank->loginCustomer($email,$password) && $this->isAdmin($email)){
             $_SESSION['email']=$email;
              header("Location: index.php?action=admin_dashboard");
              exit();
        } else{
            header("Location: index.php?action=admin_login_form");
            exit();
        }
     }
    
     public function isAdmin($admimEmail){
        return $admimEmail==="deloar@gmail.com";
     }
            
     public function logout(){
        unset($_SESSION['email']);
        session_destroy();
        header('Location:index.php?action=login_form');
         exit();
     }
     

}