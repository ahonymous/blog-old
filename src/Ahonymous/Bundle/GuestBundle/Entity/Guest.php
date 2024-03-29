<?php

namespace Ahonymous\Bundle\GuestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Guest
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ahonymous\Bundle\GuestBundle\Entity\GuestRepository")
 */
class Guest
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank(message = "Name don't empty.")
     * @Assert\Length(min = 3, minMessage = "Name must be for {{ limit }} chars.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\NotBlank(message = "Email don't empty.")
     * @Assert\Email(message = "Email don't correct.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Assert\NotBlank(message = "Your message don't empty.")
     * @Assert\Length(min = 100, minMessage = "Message must be for {{ limit }} chars.")
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="createdTime", type="datetime")
     */
    private $createdTime;

    /**
     * @var string
     *
     * @ORM\Column(name="editedTime", type="datetime")
     */
    private $editedTime;

    /**
     * @Gedmo\Slug(fields={"name", "createdTime"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $editedTime
     */
    public function setEditedTime($editedTime = null)
    {
        $this->editedTime = !is_null($editedTime) ? $editedTime : date('Y-m-d H:i:s');

    }

    /**
     * @return string
     */
    public function getEditedTime()
    {
        return $this->editedTime;
    }

    public function __construct(\DateTime $createdTime)
    {
        $this->createdTime = $createdTime;
    }

    /**
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Guest
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param  string $email
     * @return Guest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set message
     *
     * @param  string $message
     * @return Guest
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
