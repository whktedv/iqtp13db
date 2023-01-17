<?php
namespace Ud\Iqtp13db\Domain\Model;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungserstberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Berater
 */
class Berater extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected $username = '';
    
    /**
     * @var string
     */
    protected $password = '';
    
    /**
     * @var ?ObjectStorage<\Ud\Iqtp13db\Domain\Model\UserGroup>
     */
    protected $usergroup;
    
    /**
     * @var string
     */
    protected $firstName = '';
    
    /**
     * @var string
     */
    protected $lastName = '';
    
    /**
     * @var string
     */
    protected $email = '';
    
    /**
     * @var string
     */
    protected $company = '';
    
    /**
     * @var \DateTime|null
     */
    protected $lastlogin;
    
    /**
     * Constructs a new Front-End User
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = '', $password = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->initializeObject();
    }
    
    /**
     * Called again with initialize object, as fetching an entity from the DB does not use the constructor
     */
    public function initializeObject()
    {
        $this->usergroup = $this->usergroup ?? new ObjectStorage();
    }
    
    /**
     * Sets the username value
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    /**
     * Returns the username value
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Sets the password value
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * Returns the password value
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Sets the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @param ObjectStorage<UserGroup> $usergroup
     */
    public function setUsergroup(ObjectStorage $usergroup)
    {
        $this->usergroup = $usergroup;
    }
    
    /**
     * Adds a usergroup to the frontend user
     *
     * @param UserGroup $usergroup
     */
    public function addUsergroup(UserGroup $usergroup)
    {
        $this->usergroup->attach($usergroup);
    }
    
    /**
     * Removes a usergroup from the frontend user
     *
     * @param UserGroup $usergroup
     */
    public function removeUsergroup(UserGroup $usergroup)
    {
        $this->usergroup->detach($usergroup);
    }
    
    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return ObjectStorage<UserGroup> An object storage containing the usergroup
     */
    public function getUsergroup(): ObjectStorage
    {
        return $this->usergroup;
    }
    
    /**
     * Sets the firstName value
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    
    /**
     * Returns the firstName value
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * Sets the lastName value
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
    /**
     * Returns the lastName value
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * Sets the email value
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * Returns the email value
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Sets the company value
     *
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }
    
    /**
     * Returns the company value
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    /**
     * Sets the lastlogin value
     *
     * @param \DateTime $lastlogin
     */
    public function setLastlogin(\DateTime $lastlogin)
    {
        $this->lastlogin = $lastlogin;
    }
    
    /**
     * Returns the lastlogin value
     *
     * @return \DateTime
     */
    public function getLastlogin()
    {
        return $this->lastlogin;
    }
    
}
