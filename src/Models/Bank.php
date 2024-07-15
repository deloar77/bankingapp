<?php
namespace App\Models;
use App\Models\Customer;


class Bank{
    private $data_file;
    private $transactions_file;
    private $customers;
    public function __construct($data_file,$transactions_file)
    {
        $this->data_file=$data_file;
        $this->transactions_file=$transactions_file;
        $this->customers=$this->loadData();
    }

  private function loadData(){
    if(file_exists($this->data_file)){
        return json_decode(file_get_contents($this->data_file),true);
    } else {
        return [];
    }
  }

   private function saveData(){
    file_put_contents($this->data_file,json_encode($this->customers));
   }
   
   private function loadTransactions(){
    if(file_exists($this->transactions_file)){
        return json_decode(file_get_contents($this->transactions_file),true);
    }else {
        return [];
    }
   }


   private function logTransaction($transaction){
         $transactions=$this->loadTransactions();
         $transactions[]=$transaction;
         file_put_contents($this->transactions_file,json_encode($transactions));
   }





  public function registerCustomer($name,$email,$password){
    $customer = new Customer($name,$email,$password);
    $this->customers[]=(array)$customer;
    $this->saveData();
     $this->logTransaction(["action" => "register", "customer_id" => $customer->id, "timestamp" => time()]);

  }

  public function loginCustomer($email,$password){
            
     foreach($this->customers as $customer){
         
         
        if ($customer['email']===$email ){
         if($customer['password']===$password){
            return $customer['id'];
         } else {
            return false;
         }
        }
     }
  }

   public function showCustomers(){
    return $this->customers;
   }

   public function getCustomer($customer_id){
    foreach($this->customers as $customer){
        if($customer['id']===$customer_id){
            return $customer;
        }
    }
    return null;
   }


 public function approveCustomer($customer_id){
    foreach($this->customers as &$customer){
        if($customer['id']===$customer_id){
            $customer['is_approved']=true;
            $this->saveData();
            $this->logTransaction(["action" => "approve", "customer_id" => $customer_id, "timestamp" => time()]);
            return;
        }
    }
 }


 public function depositFunds($customer_id,$amount){
    foreach($this->customers as &$customer){
        if($customer['id']===$customer_id){
            if($customer['is_approved']){
                $customer['balance']+=$amount;
                $this->saveData();
                $this->logTransaction(["action" => "deposit", "customer_id" => $customer_id, "amount" => $amount, "timestamp" => time()]);
                return;
            } else {
                return;
            }
        }
    }
 }


  
  public function withdrawFunds($customer_id,$amount){
    foreach($this->customers as &$customer){
        if($customer['id']===$customer_id){
            if($customer['is_approved']){
              
                if($customer['balance']>=$amount){
                    $customer['balance']-=$amount;
                    $this->saveData();
                    $this->logTransaction(["action" => "withdraw", "customer_id" => $customer_id, "amount" => $amount, "timestamp" => time()]);
                    return;
                }else{
                    return;
                }

            } else {
                return;
            }
        }
    }
  }



  public function transferFunds($from_id,$to_email,$amount){
    $from_customer=null;
    $to_customer= null;

    foreach($this->customers as &$customer){
        if($customer['id']===$from_id){
            $from_customer=&$customer;
        }
        if($customer['email']===$to_email){
            $to_customer=&$customer;
        }
    }
    if($from_customer && $to_customer){
      if($from_customer['is_approved'] && $to_customer['is_approved']){
        if($from_customer['balance']>=$amount){
            $from_customer['balance']-=$amount;
            $to_customer['balance']+=$amount;
            $this->saveData();
            $this->logTransaction(["action" => "transfer", "from_id" => $from_id, "to_id" => $to_customer['id'], "amount" => $amount, "timestamp" => time()]);
            return;
        } else {
            return;
        }
      }

    }


  }


    public function getTransactions() {
        return $this->loadTransactions();
    }

        public function getTransactionsByCustomerId($customer_id) {
        $transactions = $this->loadTransactions();
        return array_filter($transactions, function($transaction) use ($customer_id) {
            return $transaction['customer_id'] === $customer_id || (isset($transaction['from_id']) && $transaction['from_id'] === $customer_id) || (isset($transaction['to_id']) && $transaction['to_id'] === $customer_id);
        });
    }



}
