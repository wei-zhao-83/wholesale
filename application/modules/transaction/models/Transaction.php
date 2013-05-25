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
    /**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
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
     * @ManyToOne(targetEntity="transaction_status\models\TransactionStatus", inversedBy="status")
     */
    protected $status;
	
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
}