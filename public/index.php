<?php
session_start();

use App\Controllers\AdminController;
use App\Controllers\CustomerController;
use App\Controllers\loginController;

require __DIR__.'/../vendor/autoload.php';

$data_file= __DIR__."/../data/data_file.json";
$transactions_file=__DIR__."/../data/transactions_file.json";

$customerController = new CustomerController($data_file,$transactions_file);
$loginController = new loginController($data_file,$transactions_file);
$adminController = new AdminController($data_file,$transactions_file);

$action = $_GET['action'] ?? $_POST['action'] ?? null;
$customer_id = $_GET['customer_id'] ?? $_POST['customer_id'] ?? null;








switch($action){

    case "register_form":
        include __DIR__ . '/../src/Views/customer/customer_register.php';
        break;

    case "login_form":
         include __DIR__ . '/../src/Views/customer/customer_login.php';
        break;

    case "admin_login_form":
         include __DIR__ . '/../src/Views/admin/admin_login_form.php';
        break;
    case "customer_dashboard":
        $customerController->showDashboard();
        break;
    case "admin_dashboard":
        $adminController->showDashboard();
    

        break;  
        case "approve_customer":
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->approve($customer_id);
          
        }
        break;      

    case "register":
             if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name=$_POST['name'];
                $email=$_POST['email'];
                $password=$_POST['password'];
                
                $customerController->register($name,$email,$password);
        } else {
            include __DIR__ . '/../src/Views/customer/customer_register.php';
        }
        break;
           
    case "login":
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                 $email=$_POST['email'];
                $password=$_POST['password'];
               
                $loginController->login($email,$password);
              
            }
          break;
        case "admin_login":
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                 $email=$_POST['email'];
                $password=$_POST['password'];
               
                $loginController->adminLogin($email,$password);
              
            }
          break;

    case "deposit_form":
          $customerController->deposit_form_data();
       
        break;
    case "deposit":
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $customer_id=$_POST['customer_id'];
            $amount= $_POST['amount'];
           
            $customerController->deposit($customer_id,$amount);
       
        }
        break;
        
    case "withdraw_form":
        $customerController->withdraw_form_data();
        break;    

      case "withdraw":
          if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $customer_id=$_POST['customer_id'];
            $amount= $_POST['amount'];
           
            $customerController->withdraw($customer_id,$amount);
       
        }
        break;       
    case "transfer_form":
        $customerController->transfer_form_data();
        break;
  case "transfer":
          if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $customer_id=$_POST['customer_id'];
            $to_email=$_POST['email'];
            $amount= $_POST['amount'];
           
            $customerController->transfer($customer_id,$to_email,$amount);
       
        }
        break;          

    case "logout":
              $loginController->logout();
              break;


       
        default:
          
        include __DIR__ . '/../src/Views/customer/home.php';
        break;
}