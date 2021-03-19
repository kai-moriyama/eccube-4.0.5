<?php

namespace Plugin\SamplePlugin\Entity;

use Doctrine\ORM\Mapping as ORM;



// @ORM\TableでDBのテーブルにマッピングする。
// @ORM\Entityでこのクラスに対するRepositoryを定義する。
/**
 * SamplePluginConfig
 *
 * @ORM\Table(name="plg_sample_plugin_config")
 * @ORM\Entity(repositoryClass="Plugin\SamplePlugin\Repository\SamplePluginConfigRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()

 */
class SamplePluginConfig
{
    // @ORM\Columnでプロパティ$idをDBテーブルのカラムにマッピングする。
    // @ORM\Idで$idがプライマリーキーであることを定義する。
    // @ORM\GeneratedValueで"IDENTITY"を指定し、idを自動採番する。
    // privateでプロパティ$idを定義する。
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="price", type="decimal", precision=12, scale=2, nullable=true, options={"unsigned":true})
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="option_gift", type="boolean", options={"default":false})
     */
    private $option_gift = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $create_date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del_flg", type="boolean", options={"default":false})
     */
    private $del_flg = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return SamplePluginConfig
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return SamplePluginConfig
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get optionGift.
     *
     * @return boolean
     */
    public function isOptionGift()
    {
        return $this->option_gift;
    }

    /**
     * Set optionGift.
     *
     * @param boolean $optionGift
     *
     * @return SamplePluginConfig
     */
    public function setOptionGift($optionGift)
    {
        $this->option_gift = $optionGift;
        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return SamplePluginConfig
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;
        return $this;
    }

    /**
     * Get delFlg.
     *
     * @return boolean
     */
    public function isDelFlg()
    {
        return $this->del_flg;
    }

    /**
     * Set delFlg.
     *
     * @param boolean $delFlg
     *
     * @return SamplePluginConfig
     */
    public function setDelFlg($delFlg)
    {
        $this->del_flg = $delFlg;
        return $this;
    }
}
