<?php

namespace purchase\models;

use transaction\models\Transaction AS Transaction;

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
        $sub_total = $total = $tax = 0;
		
		$items = $this->getItems();
        
		if($items->count() > 0) {
			foreach($items as $item) {
				$sub_total += $item->getCost() * $item->getQty();
				$tax += $item->getTax() * $item->getCost() * $item->getQty();
			}            
		}
		
		return array('sub_total' => number_format($sub_total, 2, '.', ''),
					 'tax' 		 => number_format($tax, 2, '.', ''),
					 'total' 	 => number_format($sub_total + $tax, 2, '.', ''));
    }
}