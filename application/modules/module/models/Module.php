<?php

namespace module\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="modules")
 */
class Module {
	
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=255, nullable=false)
	 */
	private $name;
	
	/**
	 * @Column(type="string", length=255, unique=true, nullable=false)
	 */
	private $slug;
	
	/**
	 * @Column(type="string", length=20, nullable=true)
	 */
	private $version;
	
	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $core = 0;
	
	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $active = 1;
	
	/**
	 * @Column(type="text", nullable=true)
	 */
	private $description;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $last_updated_at;
	
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
	
	public function getSlug() {
		return $this->slug;
	}
	
	public function setSlug($slug) {
		$this->slug = $slug;
	}
	
	public function getVersion() {
		return $this->version;
	}
	
	public function setVersion($version) {
		$this->version = $version;
	}
	
	public function getCore() {
		return $this->core;
	}
	
	public function setCore($core) {
		$this->core = $core;
	}
	
	public function getActive() {
		return $this->active;
	}
	
	public function setActive($active) {
		$this->active = $active;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getLastUpdatedAt() {
		return $this->last_updated_at->format('Y-m-d H:i:s');
	}
	
	public function setLastUpdatedAt($last_updated_at) {
		$this->last_updated_at = $last_updated_at;
	}
}
?>