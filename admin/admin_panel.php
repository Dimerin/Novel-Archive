<?php
session_start();
include '../db/db.php';

// Admin privilege check

if ($_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);

    if ($stmt->execute()) {
        echo "User privilege updated.";
    } else {
        echo "Error updating privilege.";
    }
}

$users = $conn->query("SELECT id, username, role FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Admin Panel</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Change Role</th>
        </tr>
        <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                    <select name="new_role">
                        <option value="non-premium" <?php if ($row['role'] == 'non-premium') echo 'selected'; ?>>Non-Premium</option>
                        <option value="premium" <?php if ($row['role'] == 'premium') echo 'selected'; ?>>Premium</option>
                    </select>
                    <button type="submit">Change</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
