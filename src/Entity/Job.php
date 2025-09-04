<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\DTO\JobInput;
use App\Enum\StatusEnum;
use App\Repository\JobRepository;
use App\State\JobStateProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: [AbstractNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'company',
                'url',
                'city',
                'presence',
                'website',
                'salary',
                'askedSalary',
                'creationDate',
                'applicationDate',
                'actionsToTake'
                ],
                'groups' => ['job:read']
            ],
        ),
        new Post(
            input: JobInput::class,
            processor: JobStateProcessor::class
        ),
        new Delete(),
        new Patch(processor: JobStateProcessor::class)
    ],
)]
#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('job:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('job:read')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('job:read')]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    #[Groups('job:read')]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('job:read')]
    private ?Website $website = null;

    #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'd-m-Y'])]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups('job:read')]
    private ?\DateTime $creationDate = null;

    #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => 'd-m-Y'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('job:read')]
    private ?\DateTime $applicationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('job:read')]
    private ?string $salary = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('job:read')]
    private ?string $askedSalary = null;

    #[ORM\Column(length: 255)]
    #[Groups('job:read')]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[Groups('job:read')]
    private ?Presence $presence = null;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\ManyToMany(targetEntity: Action::class, inversedBy: 'jobs')]
    #[Groups('job:read')]
    private Collection $actionsToTake;

    #[ORM\Column]
    #[Groups('job:read')]
    private ?int $status = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'job')]
    private Collection $contact;

    public string $statusText;

    public function __construct()
    {
        $this->actionsToTake = new ArrayCollection();
        $this->contact = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTime $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getApplicationDate(): ?\DateTime
    {
        return $this->applicationDate;
    }

    public function setApplicationDate(\DateTime $applicationDate): static
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getAskedSalary(): ?string
    {
        return $this->askedSalary;
    }

    public function setAskedSalary(?string $askedSalary): static
    {
        $this->askedSalary = $askedSalary;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPresence(): ?Presence
    {
        return $this->presence;
    }

    public function setPresence(?Presence $presence): static
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActionsToTake(): Collection
    {
        return $this->actionsToTake;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actionsToTake->contains($action)) {
            $this->actionsToTake->add($action);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        $this->actionsToTake->removeElement($action);

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contact->contains($contact)) {
            $this->contact->add($contact);
            $contact->setJob($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contact->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getJob() === $this) {
                $contact->setJob(null);
            }
        }

        return $this;
    }

    public function getStatusText(): void
    {
        $this->statusText = StatusEnum::from($this->getStatus())->name;
    }
}
