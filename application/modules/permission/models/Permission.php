<?php

namespace permission\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="permission\models\PermissionRepository")
 * @Table(name="permissions")
 */
class Permission {
    /**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	private $module;
	
	/**
	 * @Column(type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $removable;
	
	/**
	 * @Column(type="string", length=255, nullable=true)
	 */
	private $description;
	
	/**
	 * @ManyToMany(targetEntity="role\models\Role", mappedBy="permissions")
	 */
    protected $roles;
	
	public function __construct() {
		$this->roles = new ArrayCollection;
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
	
	public function getRemovable() {
		return $this->removable;
	}
	
	public function setRemovable($removable) {
		$this->removable = $removable;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getModule() {
		return $this->module;
	}
	
	public function setModule($module) {
		$this->module = $module;
	}
	
	public function getRoles() {
		return $this->roles;
	}
	
	public function setRole($role) {
		$this->roles[] = $role;
		$role->addPermission($this);
	}
}