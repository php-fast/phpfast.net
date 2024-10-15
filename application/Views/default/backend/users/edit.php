<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <?php if (isset($error)): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="/users/edit/<?php echo $user['id']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="moderator" <?php echo $user['role'] == 'moderator' ? 'selected' : ''; ?>>Moderator</option>
            <option value="author" <?php echo $user['role'] == 'author' ? 'selected' : ''; ?>>Author</option>
            <option value="member" <?php echo $user['role'] == 'member' ? 'selected' : ''; ?>>Member</option>
        </select><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            <option value="banned" <?php echo $user['status'] == 'banned' ? 'selected' : ''; ?>>Banned</option>
        </select><br><br>

        <button type="submit">Update User</button>
    </form>
</body>
</html>
