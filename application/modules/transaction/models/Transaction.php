<?php

namespace transaction\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="transactions")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="tansaction_type", type="string")
 * @DiscriminatorMap({"sale" = "sale\models\Sale", "purchase" = "purchase\models\Purchase", "returns" = "returns\models\Returns", "quote" = "quote\models\Quote"})
 */
class Transaction {
	const PAYMENT_CASH       = 'cash';
    const PAYMENT_CREDITCARD = 'credit_card';
    const PAYMENT_DEBIT      = 'debit';
    const PAYMENT_CREDITS    = 'credits';
    const PAYMENT_CHEQUE     = 'cheque';
	
	const STATUS_DRAFT     = 'draft';
	const STATUS_PENDING   = 'pending';
	const STATUS_PICKED    = 'picked';
	const STATUS_SHIPPED   = 'shipped';
	const STATUS_RECIEVED  = 'recieved';
	const STATUS_COMPLETED = 'completed';
	const STATUS_CANCELLED = 'cancelled';
	const STATUS_IN_TRANSIT = 'in_transit';
	
    /**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=125)
	 */
	private $status;
	
	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $boh_updated = 0;
	
	/**
     * @Column(type="decimal", scale=2)
	 */
	private $total = 0.00;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	private $comment;
	
	/**
     * @ManyToOne(targetEntity="user\models\User", inversedBy="id")
     */
	private $user;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $created_at;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $deleted_at;
	
	/**
     * @OneToMany(targetEntity="transaction\models\TransactionItem", mappedBy="transaction", cascade={"persist"})
     */
    protected $items;
	
	/**
     * @OneToMany(targetEntity="transaction\models\TransactionPayment", mappedBy="transaction", cascade={"persist"})
     */
    protected $payments;
	
	public function addPayment(transactionPayment $payment) {
		$this->payments[] = $payment;
		$payment->setTransaction($this);
	}
	
	public function getPayments() {
		return $this->payments;
	}
	
	public function removePayment($payment) {
		$this->payments->removeElement($payment);
	}
	
	public function __construct() {
		$this->created_at = new \DateTime("now");
        $this->items = new ArrayCollection();
    }
	
	public function getId() {
		return str_pad($this->id, 6, '0', STR_PAD_LEFT);
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user = $user;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function getBohUpdated() {
		return $this->boh_updated;
	}
	
	public function setBohUpdated($boh_updated) {
		$this->boh_updated = $boh_updated;
	}
	
	public function getTotal() {
		return $this->total;
	}
	
	public function setTotal($total) {
		$this->total = $total;
	}
	
	public function getComment() {
		return $this->comment;
	}
	
	public function setComment($comment) {
		$this->comment = $comment;
	}
	
	public function getCreatedAt() {
		return $this->created_at->format('Y-m-d');
	}
	
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}
	
	public function getDeletedAt() {
		return $this->deleted_at;
	}
	
	public function setDeletedAt($deleted_at) {
		$this->deleted_at = $deleted_at;
	}
	
	public function addItem($item) {
		$this->items[] = $item;
		$item->setTransaction($this);
	}
	
	public function getItems() {
		return $this->items;
	}
	
	public function removeItem($item) {
		$this->items->removeElement($item);
	}
	
    public static function getPaymentTypes() {
        return array(self::PAYMENT_CASH       => get_full_name(self::PAYMENT_CASH),
                     self::PAYMENT_CREDITCARD => get_full_name(self::PAYMENT_CREDITCARD),
                     self::PAYMENT_DEBIT      => get_full_name(self::PAYMENT_DEBIT),
                     self::PAYMENT_CREDITS    => get_full_name(self::PAYMENT_CREDITS),
                     self::PAYMENT_CHEQUE     => get_full_name(self::PAYMENT_CHEQUE));
    }
	
	public static function getSaleStatuses() {
		return array(self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_PICKED, self::STATUS_IN_TRANSIT, self::STATUS_SHIPPED, self::STATUS_COMPLETED, self::STATUS_CANCELLED);
	}
	
	public static function getPurchaseStatuses() {
		return array(self::STATUS_DRAFT, self::STATUS_PENDING, self::STATUS_RECIEVED, self::STATUS_COMPLETED, self::STATUS_CANCELLED);
	}
}