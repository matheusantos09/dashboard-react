<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private $permissionsArray = [
        'user',
        'taskWeek-list',
        'taskUser-list',
        'taskOverdue-list',
        'company-list',
        'project-list',
        'ftp-list',
        'site-index',
        'site-create',
        'site-edit',
        'site-destroy',
        'site-access',
        'ftp-create',
        'rh',
        'analytics-list',
        'analytics-edit',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermission();
//        $this->removePermission();
    }

    private function createPermission()
    {

        foreach ($this->permissionsArray as $permission) {

            Permission::firstOrCreate([
                'name' => $permission
            ]);

        }

    }

    private function removePermission()
    {

        foreach ($this->permissionsArray as $permission) {

            $permission = Permission::where('name', $permission)->first();

            if ($permission) {
                $permission->delete();
            }

        }

    }
}