<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin', 'describe' => 'Toàn quyền truy cập và quản lý blog.']);
        Role::create(['name' => 'editor', 'describe' => 'Toàn quyền với tất cả các bài viết trên blog.']);
        Role::create(['name' => 'author', 'describe' => 'Toàn quyền với tất cả các bài viết của chính mình trên blog.']);
        Role::create(['name' => 'contributor', 'describe' => 'Không được xoá hay tự xuất bản các bài viết của mình trên blog.']);
        Role::create(['name' => 'subscriber', 'describe' => ' Truy cập và đọc nội dung trên blog, có thể nhận thông báo và bình luận (nếu được phép).']);
    }
}
