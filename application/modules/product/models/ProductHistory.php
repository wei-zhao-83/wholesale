<?php

namespace product\models;

/**
 * @Entity
 * @Table(name="product_history")
 */
class ProductHistory {
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=64)
	 */
	private $timestamp;
	
	/**
	 * @Column(type="text", nullable=true)
	 */
	private $changes;
	
	/**
     * @ManyToOne(targetEntity="Product", inversedBy="product_changes")
     */
	private $product;
	
	public function getId() {
		return $this->id;
	}
	
	public function getTimeStamp() {
		return $this->timestamp;
	}
	
	public function setTimeStamp($timestamp) {
		$this->timestamp = $timestamp;
	}
	
	public function getChanges() {
		$result = @unserialize($this->changes);
		
		if ($this->changes === 'b:0;' || $result !== false) {
			return unserialize($this->changes);
		} else {
			return $this->changes;
		}
	}
	
	public function setChanges($changes) {
		$this->changes = serialize($changes);
	}
	
	public function getProduct() {
		return $this->product;
	}
	
	public function setProduct(Product $product) {
		$this->product = $product;
	}
}

?>