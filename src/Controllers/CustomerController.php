<?php
namespace App\Controllers;

use App\Models\Bank;

class CustomerController{
    private $bank;
    private $data_file;
    public function __construct($data_file,$transactions_file)
    {
        $this->data_file=$data_file;
        $this->bank= new Bank($data_file,$transactions_file);
    }
    public function register($name,$email,$password){
        $this->bank->registerCustomer($name,$email,$password);
        include __DIR__."/../Views/customer/customer_register.php";
    }

     public function showDashboard() {
        if (!isset($_SESSION['email'])) {
            header("Location: index.php?action=login_form");
            exit();
        }
       
        include __DIR__ . '/../Views/customer/dashboard.php';
    }

    public function deposit_form_data(){
        if(!isset($_SESSION['email'])){
             header("Location: index.php?action=login_form");
           exit();
        } 
        $customer_id=$_SESSION['id'];
        $customerData= $this->bank->getCustomer($customer_id);
        include __DIR__ . '/../Views/customer/deposit.php';

    }

     public function withdraw_form_data(){
        if(!isset($_SESSION['email'])){
             header("Location: index.php?action=login_form");
           exit();
        } 
        $customer_id=$_SESSION['id'];
        $customerData= $this->bank->getCustomer($customer_id);
        include __DIR__ . '/../Views/customer/withdraw.php';

    }

       public function transfer_form_data(){
        if(!isset($_SESSION['email'])){
             header("Location: index.php?action=login_form");
           exit();
        } 
        $customer_id=$_SESSION['id'];
        $customerData= $this->bank->getCustomer($customer_id);
        include __DIR__ . '/../Views/customer/transfer.php';

    }
   
    public function deposit($customer_id,$amount){
        $this->bank->depositFunds($customer_id,$amount);
         header("Location: index.php?action=deposit_form");
         exit();
    }

    public function withdraw($customer_id,$amount){
        $this->bank->withdrawFunds($customer_id,$amount);
          header("Location: index.php?action=withdraw_form");
            exit();
    }

    public function transfer($from_id,$to_email,$amount){
        $this->bank->transferFunds($from_id,$to_email,$amount);
            header("Location: index.php?action=transfer_form");
            exit();
    }
  



}