
<?php
include '../includes/db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Delete Product Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: manage_products.php"); // Refresh the page
    exit();
}

// Handle Edit Product Request
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $price, $description, $id]);

    header("Location: manage_products.php"); // Refresh the page
    exit();
}

// Fetch all products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  Manage Products  </title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color:rgb(192, 104, 104); }
        a, button { padding: 5px 10px; margin: 5px; border-radius: 5px; cursor: pointer; }
        .edit { background-color:rgb(7, 255, 102); color: black; border: none; }
        .delete { background-color:rgb(44, 188, 231); color: white; border: none; }
    </style>
</head>
<body>

    <h2>Manage Products</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id']; ?></td>
            <td><?= $product['name']; ?></td>
            <td>$<?= $product['price']; ?></td>
            <td><?= $product['description']; ?></td>
            <td>
                <button class="edit" onclick="editProduct(<?= $product['id']; ?>, '<?= $product['name']; ?>', <?= $product['price']; ?>, '<?= $product['description']; ?>')">Edit</button>
                <br>
                <br>

                <a href="manage_products.php?delete_id=<?= $product['id']; ?>" class="delete" onclick="return confirm('Are you sure?');">Delete</a>
                <br>
                <br>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <br>
    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    <br>
    <br>
    
    <!-- Edit Product Modal -->
    <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ddd; box-shadow:0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2>Edit Product</h2>
        <form method="POST">
            <input type="hidden" name="id" id="edit_id">
            <label>Name:</label>
            <input type="text" name="name" id="edit_name" required><br>

            <label>Price:</label>
            <input type="text" name="price" id="edit_price" required><br>

            <label>Description:</label>
            <textarea name="description" id="edit_description" required></textarea><br>

            <button type="submit" name="update">Update Product</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>

    <script>
        function editProduct(id, name, price, description) {
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_name").value = name;
            document.getElementById("edit_price").value = price;
            document.getElementById("edit_description").value = description;
            document.getElementById("editModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("editModal").style.display = "none";
        }
    </script>

</body>
</html>
