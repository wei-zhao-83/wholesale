<?php

namespace Proxies\__CG__\vendor\models;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Vendor extends \vendor\models\Vendor implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getOrderFrequency()
    {
        $this->__load();
        return parent::getOrderFrequency();
    }

    public function setOrderFrequency($order_frequency)
    {
        $this->__load();
        return parent::setOrderFrequency($order_frequency);
    }

    public function getHstNumber()
    {
        $this->__load();
        return parent::getHstNumber();
    }

    public function setHstNumber($hst_number)
    {
        $this->__load();
        return parent::setHstNumber($hst_number);
    }

    public function getBankName()
    {
        $this->__load();
        return parent::getBankName();
    }

    public function setBankName($bank_name)
    {
        $this->__load();
        return parent::setBankName($bank_name);
    }

    public function getBankBranch()
    {
        $this->__load();
        return parent::getBankBranch();
    }

    public function setBankBranch($bank_branch)
    {
        $this->__load();
        return parent::setBankBranch($bank_branch);
    }

    public function getBankAccount()
    {
        $this->__load();
        return parent::getBankAccount();
    }

    public function setBankAccount($bank_account)
    {
        $this->__load();
        return parent::setBankAccount($bank_account);
    }

    public function addProduct($product)
    {
        $this->__load();
        return parent::addProduct($product);
    }

    public function removeProduct($product)
    {
        $this->__load();
        return parent::removeProduct($product);
    }

    public function getPurchases()
    {
        $this->__load();
        return parent::getPurchases();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function setEmail($email)
    {
        $this->__load();
        return parent::setEmail($email);
    }

    public function getPhone()
    {
        $this->__load();
        return parent::getPhone();
    }

    public function setPhone($phone)
    {
        $this->__load();
        return parent::setPhone($phone);
    }

    public function getFax()
    {
        $this->__load();
        return parent::getFax();
    }

    public function setFax($fax)
    {
        $this->__load();
        return parent::setFax($fax);
    }

    public function getDescription()
    {
        $this->__load();
        return parent::getDescription();
    }

    public function setDescription($description)
    {
        $this->__load();
        return parent::setDescription($description);
    }

    public function getShippingAddress()
    {
        $this->__load();
        return parent::getShippingAddress();
    }

    public function setShippingAddress($address)
    {
        $this->__load();
        return parent::setShippingAddress($address);
    }

    public function getShippingCity()
    {
        $this->__load();
        return parent::getShippingCity();
    }

    public function setShippingCity($city)
    {
        $this->__load();
        return parent::setShippingCity($city);
    }

    public function getShippingProvinceAbbr()
    {
        $this->__load();
        return parent::getShippingProvinceAbbr();
    }

    public function setShippingProvinceAbbr($abbr)
    {
        $this->__load();
        return parent::setShippingProvinceAbbr($abbr);
    }

    public function getShippingPostal()
    {
        $this->__load();
        return parent::getShippingPostal();
    }

    public function setShippingPostal($postal)
    {
        $this->__load();
        return parent::setShippingPostal($postal);
    }

    public function getBillingAddress()
    {
        $this->__load();
        return parent::getBillingAddress();
    }

    public function setBillingAddress($address)
    {
        $this->__load();
        return parent::setBillingAddress($address);
    }

    public function getBillingCity()
    {
        $this->__load();
        return parent::getBillingCity();
    }

    public function setBillingCity($city)
    {
        $this->__load();
        return parent::setBillingCity($city);
    }

    public function getBillingProvinceAbbr()
    {
        $this->__load();
        return parent::getBillingProvinceAbbr();
    }

    public function setBillingProvinceAbbr($abbr)
    {
        $this->__load();
        return parent::setBillingProvinceAbbr($abbr);
    }

    public function getBillingPostal()
    {
        $this->__load();
        return parent::getBillingPostal();
    }

    public function setBillingPostal($postal)
    {
        $this->__load();
        return parent::setBillingPostal($postal);
    }

    public function getDeletedAt()
    {
        $this->__load();
        return parent::getDeletedAt();
    }

    public function setDeletedAt($deleted_at)
    {
        $this->__load();
        return parent::setDeletedAt($deleted_at);
    }

    public function getTags()
    {
        $this->__load();
        return parent::getTags();
    }

    public function addTag($tag)
    {
        $this->__load();
        return parent::addTag($tag);
    }

    public function removeTag($tag)
    {
        $this->__load();
        return parent::removeTag($tag);
    }

    public function addImage($image)
    {
        $this->__load();
        return parent::addImage($image);
    }

    public function getImages()
    {
        $this->__load();
        return parent::getImages();
    }

    public function removeImage($image)
    {
        $this->__load();
        return parent::removeImage($image);
    }

    public function addContact($contact)
    {
        $this->__load();
        return parent::addContact($contact);
    }

    public function getContacts()
    {
        $this->__load();
        return parent::getContacts();
    }

    public function removeContact($contact)
    {
        $this->__load();
        return parent::removeContact($contact);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'email', 'phone', 'fax', 'description', 'shipping_address', 'shipping_city', 'shipping_province_abbr', 'shipping_postal', 'billing_address', 'billing_city', 'billing_province_abbr', 'billing_postal', 'deleted_at', 'tags', 'images', 'contacts', 'order_frequency', 'hst_number', 'bank_name', 'bank_branch', 'bank_account', 'purchases', 'products');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}