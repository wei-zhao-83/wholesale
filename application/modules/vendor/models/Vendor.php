<?php

namespace vendor\models;

use dealer\models\Dealer AS Dealer;
use product\models\Product AS Product;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="vendor\models\VendorRepository")
 *  @Table(name="vendors")
 */
class Vendor extends Dealer {
	/**
	 * @Column(type="string", length=4)
	 */
	private $order_frequency = 0;
	
    /**
	 * @Column(type="string", length=64)
	 */
	private $hst_number;
    
    /**
	 * @Column(type="string", length=128)
	 */
    private $bank_name;
    
    /**
	 * @Column(type="string", length=128)
	 */
    private $bank_branch;
    
    /**
	 * @Column(type="string", length=128)
	 */
    private $bank_account;
    
	/**
     * @OneToMany(targetEntity="purchase\models\Purchase", mappedBy="vendor")
     */
    protected $purchases;
	
    /**
	 * @ManyToMany(targetEntity="product\models\Product", inversedBy="vendors")
	 * @JoinTable(name="vendor_product",
	 *  joinColumns={@JoinColumn(name="vendor_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
	 *  )
	 */
    protected $products;
    
    public function __construct() {
        $this->products = new ArrayCollection();
    }
    
	public function getOrderFrequency() {
		return $this->order_frequency;
	}
	
	public function setOrderFrequency($order_frequency) {
		$this->order_frequency = $order_frequency;
	}
    
    public function getHstNumber() {
		return $this->hst_number;
	}
	
	public function setHstNumber($hst_number) {
		$this->hst_number = $hst_number;
	}
    
    public function getBankName() {
		return $this->bank_name;
	}
	
	public function setBankName($bank_name) {
		$this->bank_name = $bank_name;
	}
    
    public function getBankBranch() {
		return $this->bank_branch;
	}
	
	public function setBankBranch($bank_branch) {
		$this->bank_branch = $bank_branch;
	}
    
    public function getBankAccount() {
		return $this->bank_account;
	}
    
    public function setBankAccount($bank_account) {
		$this->bank_account = $bank_account;
	}
    
	public function getProducts() {
		return $this->products;
	}
	
    public function addProduct($product) {
        $product->addVendor($this);
        $this->products[] = $product;
    }
    
    public function removeProduct($product) {
        $this->products->removeElement($product);
    }
    
    public function getPurchases() {
        return $this->purchases;
    }
}

