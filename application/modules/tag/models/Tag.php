<?php

namespace tag\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="tag\models\TagRepository")
 * @Table(name="tags")
 */
class Tag {
    /**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=50, unique=true, nullable=false)
	 */
	private $name;
    
    /**
	 * @ManyToMany(targetEntity="category\models\Category", mappedBy="tags")
	 */
    protected $categories;
    
    /**
	 * @ManyToMany(targetEntity="dealer\models\Dealer", mappedBy="tags")
	 */
    protected $dealers;
	
    /**
	 * @ManyToMany(targetEntity="product\models\Product", mappedBy="tags")
	 */
    protected $products;
    
    public function __construct() {}
    
    public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
    
//    public function getCategories() {
//		return $this->categories;
//	}
//	
//	public function setCategory($category) {
//		$this->categories[] = $category;
//		$category->addTag($this);
//	}
//    
//    public function getDealers() {
//		return $this->dealers;
//	}
//	
//	public function setDealer($dealer) {
//		$this->dealers[] = $dealer;
//		$dealer->addTag($this);
//	}
//    
//    public function getProducts() {
//		return $this->products;
//	}
//	
//	public function setProduct($product) {
//		$this->products[] = $product;
//		$product->addTag($this);
//	}
}