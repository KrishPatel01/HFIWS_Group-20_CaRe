<?php
session_start();
$page_title = 'Add Goal';
include('layouts/header.php');
include('../common/config/database.php');


if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_SESSION['user_id'];
    $goal_title = $_POST['goal_title'];
    $goal_description = $_POST['goal_description'];
    $target_date = $_POST['target_date'];
    $status = 'pending';  

    $sql = "INSERT INTO goals (patient_id, goal_title, goal_text, target_date, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("issss", $patient_id, $goal_title, $goal_description, $target_date, $status);

    if ($stmt->execute()) {
        // Redirect to the view goals page after successful insertion
        header("Location: view_goals.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>


<div class="container">
    <h1>Add New Goal</h1>

    <form action="" method="POST">
        <label for="goal_title">Goal Title</label>
        <input type="text" id="goal_title" name="goal_title" placeholder="Enter your goal title..." required>

        <label for="goal_description">Goal Description</label>
        <textarea id="goal_description" name="goal_description" placeholder="Describe your goal..." required></textarea>

        <label for="target_date">Target Date</label>
        <input type="date" id="target_date" name="target_date" required>

        <button type="submit" class="btn">Add Goal</button> <!-- Replaced anchor tag with a button -->
    </form>
</div>

<?php
include('layouts/footer.php');
?>
