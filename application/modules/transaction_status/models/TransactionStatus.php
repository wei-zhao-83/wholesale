<?php

namespace transaction_status\models;

/**
 * @Entity(repositoryClass="transaction_status\models\TransactionStatusRepository")
 * @Table(name="transaction_status")
 */
class TransactionStatus {
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=125, unique=true, nullable=false)
	 */
	private $name;
	
	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $core;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $deleted_at;
	
	/**
     * @OneToMany(targetEntity="transaction\models\Transaction", mappedBy="status")
     */
    protected $transactions;
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getCore() {
		return $this->core;
	}
	
	public function setCore($core) {
		$this->core = $core;
	}
	
	public function getDeletedAt() {
		return $this->deleted_at->format('Y-m-d H:i:s');
	}
	
	public function setDeletedAt($deleted_at) {
		$this->deleted_at = $deleted_at;
	}
}

?>