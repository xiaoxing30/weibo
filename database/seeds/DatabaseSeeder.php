<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //在 DatabaseSeeder 中调用 call 方法来指定我们要运行假数据填充的文件。
        Model::unguard();

        $this->call(UsersTableSeeder::class);
        $this->call(StatusesTableSeeder::class);

        Model::reguard();
    }

    /*
    在运行生成假数据的命令之前，需要使用 migrate:refresh 命令来重置数据库，之后再使用 db:seed 执行数据填充。
    $ php artisan migrate:refresh
    $ php artisan db:seed
    如果要单独指定执行 UserTableSeeder 数据库填充文件，则可以这么做：
    $ php artisan migrate:refresh
    $ php artisan db:seed --class=UsersTableSeeder
    也可以使用下面一条命令来同时完成数据库的重置和填充操作：
    $ php artisan migrate:refresh --seed
    */
}
