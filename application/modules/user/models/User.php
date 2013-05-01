<?php

namespace user\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="user\models\UserRepository")
 * @Table(name="users")
 */
class User {
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
	private $username;
	
	/**
	 * @Column(type="string", length=255, nullable=false)
	 */
	private $password;
	
	/**
	 * @Column(type="string", length=6, nullable=true)
	 */
	private $salt;
	
	/**
	 * @Column(type="string", length=255, nullable=false)
	 */
	private $email;
	
	/**
	 * @Column(type="string", length=64, nullable=true)
	 */
	private $phone;
	
	/**
	 * @Column(type="string", length=64, nullable=true)
	 */
	private $token;
	
	/**
	 * @Column(type="boolean", nullable=false)
	 */
	private $active;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $created_at;
	
	/**
	 * @Column(type="datetime", nullable=true)
	 */
	private $last_login_at;
	
	/**
	 * @ManyToMany(targetEntity="role\models\Role", inversedBy="users", cascade={"persist", "merge", "detach"})
	 * @JoinTable(name="user_role",
	 *  joinColumns={@JoinColumn(name="user_id", referencedColumnName="id", unique=true)},
	 *  inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")}
	 *  )
	 */
    protected $role;
	
	/**
	 * Bidirectional - One-To-Many (INVERSE SIDE)
	 * 
     * @OneToMany(targetEntity="user\models\UserMeta", mappedBy="user", indexBy="meta_key", cascade={"all"})
     * 
     */
    protected $user_metas;
	
	/**
	 * Bidirectional - One-To-Many (INVERSE SIDE)
	 * 
     * @OneToMany(targetEntity="message\models\Message", mappedBy="to", cascade={"all"})
     * 
     */
	private $received_messages;
	
	/**
	 * Bidirectional - One-To-Many (INVERSE SIDE)
	 * 
     * @OneToMany(targetEntity="message\models\Message", mappedBy="from", cascade={"all"})
     * 
     */
	private $sent_messages;
	
	/** @OneToMany(targetEntity="transaction\models\Transaction", mappedBy="user") */
    private $transactions;
	
	public function __construct() {
        $this->created_at = new \DateTime("now");
		$this->user_metas = new ArrayCollection();
    }
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function getSalt() {
		return $this->salt;
	}
	
	public function setSalt($salt) {
		$this->salt = $salt;
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
	
	public function getToken() {
		return $this->token;
	}
	
	public function setToken($token) {
		$this->token = $token;
	}
	
	public function getActive() {
		return $this->active;
	}
	
	public function setActive($active) {
		$this->active = $active;
	}
	
	public function getCreatedAt() {
		return $this->created_at->format('Y-m-d H:i:s');
	}
	
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}
	
	public function getLastLoginAt() {
		return $this->last_login_at->format('Y-m-d H:i:s');
	}
	
	public function setLastLoginAt($last_login_at) {
		$this->last_login_at = $last_login_at;
	}
	
	public function getRole() {
		return $this->role[0];
	}
	
	public function setRole($role) {
		$this->role[] = $role;
        $role->addUser($this);
	}
	
	public function removeRole($role) {
		$this->role->removeElement($role);
	}
	
	public function getUserMeta($meta_key) {
		if (isset($this->user_metas[$meta_key])) {
			return $this->user_metas[$meta_key];
        } else {
			return false;
			//throw new \InvalidArgumentException("The user meta is not exist.");
		}
	}
	
	public function addUserMeta(UserMeta $user_meta) {
		$this->user_metas[$user_meta->getKey()] = $user_meta;
		$user_meta->setUser($this);
	}
	
	public function getUserMetas() {
		return $this->user_metas;
	}
	
	public function removeUserMeta($meta) {
		$this->user_metas->removeElement($meta);
	}
}