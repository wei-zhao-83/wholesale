<?php

namespace product\models;

use Doctrine\Common\Collections\ArrayCollection;
 
/**
 * @Entity(repositoryClass="product\models\ProductRepository")
 * @Table(name="products")
 */
class Product {
    const UNIT_EACH  = 'each';
    const UNIT_BOX   = 'box';
    const UNIT_CASE  = 'case';
    const UNIT_DOZEN = 'dozen';
    const UNIT_SET   = 'set';
    
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
     * @Column(type="string", length=125)
     */
    private $barcode = 0;
    
    /**
     * @Column(type="string", length=125)
     */
    private $sku = 0;
    
	/**
	 * @Column(type="string", length=50)
	 */
	private $section;
	
    /**
	 * @Column(type="boolean", nullable=false)
	 */
	private $active = 1;
	
    /**
     * @Column(type="boolean", nullable=false)
	 */
	private $no_discount = 0;
    
    /**
	 * @Column(type="string", length=255)
	 */
	private $description;
    
    /**
	 * @Column(type="text")
	 */
	private $comment;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $cost = 0.00;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $suggested_price = 0.00;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $no_service_price = 0.00;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $full_service_price = 0.00;
    
    /**
     * @Column(type="decimal", scale=2)
	 */
	private $cash_and_carry = 0.00;
    
    /**
     * @Column(type="integer")
	 */
	private $total_qty = 0;
	
    /**
     * @Column(type="integer")
	 */
	private $qty_unit = 0;
    
    /**
      * @Column(type="string")
      */
    private $unit = self::UNIT_EACH;
    
    /**
      * @Column(type="integer")
      */
    private $unit_case = '';
    
    /**
	 * @Column(type="datetime", nullable=true)
	 */
	private $deleted_at;
    
    /**
	 * @ManyToMany(targetEntity="tag\models\Tag", inversedBy="products")
	 * @JoinTable(name="product_tag",
	 *  joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
	 *  )
	 */
    protected $tags;
    
    /**
	 * @ManyToMany(targetEntity="image\models\Image", inversedBy="product", cascade={"all"})
	 * @JoinTable(name="product_image",
	 *  joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="image_id", referencedColumnName="id")}
	 *  )
	 */
    protected $images;
    
    /**
     * @ManyToOne(targetEntity="category\models\Category", inversedBy="products")
     * @JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
    
    /**
     * @OneToMany(targetEntity="transaction\models\TransactionItem", mappedBy="product")
     */
    protected $transaction_items;
    
    /**
     * @OneToMany(targetEntity="product\models\ProductHistory", mappedBy="product", indexBy="timestamp", cascade={"all"})
     * 
     */
    protected $product_changes;
    
    /**
	 * @ManyToMany(targetEntity="vendor\models\Vendor", mappedBy="products")
	 */
    protected $vendors;
    
    public function __construct() {
        $this->vendors = new ArrayCollection();
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

	public function getBarcode() {
		return $this->barcode;
	}
	
	public function setBarcode($barcode) {
		$this->barcode = $barcode;
	}	
	
	public function getSKU() {
		return $this->sku;
	}
	
	public function setSKU($sku) {
		$this->sku = $sku;
	}

	public function getSection() {
		return $this->section;
	}
	
	public function setSection($section) {
		$this->section = $section;
	}

	public function getActive() {
		return $this->active;
	}
	
	public function setActive($active) {
		$this->active = $active;
	}

    public function getNoDiscount() {
		return $this->no_discount;
	}
	
	public function setNoDiscount($discount) {
		$this->no_discount = empty($discount) ? 0 : $discount;
	}
    
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
    
    public function getComment() {
		return $this->comment;
	}
	
	public function setComment($comment) {
		$this->comment = $comment;
	}
    
    public function getCost() {
		return $this->cost;
	}
	
	public function setCost($cost) {
		$this->cost = $cost;
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
    
    public function getDeletedAt() {
		return $this->deleted_at->format('Y-m-d H:i:s');
	}
	
	public function setDeletedAt($deleted_at) {
		$this->deleted_at = $deleted_at;
	}
    
    public function getTags() {
		return $this->tags;
	}
	
	public function addTag($tag) {
		$this->tags[] = $tag;
	}
	
	public function removeTag($tag) {
		$this->tags->removeElement($tag);
	}
    
    public function addImage($image) {
		$this->images[] = $image;
	}
	
	public function getImages() {
		return $this->images;
	}
	
	public function removeImage($image) {
		$this->images->removeElement($image);
	}
    
    public function getCategory() {
		return $this->category;
	}
	
	public function setCategory($category) {
		$this->category = $category;
        $category->addProduct($this);
	}
    
    public function getProductChange($timestamp) {
        if (isset($this->product_changes[$timestamp])) {
			return $this->product_changes[$timestamp];
        } else {
			return false;
		}
    }
    
	public function addProductChange(ProductHistory $history) {
		$this->product_changes[$history->getTimeStamp()] = $history;
		$history->setProduct($this);
	}
	
	public function getProductChanges() {
		return $this->product_changes;
	}
    
    public function addVendor($vendor) {
        $this->vendors[] = $vendor;
    }
    
    public function getVendors() {
        return $this->vendors;
    }
    
    public function removeVendor($vendor) {
        $this->vendors->removeElement($vendor);
    }
    
    public function getQtyUnit() {
		return $this->qty_unit;
	}
	
	public function setQtyUnit($qty_unit) {
		$this->qty_unit = $qty_unit;
	}
    
	public function getTotalQty() {
		return $this->total_qty;
	}
	
	public function setTotalQty($total_qty) {
		$this->total_qty = $total_qty;
	}
	
    public function getUnit() {
		return $this->unit;
	}
    
    public function setUnit($unit) {
        if (!in_array($unit, self::getUOM())) {
            throw new \InvalidArgumentException("Invalid unit of measure");
        }
        
        $this->unit = $unit;
    }
    
    public function getUnitCase() {
		return $this->unit_case;
	}
	
	public function setUnitCase($unit_case) {
		$this->unit_case = $unit_case;
	}
    
	public function getPickedQty() {
		$transaction_items = $this->transaction_items;
		$total_picked = 0;
		
		if ($transaction_items->count() > 0) {
			foreach ($transaction_items as $item) {
				$transaction = $item->getTransaction();
				
				if ($transaction instanceof \sale\models\Sale && !$transaction->getDeletedAt() && $transaction->getStatus()->getId() == 2) { // magic number here..Orz
					$total_picked += $item->getPicked();
				}
			}
		}
		
		return $total_picked;
	}
	
    // Get unit of measure
    public static function getUOM() {
        return array(self::UNIT_EACH, self::UNIT_DOZEN, self::UNIT_BOX, self::UNIT_SET, self::UNIT_CASE);
    }
}
?>