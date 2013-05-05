<?php

namespace sale\models;

use transaction\models\Transaction AS Transaction;

/** @Entity(repositoryClass="sale\models\SaleRepository")
 *  @Table(name="sales")
 */
class Sale extends Transaction {
    const PAYMENT_CASH       = 'cash';
    const PAYMENT_CREDITCARD = 'credit_card';
    const PAYMENT_DEBIT      = 'debit';
    const PAYMENT_CREDITS    = 'credits';
    const PAYMENT_CHEQUE     = 'cheque';
    
    const TYPE_CNC           = 'cash_and_carry';
    const TYPE_FULL          = 'full_service';
    const TYPE_STANDARD      = 'standard_service';
    
    /**
      * @Column(type="string")
      */
    private $payment = self::PAYMENT_CASH;
    
    /**
      * @Column(type="string")
      */
    private $type = self::TYPE_CNC;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $credits = 0.00;
    
    /**
     * @ManyToOne(targetEntity="customer\models\Customer", inversedBy="sales")
     */
	private $customer;
    
    public function getPayment() {
        return $this->payment;
    }
    
    public function setPayment($payment) {
        if (!key_exists($payment, self::getPaymentTypes())) {
            throw new \InvalidArgumentException("Invalid payment");
        }
        
        $this->payment = $payment;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        if (!key_exists($type, self::getTypes())) {
            throw new \InvalidArgumentException("Invalid sale type");
        }
        
        $this->type = $type;
    }
    
    public function getCredits() {
		return $this->credits;
	}
	
	public function setCredits($credits) {
		$this->credits = $credits;
	}
    
    public function getCustomer() {
		return $this->customer;
	}
	
	public function setCustomer($customer) {
		$this->customer = $customer;
	}
    
    public static function getPaymentTypes() {
        return array(self::PAYMENT_CASH => get_full_name(self::PAYMENT_CASH),
                     self::PAYMENT_CREDITCARD => get_full_name(self::PAYMENT_CREDITCARD),
                     self::PAYMENT_DEBIT => get_full_name(self::PAYMENT_DEBIT),
                     self::PAYMENT_CREDITS => get_full_name(self::PAYMENT_CREDITS),
                     self::PAYMENT_CHEQUE => get_full_name(self::PAYMENT_CHEQUE));
    }
    
    public static function getTypes() {
        return array(self::TYPE_CNC => get_full_name(self::TYPE_CNC),
                     self::TYPE_FULL => get_full_name(self::TYPE_FULL),
                     self::TYPE_STANDARD => get_full_name(self::TYPE_STANDARD));
    }
}