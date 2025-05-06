<?php
namespace App\Models;
use App\Models\Model;

class ItemsModel extends Model
{
    private int $id_menu = 0;
    private int $id_parent = 0;
    private int $status = 0;
    private string $name = '';
    private string $description = '';

    public function getIdMenu(): int
    {
        return $this->id_menu;
    }

    public function setIdMenu(int $id_menu): void
    {
        $this->id_menu = $id_menu;
    }

    public function getIdParent(): int
    {
        return $this->id_parent;
    }

    public function setIdParent(int $id_parent): void
    {
        $this->id_parent = $id_parent;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
}
