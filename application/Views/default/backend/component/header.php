<header class="bg-gray-800 text-white py-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">PhpFast Admin</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="<?= admin_url('dashboard') ?>" class="hover:text-gray-400">Dashboard</a></li>
                <li><a href="<?= admin_url('users') ?>" class="hover:text-gray-400">Users</a></li>
                <li><a href="<?= admin_url('posts') ?>" class="hover:text-gray-400">Posts</a></li>
                <li><a href="<?= admin_url('auth/logout') ?>" class="hover:text-gray-400">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>