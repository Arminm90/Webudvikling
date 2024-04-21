<?php
    session_start();  // Start the session

    require_once __DIR__ . '/_header.php';
    require_once __DIR__ . '/../_.php';

    // Check if user is logged in, if not redirect to login page
    if (!isset($_SESSION['user_id'])) {
        header('Location: /views/index.php');
        exit();
    }

    $db = _db();
    $userId = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $q = $db->prepare($sql);
    $q->bindParam(':user_id', $userId);
    $q->execute();
    $user = $q->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found!";
        exit;
    }
?>
<link rel="stylesheet" href="/css/user.css">
<div id="userProfile">
<h4>User Profile</h4>
<p><strong>Name:</strong> <?php echo $user['user_name']; ?></p>
<p><strong>Last Name: </strong> <?php echo $user['user_last_name']; ?></p>
<p><strong>Email: </strong> <?php echo $user['user_email']; ?></p>
<p><strong>Address:</strong> <?php echo $user['user_address']; ?></p>
<p><strong>Username:</strong> <?php echo $user['user_username']; ?></p>
<p><strong>Role:</strong> <?php echo $user['user_role']; ?></p>

<div id="button">
    <div id="update">
        <button id="update-btn" onclick="window.location.href='/views/update.php'">Update Profile</button>
    </div>
    <div id="delete">
        <button id="delete-btn" onclick="confirmDelete()">Delete Profile</button>
    </div>
    <div id="logout">
        <button id="logout-btn" onclick="window.location.href='/views/logout.php'">Logout</button>
    </div>
</div>
<?php
$userId = $_SESSION['user_id'];


$sql = "SELECT * FROM users WHERE user_id = :user_id";
$q = $db->prepare($sql);
$q->bindParam(':user_id', $userId);
$q->execute();
$user = $q->fetch(PDO::FETCH_ASSOC);


if (!$user) {
    echo "User not found!";
    exit;
}

$sql = "SELECT 
            users.user_id, 
            users.user_name, 
            users.user_last_name, 
            users.user_username, 
            users.user_email, 
            users.user_address, 
            orders.order_id, 
            orders.order_user_fk, 
            orders.order_product_fk, 
            orders.order_amount_paid, 
            orders.order_status
        FROM 
            users 
        INNER JOIN 
            orders ON users.user_id = orders.order_user_fk
        WHERE 
            users.user_id = :user_id
        ORDER BY 
            orders.order_id ASC";

$q = $db->prepare($sql);
$q->bindParam(':user_id', $userId);
$q->execute();
$orders = $q->fetchAll(PDO::FETCH_ASSOC);
?>
<section id="orders">
        
        <form data-url="<?= $frm_search_url ?>" id="frm_search" action="/search-results" method="GET">
        
        </form>

        <?php foreach ($orders as $order): ?>
            <div id="vieworders">
                <div>
                    <div><label for="order_id">Order ID:</label></div>
                    <?= $order['order_id']; ?>
                </div>

                <div>
                    <div><label for="order_user_fk">Order User FK:</label></div>
                    <?= $order['order_user_fk']; ?>
                </div>

                <div>
                    <div><label for="order_product_fk">Order Product FK:</label></div>
                    <?= $order['order_product_fk']; ?>
                </div>

                <div>
                    <div><label for="order_amount_paid">Order Amount Paid:</label></div>
                    <?= $order['order_amount_paid']; ?>
                </div>

                <div>
                    <div><label for="order_status">Order Status:</label></div>
                    <?= $order['order_status']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</div>
<?php
    require_once __DIR__ . '/_footer.php';
?>
