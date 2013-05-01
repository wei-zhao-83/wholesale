<?php

namespace image\models;
 
/**
 * @Entity
 * @Table(name="images")
 */
class Image {
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @Column(type="string", length=125, nullable=false)
     */
    private $name;
    
	/**
     * @Column(type="string", length=255, nullable=false)
     */
	private $path;
	
	/**
     * @Column(type="string", length=255, nullable=true)
     */
	private $alt;
	
	/**
     * @Column(type="string")
     */
    private $arrange = 0;
	
    /**
	 * @Column(type="boolean")
	 */
	private $main = 0;
    
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $created_at;
	
	/**
	 * @ManyToMany(targetEntity="category\models\Category", mappedBy="images")
	 */
	protected $category;
	
	/**
	 * @ManyToMany(targetEntity="dealer\models\Dealer", mappedBy="images")
	 */
	protected $dealer;
	
	/**
	 * @ManyToMany(targetEntity="product\models\Product", mappedBy="images")
	 */
	protected $product;
	
    public function __construct() {
		$this->created_at = new \DateTime("now");
	}
    
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

	public function getPath() {
		return $this->path;
	}
	
	public function setPath($path) {
		$this->path = $path;
	}

    public function getAlt() {
		return $this->alt;
	}
	
	public function setAlt($alt) {
		$this->alt = $alt;
	}
    
	public function getMain() {
		return !empty($this->main) ? 1 : 0 ;
	}
	
	public function setMain($main) {
		$this->main = $main;
	}

	public function getArrange() {
		return $this->arrange;
	}
	
	public function setArrange($arrange) {
		$this->arrange = $arrange;
	}

	public function getCreatedAt() {
		return $this->created_at->format('Y-m-d H:i:s');
	}
	
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}
	
	//public function getCategory() {
	//	return $this->category[0];
	//}
	//
	//public function setCategory($category) {
	//	$this->category[] = $category;
	//	$category->addImage($this);
	//}
	//
	//public function getDealer() {
	//	return $this->dealer[0];
	//}
	//
	//public function setDealer($dealer) {
	//	$this->dealer[] = $dealer;
	//	$dealer->addImage($this);
	//}
	//
	//public function getProduct() {
	//	return $this->product[0];
	//}
	//
	//public function setProduct($product) {
	//	$this->product[] = $product;
	//	$product->addImage($this);
	//}
}
?>