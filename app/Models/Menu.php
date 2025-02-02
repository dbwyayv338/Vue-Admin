<?php

namespace App\Models;

use App\Http\Exceptions\MachineNameInvalidArgument;
use App\Http\Exceptions\MenuAlreadyExists;
use App\Http\Exceptions\MenuNotExists;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function getTable()
    {
        return config('menu.table_names.menus', parent::getTable());
    }

    public static function create(array $attributes = [])
    {
        if (!static::validateMachineName($attributes['machine_name'])) {
            throw MachineNameInvalidArgument::create();
        }

        $menu = Menu::where('machine_name', $attributes['machine_name'])->first();

        if ($menu) {
            throw MenuAlreadyExists::create($attributes['machine_name']);
        }

        return static::query()->create($attributes);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(config('menu.models.menu_item'));
    }

    public static function validateMachineName($machine_name) {
        return preg_match('/^[a-z0-9_-]+$/', $machine_name);
    }

    protected static function getMenuTree($machine_name) {
        $menu = Menu::where('machine_name', $machine_name)->first();
        if(!$menu){
            throw MenuNotExists::create($machine_name);
        }
        return (new MenuItem)->toTree($menu->id);
    }
}
