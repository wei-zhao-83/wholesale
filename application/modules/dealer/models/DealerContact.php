<?php

namespace dealer\models;

/**
 * @Entity
 * @Table(name="dealer_contacts")
 */
class DealerContact {
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=64, nullable=false)
	 */
	private $name;
	
	/**
	 * @Column(type="string")
	 */
	private $direct_line;
	
	/**
	 * @Column(type="string", length=64)
	 */
	private $phone;
	
	/**
	 * @Column(type="text")
	 */
	private $comment;
	
	/**
     * @ManyToOne(targetEntity="Dealer", inversedBy="contacts")
     */
	private $dealer;
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getPhone() {
		return $this->phone;
	}
	
	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	public function getDirectLine() {
		return $this->direct_line;
	}
	
	public function setDirectLine($direct_line) {
		$this->direct_line = $direct_line;
	}
	
	public function getComment() {
		return $this->comment;
	}
	
	public function setComment($comment) {
		$this->comment = $comment;
	}
	
	public function getDealer() {
		return $this->dealer;
	}
	
	public function setDealer(Dealer $dealer) {
		$this->dealer = $dealer;
	}
}

?>