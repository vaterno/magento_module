<?php

namespace PrAnd\Vendor\Api\Data;

/**
 * @api
 */
interface VendorInterface
{
    const ENTITY = 'prand_vendor';
    const FULL_ENTITY = 'prand_vendor_entity';

    const ID = 'entity_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const IMAGE = 'image';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const ATTRIBUTES = [
        self::ID,
        self::NAME,
        self::DESCRIPTION,
        self::IMAGE,
        self::CREATED_AT,
        self::UPDATED_AT
    ];

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $value
     * @return $this
     */
    public function setId(mixed $value);

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self;

    /**
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): self;

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt): self;
}
