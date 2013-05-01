<?php

namespace category\models;

use Doctrine\Common\Collections\ArrayCollection;
 
/**
 * @Entity(repositoryClass="category\models\CategoryRepository")
 * @Table(name="categories")
 */
class Category {
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
     * @Column(type="string", length=125, unique=true, nullable=false)
     */
    private $slug;
    
    /**
	 * @Column(type="boolean", nullable=false)
	 */
	private $active;
    
    /**
     * @Column(type="string")
     */
    private $arrange = 0;
    
    /**
	 * @Column(type="string", length=255, nullable=true)
	 */
	private $description;
    
    /**
     * @Column(type="string", length=125, nullable=true)
     */
    private $seo_title;
    
    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $seo_url;
    
    /**
     * @Column(type="string", length=255, nullable=true)
     */
    private $seo_canonical_link;
    
    /**
     * @Column(type="text", nullable=true)
     */
    private $seo_keywords;
    
    /**
	 * @Column(type="boolean", nullable=false)
	 */
	private $seo_robots = 0;
    
    /**
	 * @Column(type="datetime", nullable=true)
	 */
	private $deleted_at;
	
    /**
	 * @ManyToMany(targetEntity="tag\models\Tag", inversedBy="categories")
	 * @JoinTable(name="category_tag",
	 *  joinColumns={@JoinColumn(name="category_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
	 *  )
	 */
    protected $tags;
    
    /**
	 * @ManyToMany(targetEntity="image\models\Image", inversedBy="category", cascade={"all"})
	 * @JoinTable(name="category_image",
	 *  joinColumns={@JoinColumn(name="category_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="image_id", referencedColumnName="id")}
	 *  )
	 */
    protected $images;
    
	/**
     * @OneToMany(targetEntity="product\models\Product", mappedBy="category")
     * 
     */
    protected $products;
    
    public function __construct() {
        $this->images = new ArrayCollection;
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
    
    public function getSlug() {
		return $this->slug;
	}
	
	public function setSlug($slug) {
		$this->slug = $slug;
	}
    
	public function getActive() {
		return $this->active;
	}
	
	public function setActive($active) {
		$this->active = $active;
	}

	public function getArrange() {
		return $this->arrange;
	}
	
	public function setArrange($arrange) {
		$this->arrange = $arrange;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
    
    public function getSEOTitle() {
		return $this->seo_title;
	}
	
	public function setSEOTitle($seo_title) {
		$this->seo_title = $seo_title;
	}
    
    public function getSEOURL() {
		return $this->seo_url;
	}
	
	public function setSEOURL($seo_url) {
		$this->seo_url = $seo_url;
	}
    
    public function getSEOCanonicalLink() {
		return $this->seo_canonical_link;
	}
	
	public function setSEOCanonicalLink($canonical_link) {
		$this->seo_canonical_link = $canonical_link;
	}
    
    public function getSEOKeywords() {
		return $this->seo_keywords;
	}
	
	public function setSEOKeywords($keywords) {
		$this->seo_keywords = $keywords;
	}
    
    public function getSEORobots() {
		return $this->seo_robots;
	}
	
	public function setSEORobots($seo_robots) {
		$this->seo_robots = $seo_robots;
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
    
    public function addProduct($product) {
		$this->products[] = $product;
	}
	
	public function getProducts() {
		return $this->products;
	}
}
?>