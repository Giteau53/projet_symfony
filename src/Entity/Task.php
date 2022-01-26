<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\Table(name="tasks")
 */
class Task
{

      /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Date 
     */
    private $start_date_str;

      /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Date 
     */
    private $end_date_str;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     
     */
    private $id;

   /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length( min = 2, max = 50)
      
     */
    private $name;

     /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(      
     *      max = 150,      
     *      maxMessage = "Ta description ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $start_date;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $end_date;

  

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;


    public function setStartDateStr(string $start_date_str)
    {
        $this->start_date_str = $start_date_str;
        return $this;
    }

    public function setEndDateStr(string $end_date_str)
    {
        $this->end_date_str = $end_date_str;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }



    public function getProject(): ?project
    {
        return $this->project;
    }

    public function setProject(?project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
