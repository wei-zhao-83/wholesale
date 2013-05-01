<?php

namespace message\models;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="message\models\MessageRepository")
 * @Table(name="messages")
 */
class Message {
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
	/**
	 * @Column(type="string", length=256)
	 */
	private $subject;
    
    /**
	 * @Column(type="text")
	 */
	private $content;
	
    /**
	 * @Column(type="datetime", nullable=true)
	 */
	private $created_at;
    
    /**
	 * @Column(type="boolean", nullable=false)
	 */
	private $unread;
    
    /**
	 * Bidirectional(OWNING SIDE)
	 * 
     * @ManyToOne(targetEntity="user\models\User", inversedBy="sent_messages")
     */
    private $from;
    
    /**
	 * Bidirectional(OWNING SIDE)
	 * 
     * @ManyToOne(targetEntity="user\models\User", inversedBy="received_messages")
     */
    private $to;
    
    public function __construct() {
        $this->created_at = new \DateTime("now");
    }
    
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
    
    public function getSubject() {
		return $this->subject;
	}
	
	public function setSubject($subject) {
		$this->subject = $subject;
	}
    
    public function getContent() {
		return $this->content;
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
    
    public function getCreatedAt() {
		return $this->created_at->format('Y-m-d H:i:s');
	}
	
	public function setCreatedAt($created_at) {
		$this->created_at = $created_at;
	}
    
    public function getUnread() {
		return $this->unread;
	}
	
	public function setUnread($unread) {
		$this->unread = $unread;
	}
    
    public function getReceiver() {
		return $this->to;
	}
	
	public function setReceiver($user) {
		$this->to = $user;
	}
    
    public function getSender() {
		return $this->from;
	}
	
	public function setSender($user) {
		$this->from = $user;
	}
}
?>