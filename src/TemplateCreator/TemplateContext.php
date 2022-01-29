<?php
namespace TemplateCreator;

class TemplateContext
{

    private string $name;

    private array $scope;

    private array $storage = [];

    private array $errors = [];

    public function __construct(string $name, array $scope = [])
    {
        $this->name = $name;
        $this->scope = $scope;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
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

    /**
     * @param array $scope
     */
    public function setScope(array $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->scope;
    }

    public function addToStorage($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function getFromStorage($key)
    {
        return $this->storage[$key] ?? null;
    }

    public function addToErrors(string $key, string $value)
    {
        $this->errors[$key] = $value;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}