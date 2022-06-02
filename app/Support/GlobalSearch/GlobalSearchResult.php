<?php

namespace App\Support\GlobalSearch;

use Illuminate\Contracts\Support\Arrayable;

class GlobalSearchResult implements Arrayable
{
    protected string $type;
    protected mixed $id;
    protected string $name;
    protected string $route;

    public function __construct(string $model, string $type, mixed $id, string $name, string $route)
    {
        $this->model = $model;
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->route = $route;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function toArray()
    {
        return [
            'model' => $this->model,
            'type' => $this->type,
            'id' => $this->id,
            'name' => $this->name,
            'route' => $this->route,
        ];
    }
}
