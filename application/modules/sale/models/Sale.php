<?php

namespace sale\models;

use transaction\models\Transaction,
	transaction\models\TransactionPayment;

/** @Entity(repositoryClass="sale\models\SaleRepository")
 *  @Table(name="sales")
 */
class Sale extends Transaction {
    const TYPE_CNC           = 'cash_and_carry';
    const TYPE_FULL          = 'full_service';
    const TYPE_STANDARD      = 'standard_service';
    
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
        $sub_total = $discount = $tax = $total_paid = $due = 0;
		
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
                $qty = ($is_picked) ? $item->getPicked() : $item->getQty();
                
				$sub_total += ($item->getSalePrice() -  $item->getDiscount()) * $qty;
				$discount  += $item->getDiscount() * $qty;
                $tax += ($item->getSalePrice() -  $item->getDiscount()) * $qty * $item->getTax();
			}            
		}
		
		if ($sub_total + $tax - $total_paid > 0) {
			$due = $sub_total + $tax - $total_paid;
		}
		
		return array('sub_total' => number_format($sub_total, 2, '.', ''),
					 'discount'  => number_format($discount, 2, '.', ''),
					 'tax' 		 => number_format($tax, 2, '.', ''),
					 'total' 	 => number_format($sub_total + $tax, 2, '.', ''),
                     'total_due' => number_format($due, 2, '.', ''));
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
    
    public static function getTypes() {
        return array(self::TYPE_CNC      => get_full_name(self::TYPE_CNC),
                     self::TYPE_FULL     => get_full_name(self::TYPE_FULL),
                     self::TYPE_STANDARD => get_full_name(self::TYPE_STANDARD));
    }
}