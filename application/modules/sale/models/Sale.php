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
	private $default_discount = 0.00;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $credits = 0.00;
    
    /**
     * @Column(type="datetime", nullable=true)
     */
    private $ship_date;
    
    /**
     * @ManyToOne(targetEntity="customer\models\Customer", inversedBy="sales")
     */
	private $customer;
    
    /**
     * @OneToMany(targetEntity="sale\models\SalePayment", mappedBy="sale", cascade={"persist"})
     */
    protected $payments;
    
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
    
    public function setDefaultDiscount($discount) {
        $this->default_discount = $discount;
    }
    
    public function getDefaultDiscount() {
        return $this->default_discount;
    }
    
    public function getShipDate() {
        return $this->ship_date->format('Y-m-d');
    }
    
    public function setShipDate($date) {
        $this->ship_date = new \DateTime($date);
    }
    
    public function getSummary($is_picked = false) {
        $sub_total = $discount = $tax = $total_paid = 0;
		
		$items = $this->getItems();
        $payments = $this->getPayments();
        
        if(!empty($payments)) {
            foreach($payments as $payment) {
                if($payment->getStatus() == SalePayment::STATUS_COMPLETED) {
                    $total_paid += $payment->getAmount();
                }
            }
        }
        
		if(!empty($items)) {
			foreach($items as $item) {
                $qty = ($is_picked) ? $item->getPicked() : $item->getQty();
                
				$sub_total += ($item->getSalePrice() -  $item->getDiscount()) * $qty;
				$discount  += $item->getDiscount() * $qty;
			}
            
            $tax = $item->getTax() * $sub_total;
		}
		
		return array('sub_total' => number_format($sub_total, 2),
					 'discount'  => number_format($discount, 2),
					 'tax' 		 => number_format($tax, 2),
					 'total' 	 => number_format($sub_total + $tax, 2),
                     'total_due' => number_format($sub_total + $tax - $total_paid, 2));
    }
    
    // override the parent class method
    public function addItem($item) {
        if ($this->type == self::TYPE_CNC) {
        	$item->setCNC($item->getProduct()->getCNC());
        } elseif ($this->type == self::TYPE_FULL) {
        	$item->setFullServicePrice($item->getProduct()->getFullServicePrice());
        } elseif ($this->type == self::TYPE_STANDARD) {
        	$item->setNoServicePrice($item->getProduct()->getNoServicePrice());
        }
        
		$this->items[] = $item;
		$item->setTransaction($this);
	}
    
    public function addPayment(SalePayment $payment) {
		$this->payments[] = $payment;
		$payment->setSale($this);
	}
	
	public function getPayments() {
		return $this->payments;
	}
	
	public function removePayment($payment) {
		$this->payments->removeElement($payment);
	}
    
    public static function getPaymentTypes() {
        return array(self::PAYMENT_CASH       => get_full_name(self::PAYMENT_CASH),
                     self::PAYMENT_CREDITCARD => get_full_name(self::PAYMENT_CREDITCARD),
                     self::PAYMENT_DEBIT      => get_full_name(self::PAYMENT_DEBIT),
                     self::PAYMENT_CREDITS    => get_full_name(self::PAYMENT_CREDITS),
                     self::PAYMENT_CHEQUE     => get_full_name(self::PAYMENT_CHEQUE));
    }
    
    public static function getTypes() {
        return array(self::TYPE_CNC      => get_full_name(self::TYPE_CNC),
                     self::TYPE_FULL     => get_full_name(self::TYPE_FULL),
                     self::TYPE_STANDARD => get_full_name(self::TYPE_STANDARD));
    }
}