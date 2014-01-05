<?php

namespace Ahonymous\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollaction;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Ahonymous\Bundle\BlogBundle\Entity\Repository\CommentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */
class Comment
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
     * @var ArrayCollaction $authors
     *
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="comments")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\Length(min = 10, minMessage = "Comment must be for {{ limit }} chars.")
     */
    private $body;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @var ArrayCollaction $comments
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     */
    private $article;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
