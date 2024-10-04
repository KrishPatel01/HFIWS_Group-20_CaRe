<?php
session_start();
$page_title = 'Patient Dashboard';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Assuming patient ID is stored in session
$patient_id = $_SESSION['user_id'];

// Get the current page (week), default to 1 (current week)
$current_week = isset($_GET['week']) ? (int)$_GET['week'] : 1;

// Calculate the starting and ending date for the week
// 'week 1' means current week, 'week 2' means last week, etc.
$start_date = date('Y-m-d', strtotime("-" . (($current_week - 1) * 7) . " days"));
$end_date = date('Y-m-d', strtotime("-" . (($current_week - 1) * 7 + 7) . " days"));

// Fetch the journal entries for the patient for the selected week (last 7 days)
$entry_query = "
    SELECT * FROM journal_entries 
    WHERE patient_id = ? 
    AND date_time BETWEEN ? AND ? 
    ORDER BY date_time DESC";
$entry_stmt = $connection->prepare($entry_query);
$entry_stmt->bind_param("iss", $patient_id, $end_date, $start_date);
$entry_stmt->execute();
$entries_result = $entry_stmt->get_result();

// Fetch daily affirmation (optional, from the most recent entry)
$affirmation_query = "SELECT daily_affirmation FROM journal_entries WHERE patient_id = ? ORDER BY date_time DESC LIMIT 1";
$affirmation_stmt = $connection->prepare($affirmation_query);
$affirmation_stmt->bind_param("i", $patient_id);
$affirmation_stmt->execute();
$affirmation_result = $affirmation_stmt->get_result();
$affirmation = $affirmation_result->fetch_assoc()['daily_affirmation'];

// Fetch goals for the patient (No date limitation, just all current goals)
$goal_query = "
    SELECT * FROM goals 
    WHERE patient_id = ? 
    AND created_at BETWEEN ? AND ? 
    ORDER BY created_at DESC";
$goal_stmt = $connection->prepare($goal_query);
$goal_stmt->bind_param("iss", $patient_id, $end_date, $start_date);
$goal_stmt->execute();
$goals_result = $goal_stmt->get_result();
?>

<div class="container">
    <h1>Welcome <a href="profile.php" class="profile-btn"><?php echo $_SESSION['user_name']; ?>!</a></h1>

    <div class="affirmations dashboard-section">
        <h3>Daily Affirmation:</h3>
        <p>"<?php echo $affirmation ? $affirmation : 'Stay positive today!'; ?>"</p>
    </div>

    <div class="dashboard-section">
        <div class="flex-header">
            <h2>Your Activities from <?php echo date('F j, Y', strtotime($start_date)); ?> to <?php echo date('F j, Y', strtotime($end_date)); ?></h2>
        </div>

        <ul class="activity-list">
            <?php
            if ($entries_result->num_rows > 0) {
                while ($entry = $entries_result->fetch_assoc()) { ?>
                    <li class="activity-item">
                        <h4><?php echo date('F j, Y', strtotime($entry['date_time'])); ?></h4>
                        <h3>Sleeping Cycle</h3>
                        <p>You slept an average of <?php echo $entry['sleep_last_night']; ?> hours last night.</p>
                        <hr>
                        <h3>Eating Habits</h3>
                        <p><?php echo $entry['eating_habits']; ?></p>
                        <hr>
                        <h3>Exercise</h3>
                        <p><?php echo $entry['activity_type']; ?></p>
                        <hr>
                    </li>
            <?php
                }
            } else {
                echo "<p>No activity data available for this week.</p>";
            }
            ?>
        </ul>
    </div>

    <div class="dashboard-section">
        <h2>Your Goals for the Week <?php echo date('F j, Y', strtotime($start_date)); ?> to <?php echo date('F j, Y', strtotime($end_date)); ?></h2>
        <ul class="goal-list">
            <?php
            if ($goals_result->num_rows > 0) {
                while ($goal = $goals_result->fetch_assoc()) { ?>
                    <li class="goal-item">
                        <div class="flex-header">
                            <h3><?php echo $goal['goal_text']; ?></h3>
                        </div>
                        <p class="<?php echo $goal['status'] == 'completed' ? 'completed' : 'pending'; ?>">
                            Goal Status: <?php echo ucfirst($goal['status']); ?>
                        </p>
                        <small>Target Date: <?php echo date('F j, Y', strtotime($goal['target_date'])); ?></small>
                        <hr> <!-- Separation between goals -->
                    </li>
            <?php
                }
            } else {
                echo "<p>No goals set for this week.</p>";
            }
            ?>
        </ul>

        <!-- Unified Weekly Pagination for Goals and Activities -->
        <div class="pagination">
            <a href="?week=<?php echo $current_week + 1; ?>" class="btn">Previous Week</a>
            <?php if ($current_week > 1) { ?>
                <a href="?week=<?php echo $current_week - 1; ?>" class="btn">Next Week</a>
            <?php } ?>
        </div>
    </div>
</div>

<?php
include('layouts/footer.php');
?>