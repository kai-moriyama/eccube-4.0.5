<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Donut')) {
    /**
     * Donut
     *
     * @ORM\Table(name="cstm_dtb_donut")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repository\DonutRepository")
     */
    class Donut extends \Eccube\Entity\AbstractEntity
    {
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
         * @ORM\Column(name="name", type="string", length=255, nullable=true)
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
         * Get id.
         *
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set name.
         *
         * @param string|null $name
         *
         * @return Donut
         */
        public function setName($name = null)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name.
         *
         * @return string|null
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Set price.
         *
         * @param string|null $price
         *
         * @return Donut
         */
        public function setPrice($price = null)
        {
            $this->price = $price;

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
         * Set optionGift.
         *
         * @param boolean $optionGift
         *
         * @return Donut
         */
        public function setOptionGift($optionGift)
        {
            $this->option_gift = $optionGift;

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
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return Donut
         */
        public function setCreateDate($createDate)
        {
            $this->create_date = $createDate;

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
         * Set delFlg.
         *
         * @param boolean $delFlg
         *
         * @return Donut
         */
        public function setDelFlg($delFlg)
        {
            $this->del_flg = $delFlg;

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

    }
}