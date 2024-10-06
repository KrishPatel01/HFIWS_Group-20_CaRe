<?php
session_start();
$page_title = 'Update Goal';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];  // Get the patient ID from the session

if (isset($_GET['id'])) {
    $goal_id = $_GET['id'];

    if (isset($_GET['type']) && $_GET['type'] === 'delete') {
        $delete_sql = "DELETE FROM goals WHERE id = ? AND patient_id = ?";
        $delete_stmt = $connection->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $goal_id, $patient_id);

    $delete_stmt->execute();
            header("Location: view_goals.php");
    }

    // Fetch the goal to edit
    $sql = "SELECT * FROM goals WHERE id = ? AND patient_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $goal_id, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $goal = $result->fetch_assoc();

    if (!$goal) {
        echo "<p>No goal found!</p>";
        exit();
    }
}

// Update the goal if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $goal_title = $_POST['goal_title'];
    $goal_description = $_POST['goal_description'];
    $target_date = $_POST['target_date'];

    // Update goal in the database
    $update_sql = "UPDATE goals SET goal_title=?, goal_text = ?, target_date = ?, created_at=NOW() WHERE id = ? AND patient_id = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("sssii", $goal_title, $goal_description, $target_date, $goal_id, $patient_id);

    if ($update_stmt->execute()) {
        // Redirect to view goals after successful update
        header("Location: view_goals.php");
        exit();
    } else {
        echo "<p>Error updating goal: " . $update_stmt->error . "</p>";
    }
}
?>

<div class="container">
    <h1>Update Goal</h1>

    <form method="POST" action="">
        <label for="goal_title">Goal Title</label>
        <input type="text" id="goal_title" name="goal_title" placeholder="Enter your goal title..." value="<?php echo htmlspecialchars($goal['goal_title']); ?>" required>

        <label for="goal_description">Goal Description</label>
        <textarea id="goal_description" name="goal_description" placeholder="Describe your goal..." required><?php echo htmlspecialchars($goal['goal_text']); ?></textarea>

        <label for="target_date">Target Date</label>
        <input type="date" id="target_date" name="target_date" value="<?php echo htmlspecialchars($goal['target_date']); ?>" required>

        <button type="submit" class="edit-btn">Update Goal</button>
        <a href="update_goal.php?type=delete&id=<?php echo $goal_id; ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to delete this goal?');">Delete Goal</a>
    </form>
</div>

<?php
include('layouts/footer.php');
?>
