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
}