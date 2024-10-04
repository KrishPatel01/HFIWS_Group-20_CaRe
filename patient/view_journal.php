<?php
session_start();
$page_title = 'View Journal';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  

$sql = "SELECT id, date_time, emotion, state_of_mind, sleep_last_night, activity_type, eating_habits, daily_affirmation FROM journal_entries WHERE patient_id = ? ORDER BY date_time DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <div class="flex-header">
        <h2>Your Journal Entries</h2>
        <a href="add_journal_entry.php" class="add-btn">Add New Journal Entry</a>
    </div>
    <ul class="journal-list">
        <?php while ($entry = $result->fetch_assoc()): ?>
            <li>
                <div class="flex-header">
                    <h3>Date: <?php echo htmlspecialchars($entry['date_time']); ?></h3>
                    <a href="update_journal_entry.php?id=<?php echo $entry['id']; ?>" class="edit-btn">Edit</a>
                </div>
                <p><strong>Emotion:</strong> <?php echo htmlspecialchars($entry['emotion']); ?></p>
                <p><strong>State of Mind:</strong> <?php echo htmlspecialchars($entry['state_of_mind']); ?></p>
                <p><strong>Sleep last night:</strong> <?php echo htmlspecialchars($entry['sleep_last_night']); ?> hours</p>
                <p><strong>Activity type:</strong> <?php echo htmlspecialchars($entry['activity_type']); ?></p>
                <p><strong>Eating habits:</strong> <?php echo htmlspecialchars($entry['eating_habits']); ?></p>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php
include('layouts/footer.php');
?>
