<?php

namespace dealer\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="dealers")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="dealer_type", type="string")
 * @DiscriminatorMap({"vendor" = "vendor\models\Vendor", "customer" = "customer\models\Customer"})
 */
class Dealer {
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
    private $email;
    
	/**
	 * @Column(type="string", length=64)
	 */
	private $phone;
	
	/**
	 * @Column(type="string", length=64)
	 */
	private $fax;
	
    /**
	 * @Column(type="string", length=255)
	 */
	private $description;
    
	/**
	 * @Column(type="string", length=255)
	 */
	private $shipping_address;
	
	/**
	 * @Column(type="string", length=125)
	 */
	private $shipping_city;
	
	/**
	 * @Column(type="string", length=2)
	 */
	private $shipping_province_abbr;
	
	/**
	 * @Column(type="string", length=7)
	 */
	private $shipping_postal;

	/**
	 * @Column(type="string", length=255)
	 */
	private $billing_address;
	
	/**
	 * @Column(type="string", length=125)
	 */
	private $billing_city;
	
	/**
	 * @Column(type="string", length=2)
	 */
	private $billing_province_abbr;
	
	/**
	 * @Column(type="string", length=7)
	 */
	private $billing_postal;
    
    /**
	 * @Column(type="datetime", nullable=true)
	 */
	private $deleted_at;
    
    /**
	 * @ManyToMany(targetEntity="tag\models\Tag", inversedBy="dealers")
	 * @JoinTable(name="dealer_tag",
	 *  joinColumns={@JoinColumn(name="dealer_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id")}
	 *  )
	 */
    protected $tags;
    
    /**
	 * @ManyToMany(targetEntity="image\models\Image", inversedBy="dealer", cascade={"all"})
	 * @JoinTable(name="dealer_image",
	 *  joinColumns={@JoinColumn(name="dealer_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="image_id", referencedColumnName="id")}
	 *  )
	 */
    protected $images;
    
	/**
     * @OneToMany(targetEntity="dealer\models\DealerContact", mappedBy="dealer", cascade={"all"})
     * 
     */
    protected $contacts;
    
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

	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}	
	
	public function getPhone() {
		return $this->phone;
	}
	
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getFax() {
		return $this->fax;
	}
	
	public function setFax($fax) {
		$this->fax = $fax;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
    
	public function getShippingAddress() {
		return $this->shipping_address;
	}
	
	public function setShippingAddress($address) {
		$this->shipping_address = $address;
	}

	public function getShippingCity() {
		return $this->shipping_city;
	}
	
	public function setShippingCity($city) {
		$this->shipping_city = $city;
	}

	public function getShippingProvinceAbbr() {
		return $this->shipping_province_abbr;
	}
	
	public function setShippingProvinceAbbr($abbr) {
		$this->shipping_province_abbr = $abbr;
	}

	public function getShippingPostal() {
		return $this->shipping_postal;
	}
	
	public function setShippingPostal($postal) {
		$this->shipping_postal = $postal;
	}

	public function getBillingAddress() {
		return $this->billing_address;
	}
	
	public function setBillingAddress($address) {
		$this->billing_address = $address;
	}

	public function getBillingCity() {
		return $this->billing_city;
	}
	
	public function setBillingCity($city) {
		$this->billing_city = $city;
	}

	public function getBillingProvinceAbbr() {
		return $this->billing_province_abbr;
	}
	
	public function setBillingProvinceAbbr($abbr) {
		$this->billing_province_abbr = $abbr;
	}

	public function getBillingPostal() {
		return $this->billing_postal;
	}
	
	public function setBillingPostal($postal) {
		$this->billing_postal = $postal;
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
	
	public function addContact($contact) {
		$this->contacts[] = $contact;
		$contact->setDealer($this);
	}
	
	public function getContacts() {
		return $this->contacts;
	}
	
	public function removeContact($contact) {
		$this->contacts->removeElement($contact);
	}
}
?>