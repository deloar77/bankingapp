<?php 
namespace App\Controllers;

use App\Models\Bank;

class AdminController{

   private $data_file;
   private $transactions_file;
   private $bank;
   public function __construct($data_file,$transactions_file)
   {
    $this->data_file=$data_file;
    $this->transactions_file=$transactions_file;
    $this->bank= new Bank($data_file,$transactions_file);
   }
    
      public function showDashboard() {
        if (!isset($_SESSION['email'])) {
            header("Location: index.php?action=admin_login_form");
            exit();
        }
        $customers=json_decode(file_get_contents($this->data_file),true);

        include __DIR__ . '/../Views/admin/admin_dashboard.php';
    }

      public function approve($customer_id){
        $this->bank->approveCustomer($customer_id);
        header("Location: index.php?action=admin_dashboard");
        include __DIR__ . '/../Views/admin/admin_dashboard.php';
        
    }

   

}