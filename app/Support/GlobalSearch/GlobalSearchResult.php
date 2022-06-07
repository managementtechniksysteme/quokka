<?php

namespace App\Support\GlobalSearch;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
class GlobalSearchResult implements Arrayable
{
    protected string $type;
    protected mixed $id;
    protected string $name;
    protected string $route;
    protected Carbon $created_at;
    protected Carbon $updated_at;

    public function __construct(string $model, string $type, mixed $id, string $name, string $route, Carbon $created_at, Carbon $updated_at)
    {
        $this->model = $model;
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->route = $route;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function toArray()
    {
        return [
            'model' => $this->model,
            'type' => $this->type,
            'id' => $this->id,
            'name' => $this->name,
            'route' => $this->route,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
