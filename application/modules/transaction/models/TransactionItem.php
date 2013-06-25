<?php

namespace transaction\models;

/**
 * @Entity
 * @Table(name="transaction_item")
 */
class TransactionItem {
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
     * @Column(type="decimal", scale=2, nullable=true)
	 */
	private $cost;
	
    /**
     * @Column(type="decimal", scale=2, nullable=true)
	 */
	private $suggested_price;
    
    /**
     * @Column(type="decimal", scale=2, nullable=true)
	 */
	private $no_service_price;
    
    /**
     * @Column(type="decimal", scale=2, nullable=true)
	 */
	private $full_service_price;
    
    /**
     * @Column(type="decimal", scale=2, nullable=true)
	 */
	private $cash_and_carry;

	/**
     * @Column(type="decimal", scale=2)
	 */
	private $discount = 0.00;
	
	/**
     * @Column(type="decimal", scale=2)
	 */
	private $tax = 0.00;
	
	/**
	 * @Column(type="decimal", scale=2)
	 */
	private $return_price = 0.00;
	
	/**
     * @Column(type="integer")
	 */
	private $qty = 0;
	
	/**
     * @Column(type="integer")
	 */
	private $picked = 0;
	
	/**
     * @Column(type="integer")
	 */
	private $shipped = 0;
	
	/**
     * @Column(type="integer")
	 */
	private $received = 0;
	
	/**
	 * @Column(type="string", length=128, nullable=true)
	 */
	private $comment;
	
	/**
     * @ManyToOne(targetEntity="transaction\models\Transaction", inversedBy="items")
     * @JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;
	
	/**
     * @ManyToOne(targetEntity="product\models\Product", inversedBy="transaction_items")
     */
    private $product;
	
	public function getId() {
		return $this->id;
	}
	
	public function getCost() {
		return $this->cost;
	}
	
	public function setCost($cost) {
		$this->cost = $cost;
	}
	
	public function getRowTotal() {
		$row_total = 0;
		
		if ($this->transaction instanceof \sale\models\Sale) {
			$row_total = $this->getQty() * ($this->getSalePrice() - $this->getDiscount());
		}
		if ($this->transaction instanceof \purchase\models\Purchase) {
			$row_total = $this->getQty() * ($this->getCost() - $this->getDiscount());
		}
		
		return number_format($row_total, 2, '.', '');
	}
	
    public function getSuggestedPrice() {
		return $this->suggested_price;
	}
	
	public function setSuggestedPrice($price) {
		$this->suggested_price = $price;
	}
    
    public function getNoServicePrice() {
		return $this->no_service_price;
	}
	
	public function setNoServicePrice($price) {
		$this->no_service_price = $price;
	}
    
    public function getFullServicePrice() {
		return $this->full_service_price;
	}
	
	public function setFullServicePrice($price) {
		$this->full_service_price = $price;
	}

	public function getCNC() {
		return $this->cash_and_carry;
	}
	
	public function setCNC($price) {
		$this->cash_and_carry = $price;
	}
	
	public function getSalePrice() {		
		if ($this->getCNC() !== null) {
			return $this->getCNC();
		}
		if ($this->getFullServicePrice() !== null) {
			return $this->getFullServicePrice();
		}
		if ($this->getNoServicePrice() !== null) {
			return $this->getNoServicePrice();
		}
	}
	
	public function getSaleAmount() {
		$amount = ($this->getSalePrice() - $this->getDiscount()) * $this->getPicked();
		
		return number_format($amount, 2, '.', '');
	}
	
	public function getDiscount() {
		return $this->discount;
	}
	
	public function setDiscount($discount) {
		$this->discount = $discount;
	}

	public function getTax() {
		return $this->tax;
	}
	
	public function setTax($tax) {
		$this->tax = $tax;
	}
	
	public function getReturn() {
		return $this->return_price;
	}
	
	public function setReturn($return) {
		$this->return_price = $return;
	}
	
    public function getQty() {
		return $this->qty;
	}
	
	public function setQty($qty) {
		$this->qty = $qty;
	}
	
	public function getPicked() {
		return $this->picked;
	}
	
	public function setPicked($qty) {
		$this->picked = $qty;
	}
	
	public function getShipped() {
		return $this->shipped;
	}
	
	public function setShipped($qty) {
		$this->shipped = $qty;
	}
	
	public function getReceived() {
		return $this->received;
	}
	
	public function setReceived($received) {
		$this->received = $received;
	}
	
	public function getComment() {
		return $this->comment;
	}
	
	public function setComment($comment) {
		$this->comment = $comment;
	}
	
	public function getTransaction() {
		return $this->transaction;
	}
	
	public function setTransaction(Transaction $transaction) {
		$this->transaction = $transaction;
	}
	
	public function setProduct($product) {
		$this->product = $product;
	}
	
	public function getProduct() {
		return $this->product;
	}
}

?>