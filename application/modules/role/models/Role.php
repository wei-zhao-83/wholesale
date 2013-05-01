<?php

namespace role\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="roles")
 */
class Role {
    /**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=255, unique=true, nullable=false)
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
     *@ManyToMany(targetEntity="user\models\User", mappedBy="role")
    */
    protected $users;
	
	/**
	 * @ManyToMany(targetEntity="permission\models\Permission", inversedBy="roles")
	 * @JoinTable(name="role_permission",
	 *  joinColumns={@JoinColumn(name="role_id", referencedColumnName="id")},
	 *  inverseJoinColumns={@JoinColumn(name="permission_id", referencedColumnName="id")}
	 *  )
	 */
    protected $permissions;
    
	public function __construct() {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }
    
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getRemovable() {
		return $this->removable;
	}
	
	public function setRemovable($removable) {
		$this->removable = $removable;
	}
	
	public function getUsers() {
		return $this->users;
	}
	
	public function addUser($user) {
		$this->users[] = $user;
	}
	
	public function getPermissions() {
		return $this->permissions;
	}
	
	public function addPermission($permission) {
		$this->permissions[] = $permission;
	}
	
	public function removePermission($permission) {
		$this->permissions->removeElement($permission);
	}
}