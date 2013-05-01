<?php

namespace returns\models;

use transaction\models\Transaction AS Transaction;

/** @Entity(repositoryClass="returns\models\ReturnsRepository")
 *  @Table(name="returns")
 */
class Returns extends Transaction {
    const PAYMENT_CASH       = 'cash';
    const PAYMENT_CREDITCARD = 'credit_card';
    const PAYMENT_DEBIT      = 'debit';
    const PAYMENT_CREDITS    = 'credits';
    const PAYMENT_CHEQUE     = 'cheque';
	
    /**
      * @Column(type="string")
      */
    private $payment = self::PAYMENT_CREDITS;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $credits = 0.00;
    
	/**
     * @Column(type="string", length=64, nullable=true)
     */
	private $sale_id;
	
    /**
     * @ManyToOne(targetEntity="customer\models\Customer", inversedBy="returns")
     */
	private $customer;
    
    public function getPayment() {
        return $this->payment;
    }
    
    public function setPayment($payment) {
        if (!in_array($payment, self::getPaymentTypes())) {
            throw new \InvalidArgumentException("Invalid payment");
        }
        
        $this->payment = $payment;
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
        return array(self::PAYMENT_CASH, self::PAYMENT_CREDITCARD, self::PAYMENT_DEBIT, self::PAYMENT_CREDITS, self::PAYMENT_CHEQUE);
    }
}