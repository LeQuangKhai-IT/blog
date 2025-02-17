<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permission
        Permission::create(['name' => 'retrieve all post']);
        Permission::create(['name' => 'retrieve a post']);
        Permission::create(['name' => 'create a post']);
        Permission::create(['name' => 'update a post']);
        Permission::create(['name' => 'delete a post']);
        Permission::create(['name' => 'retrieve popular posts']);
        Permission::create(['name' => 'like a post']);
        Permission::create(['name' => 'unlike a post']);
        Permission::create(['name' => 'retrieve related post']);
        Permission::create(['name' => 'retrieve post by slug']);
        Permission::create(['name' => 'share a post']);
        Permission::create(['name' => 'search post']);

        Permission::create(['name' => 'retrieve categories']);
        Permission::create(['name' => 'retrieve a category']);
        Permission::create(['name' => 'create a category']);
        Permission::create(['name' => 'update a category']);
        Permission::create(['name' => 'delete a category']);
        Permission::create(['name' => 'retrieve popular category']);

        Permission::create(['name' => 'retrieve tags']);
        Permission::create(['name' => 'retrieve a tag']);
        Permission::create(['name' => 'create a tag']);
        Permission::create(['name' => 'update a tag']);
        Permission::create(['name' => 'delete a tag']);

        Permission::create(['name' => 'retrieve comments']);
        Permission::create(['name' => 'create a comment ']);
        Permission::create(['name' => 'update a comment']);
        Permission::create(['name' => 'delete a comment']);
        Permission::create(['name' => 'like a comment']);
        Permission::create(['name' => 'reply to a comment']);

        Permission::create(['name' => 'retrieve users']);
        Permission::create(['name' => 'retrieve a user']);
        Permission::create(['name' => 'create a user']);
        Permission::create(['name' => 'update a user']);
        Permission::create(['name' => 'delete a user']);
        Permission::create(['name' => 'retrieve user posts']);
        Permission::create(['name' => 'retrieve user comments']);
        Permission::create(['name' => 'follow a user']);
        Permission::create(['name' => 'unfollow a user']);


        Permission::create(['name' => 'delete notifications']);


        // Tạo vai trò và gán quyền cho vai trò
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $authorRole = Role::create(['name' => 'author']);
        $contributorRole = Role::create(['name' => 'contributor']);
        $subscriberRole = Role::create(['name' => 'subscriber']);

        Role::create(['name' => 'admin', 'describe' => 'Toàn quyền truy cập và quản lý blog.']);
        Role::create(['name' => 'editor', 'describe' => 'Toàn quyền với tất cả các bài viết trên blog.']);
        Role::create(['name' => 'author', 'describe' => 'Toàn quyền với tất cả các bài viết của chính mình trên blog.']);
        Role::create(['name' => 'contributor', 'describe' => 'Không được xoá hay tự xuất bản các bài viết của mình trên blog.']);
        Role::create(['name' => 'subscriber', 'describe' => ' Truy cập và đọc nội dung trên blog, có thể nhận thông báo và bình luận (nếu được phép).']);
    }
}
