<?php
/**
 * Paragraph Entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParagraphRepository")
 * @ORM\Table(name="paragraphs")
 */
class Paragraph
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subheading;

    /**
     * @ORM\Column(type="text")
     */
    private $textContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="paragraphs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Picture")
     */
    private $picture;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    /**
     * @param string $subheading
     *
     * @return Paragraph
     */
    public function setSubheading(string $subheading): self
    {
        $this->subheading = $subheading;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    /**
     * @param string $textContent
     *
     * @return Paragraph
     */
    public function setTextContent(string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }

    /**
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article|null $article
     *
     * @return Paragraph
     */
    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return Picture|null
     */
    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    /**
     * @param Picture|null $picture
     *
     * @return Paragraph
     */
    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
