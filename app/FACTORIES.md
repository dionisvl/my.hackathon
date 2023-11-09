# Demo data creation by Tinker

### before

```
php artisan tinker
```

### Create user

```
\App\Models\User::add(['name' => 'admin', 'email' => 'admin@admin.net', 'is_admin' => 1, 'password' => bcrypt('admin')]);
```

### Fill posts

```
\App\Models\Post::add(['title' => 'My blog post #1', 'content' => 'content of my post #1']);
\App\Models\Post::add(['title' => 'My blog post #2', 'content' => 'content of my post #2']);
```
