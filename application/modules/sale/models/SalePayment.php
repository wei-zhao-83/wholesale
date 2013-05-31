<?php

namespace sale\models;

/** @Entity
 *  @Table(name="sale_payments")
 */
class SalePayment {
	const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING   = 'pending';
    const STATUS_FAILED    = 'failed';
	
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
    /**
      * @Column(type="string")
      */
    private $payment_type = Sale::PAYMENT_CASH;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $amount = 0.00;
    
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $created_at;
    
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	private $comment;
	
	/**
	 * @Column(type="string", length=125, nullable=true)
	 */
	private $status;
	
	/**
     * @ManyToOne(targetEntity="sale\models\Sale", inversedBy="payments")
     */
    protected $sale;
	
	public function __construct() {
		$this->created_at = new \DateTime("now");
    }
	
	public function getID() {
		return $this->id;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
    public function getPaymentType() {
        return $this->payment_type;
    }
	
    public function setPaymentType($payment_type) {
        if (!key_exists($payment_type, Sale::getPaymentTypes())) {
            throw new \InvalidArgumentException("Invalid payment type");
        }
        
        $this->payment_type = $payment_type;
    }
    
	public function setAmount($amount) {
		$this->amount = $amount;
	}
	
	public function getAmount() {
		return $this->amount;
	}
	
    public function getCreatedAt() {
        return $this->created_at->format('Y-m-d');
    }
	
	public function setSale(Sale $sale) {
		$this->sale = $sale;
	}
	
	public function getSale() {
		return $this->sale;
	}
	
	public function getComment() {
		return $this->comment;
	}
	
	public function setComment($comment) {
		$this->comment = $comment;
	}
	
	public static function getStatuses() {
		return array(self::STATUS_COMPLETED, self::STATUS_PENDING, self::STATUS_FAILED);
	}
}