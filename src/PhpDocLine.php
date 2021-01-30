<?php

namespace Loot\PhpDocReader;

final class PhpDocLine
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array|string
     */
    private $description;

    /**
     * @var string
     */
    private $variable;

    /**
     * PhpDocLine constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setDescription(string $description)
    {
        $this->description = json_decode($description, true) ?? $description;

        return $this;
    }

    /**
     * @return string|array
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setVariable(string $variable)
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * @return string
     */
    public function getVariable(): ?string
    {
        return $this->variable;
    }
}
