<?php

namespace purchase\models;

use transaction\models\Transaction,
	transaction\models\TransactionPayment;

/** @Entity(repositoryClass="purchase\models\PurchaseRepository")
 *  @Table(name="purchases")
 */
class Purchase extends Transaction {	
    /**
     * @ManyToOne(targetEntity="vendor\models\Vendor", inversedBy="purchases")
     */
	private $vendor;
    
    public function getVendor() {
		return $this->vendor;
	}
	
	public function setVendor($vendor) {
		$this->vendor = $vendor;
	}
	
	public function getSummary() {
        $sub_total = $total = $tax = $total_paid = $due = 0;
		
		$items = $this->getItems();
        $payments = $this->getPayments();
        
        if(!empty($payments)) {
            foreach($payments as $payment) {
                if($payment->getStatus() == TransactionPayment::STATUS_COMPLETED) {
                    $total_paid += $payment->getAmount();
                }
            }
        }
		
		if($items->count() > 0) {
			foreach($items as $item) {
				$sub_total += $item->getCost() * $item->getQty();
				$tax += $item->getTax() * $item->getCost() * $item->getQty();
			}            
		}
		
		if ($sub_total + $tax - $total_paid > 0) {
			$due = $sub_total + $tax - $total_paid;
		}
		
		return array('sub_total' => number_format($sub_total, 2, '.', ''),
					 'tax' 		 => number_format($tax, 2, '.', ''),
					 'total' 	 => number_format($sub_total + $tax, 2, '.', ''),
					 'total_due' => number_format($due, 2, '.', ''));
    }
}