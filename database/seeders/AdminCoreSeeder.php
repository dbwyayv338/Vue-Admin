<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use App\Models\CategoryType;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminCoreSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'permission list',
            'permission create',
            'permission edit',
            'permission delete',
            'role list',
            'role create',
            'role edit',
            'role delete',
            'user list',
            'user create',
            'user edit',
            'user delete',
            'menu list',
            'menu create',
            'menu edit',
            'menu delete',
            'menu.item list',
            'menu.item create',
            'menu.item edit',
            'menu.item delete',
            'category.type list',
            'category.type create',
            'category.type edit',
            'category.type delete',
            'category.item list',
            'category.item create',
            'category.item edit',
            'category.item delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $role1 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        $role2 = Role::create(['name' => 'admin']);
        foreach ($permissions as $permission) {
            $role2->givePermissionTo($permission);
        }

        // create roles and assign existing permissions
        $role3 = Role::create(['name' => 'user']);

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole($role3);

        // create menu
        $menu = Menu::create([
            'name' => 'Admin',
            'machine_name' => 'admin',
            'description' => 'Admin Menu',
        ]);

        $item = $menu->menuItems()->create([
            'name' => 'Dashboard',
            'uri' => '/<admin>/dashboard',
            'enabled' => 1,
            'parent_id' => 0,
            'weight' => 0,
        ]);
        $item->assignRole([$role2->id, $role3->id]);

        //系统菜单
        $system_menu = $menu->menuItems()->create([
            'name' => 'System',
            'uri' => '<nolink>',
            'enabled' => 1,
            'parent_id' => 0,
            'weight' => 0,
        ]);
        $system_menu->assignRole($role2);

        $menu_system_items = [
            [
                'name' => 'Users',
                'uri' => '/<admin>/user',
                'enabled' => 1,
                'parent_id' => $system_menu->id,
                'weight' => 1,
            ],
            [
                'name' => 'Roles',
                'uri' => '/<admin>/role',
                'enabled' => 1,
                'parent_id' => $system_menu->id,
                'weight' => 2,
            ],
            [
                'name' => 'Menus',
                'uri' => '/<admin>/menu',
                'enabled' => 1,
                'parent_id' => $system_menu->id,
                'weight' => 3,
            ],
            [
                'name' => 'Categories',
                'uri' => '/<admin>/category',
                'enabled' => 1,
                'parent_id' => $system_menu->id,
                'weight' => 4,
            ],
            [
                'name' => 'Permissions',
                'uri' => '/<admin>/permission',
                'enabled' => 1,
                'parent_id' => $system_menu->id,
                'weight' => 5,
            ],
            [
                'name' => 'Template',
                'uri' => '/<admin>/template',
                'enabled' => 1,
                'parent_id' => $system_menu->id,
                'weight' => 99,
            ]
        ];

        $menu->menuItems()->createMany($menu_system_items);
        MenuItem::query()->where('parent_id', $system_menu->id)->get()->map(function (MenuItem $item) use ($role2) {
            $item->assignRole($role2);
        });

        // create category type
        CategoryType::create([
            'name' => 'Category',
            'machine_name' => 'category',
            'description' => 'Main Category',
        ]);

        CategoryType::create([
            'name' => 'Tag',
            'machine_name' => 'tag',
            'description' => 'Site Tags',
            'is_flat' => true,
        ]);
    }
}
