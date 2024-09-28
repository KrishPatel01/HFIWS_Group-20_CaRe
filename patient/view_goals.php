<?php
session_start();
$page_title = 'View Goals';
include('layouts/header.php');
include('../common/config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];  

$sql = "SELECT id, goal_title, goal_text, target_date, status FROM goals WHERE patient_id = ? ORDER BY target_date DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <div class="flex-header">
        <h2>Your Goals</h2>
        <a href="add_goal.php" class="add-btn">Add Goal</a>
    </div>
    <ul class="goal-list">
        <?php while ($goal = $result->fetch_assoc()): ?>
            <li>
                <div class="flex-header">
                    <h3><?php echo htmlspecialchars($goal['goal_title']); ?></h3>
                    <a href="update_goal.php?id=<?php echo $goal['id']; ?>" class="edit-btn">Edit</a>
                </div>
                <p><strong>Goal Description:</strong> <?php echo htmlspecialchars($goal['goal_text']); ?></p>
                <p><strong>Status:</strong> <?php echo ($goal['status'] == 'completed') ? 'Completed' : 'Pending'; ?></p>
                <p><strong>Target Date:</strong> <strong><?php echo htmlspecialchars($goal['target_date']); ?></strong></p>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php
include('layouts/footer.php');
?>
