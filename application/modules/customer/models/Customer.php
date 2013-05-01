<?php

namespace customer\models;

use dealer\models\Dealer AS Dealer;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity(repositoryClass="customer\models\CustomerRepository")
 *  @Table(name="customers")
 */
class Customer extends Dealer {
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $credits = 0.00;
    
    /**
     * @OneToMany(targetEntity="sale\models\Sale", mappedBy="customer")
     */
    protected $sales;
    
    /**
     * @OneToMany(targetEntity="returns\models\Returns", mappedBy="customer")
     */
    protected $returns;
    
    public function __construct() {
        $this->sales = new ArrayCollection();
    }
    
    public function getSales() {
        return $this->sales;
    }
    
    public function getCredits() {
		return $this->credits;
	}
	
	public function setCredits($credits) {
		$this->credits = $credits;
	}
}