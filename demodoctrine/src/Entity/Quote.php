<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */
declare(strict_types=1);

namespace Module\DemoDoctrine\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Module\DemoDoctrine\Repository\QuoteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Quote
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_quote", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="Module\DemoDoctrine\Entity\QuoteLang", cascade={"persist", "remove"}, mappedBy="quote")
     */
    private $quoteLangs;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date_upd", type="datetime")
     */
    private $dateUpd;

    public function __construct()
    {
        $this->quoteLangs = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getQuoteLangs()
    {
        return $this->quoteLangs;
    }

    /**
     * @param int $langId
     * @return QuoteLang|null
     */
    public function getQuoteLangByLangId(int $langId)
    {
        foreach ($this->quoteLangs as $quoteLang) {
            if ($langId === $quoteLang->getLang()->getId()) {
                return $quoteLang;
            }
        }

        return null;
    }

    /**
     * @param QuoteLang $quoteLang
     * @return $this
     */
    public function addQuoteLang(QuoteLang $quoteLang)
    {
        $quoteLang->setQuote($this);
        $this->quoteLangs->add($quoteLang);

        return $this;
    }

    /**
     * @return string
     */
    public function getQuoteContent()
    {
        if ($this->quoteLangs->count() <= 0) {
            return '';
        }

        $quoteLang = $this->quoteLangs->first();

        return $quoteLang->getContent();
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Set dateAdd.
     *
     * @param DateTime $dateAdd
     *
     * @return $this
     */
    public function setDateAdd(DateTime $dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd.
     *
     * @return DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set dateUpd.
     *
     * @param DateTime $dateUpd
     *
     * @return $this
     */
    public function setDateUpd(DateTime $dateUpd)
    {
        $this->dateUpd = $dateUpd;

        return $this;
    }

    /**
     * Get dateUpd.
     *
     * @return DateTime
     */
    public function getDateUpd()
    {
        return $this->dateUpd;
    }

    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setDateUpd(new DateTime());

        if ($this->getDateAdd() == null) {
            $this->setDateAdd(new DateTime());
        }
    }
}
