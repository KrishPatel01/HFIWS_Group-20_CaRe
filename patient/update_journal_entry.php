<?php
session_start();
$page_title = 'Edit Journal Entry';
include('layouts/header.php');
include('../common/config/database.php');


if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $journal_id = $_GET['id'];

    if (isset($_GET['type']) && $_GET['type'] === "delete") {
        $delete_sql = "DELETE FROM journal_entries WHERE id = ? AND patient_id = ?";
        $delete_stmt = $connection->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $journal_id, $patient_id);
        $delete_stmt->execute();
        header("Location: view_journal.php");
    }

    $sql = "SELECT * FROM journal_entries WHERE id = ? AND patient_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $journal_id, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $journal_entry = $result->fetch_assoc();

    if (!$journal_entry) {
        echo "<p>No journal entry found!</p>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emotion = $_POST['emotion'];
    $state_of_mind = $_POST['state_of_mind'];
    $sleep_last_night = $_POST['sleep_last_night'];
    $eating_habits = $_POST['eating_habits'];
    $activity_type = $_POST['activity_type'];
    $daily_affirmation = $_POST['selected_affirmation'];
    $date_time = date('Y-m-d H:i:s');

    $update_sql = "UPDATE journal_entries SET emotion = ?, state_of_mind = ?, sleep_last_night = ?, eating_habits = ?, activity_type = ?, daily_affirmation = ?, date_time = NOW() WHERE id = ? AND patient_id = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("ssisssii", $emotion, $state_of_mind, $sleep_last_night, $eating_habits, $activity_type, $daily_affirmation, $journal_id, $patient_id);

    if ($update_stmt->execute()) {
        echo "<p>Journal entry updated successfully!</p>";
        header("Location: view_journal.php");
        exit();
    } else {
        echo "<p>Error updating journal entry: " . $update_stmt->error . "</p>";
    }
}
?>

<div class="container">
    <h1>Daily Journal Entry</h1>

    <form method="POST" action="">
        <label for="emotion">How are you feeling today? (Emotion)</label>
        <select id="emotion" name="emotion" required>
            <option value="">Select your emotion</option>
            <option value="Happy" <?php echo ($journal_entry['emotion'] == 'Happy') ? 'selected' : ''; ?>>Happy</option>
            <option value="Sad" <?php echo ($journal_entry['emotion'] == 'Sad') ? 'selected' : ''; ?>>Sad</option>
            <option value="Anxious" <?php echo ($journal_entry['emotion'] == 'Anxious') ? 'selected' : ''; ?>>Anxious</option>
            <option value="Excited" <?php echo ($journal_entry['emotion'] == 'Excited') ? 'selected' : ''; ?>>Excited</option>
            <option value="Angry" <?php echo ($journal_entry['emotion'] == 'Angry') ? 'selected' : ''; ?>>Angry</option>
            <option value="Calm" <?php echo ($journal_entry['emotion'] == 'Calm') ? 'selected' : ''; ?>>Calm</option>
        </select>

        <label for="state_of_mind">State of Mind</label>
        <textarea id="state_of_mind" name="state_of_mind" placeholder="Describe your state of mind..." required><?php echo htmlspecialchars($journal_entry['state_of_mind']); ?></textarea>

        <label for="sleep_hours">Hours of Sleep Last Night</label>
        <input type="number" id="sleep_hours" name="sleep_last_night" min="0" max="24" value="<?php echo htmlspecialchars($journal_entry['sleep_last_night']); ?>" required>

        <label for="eating_habits">Eating Habits Today</label>
        <textarea id="eating_habits" name="eating_habits" placeholder="Describe your eating habits today..." required><?php echo htmlspecialchars($journal_entry['eating_habits']); ?></textarea>

        <label for="exercise">Exercise Today (Describe)</label>
        <textarea id="exercise" name="exercise" placeholder="Describe any physical exercise today..." required><?php echo htmlspecialchars($journal_entry['activity_type']); ?></textarea>

        <div class="affirmations">
            <label>Select a Daily Affirmation</label>
            <div class="affirmation-options">
                <button type="button" onclick="selectAffirmation(this)" value="I am strong and capable" <?php echo ($journal_entry['daily_affirmation'] == 'I am strong and capable') ? 'class="selected"' : ''; ?>>I am strong and capable</button>
                <button type="button" onclick="selectAffirmation(this)" value="Today is a new beginning" <?php echo ($journal_entry['daily_affirmation'] == 'Today is a new beginning') ? 'class="selected"' : ''; ?>>Today is a new beginning</button>
                <button type="button" onclick="selectAffirmation(this)" value="I choose to be happy" <?php echo ($journal_entry['daily_affirmation'] == 'I choose to be happy') ? 'class="selected"' : ''; ?>>I choose to be happy</button>
                <button type="button" onclick="selectAffirmation(this)" value="I believe in myself" <?php echo ($journal_entry['daily_affirmation'] == 'I believe in myself') ? 'class="selected"' : ''; ?>>I believe in myself</button>
                <button type="button" onclick="selectAffirmation(this)" value="I am grateful for today" <?php echo ($journal_entry['daily_affirmation'] == 'I am grateful for today') ? 'class="selected"' : ''; ?>>I am grateful for today</button>
            </div>
        </div>

        <input type="hidden" id="selected_affirmation" name="selected_affirmation" value="<?php echo htmlspecialchars($journal_entry['daily_affirmation']); ?>">

        <div class="center">
            <button type="submit" class="edit-btn">Update</button>
            <a href="update_journal_entry.php?type=delete&id=<?php echo $journal_id ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to delete this journal entry?');">Delete</a>
        </div>
    </form>
</div>

<script>
    function selectAffirmation(button) {
        const buttons = document.querySelectorAll('.affirmation-options button');
        buttons.forEach(btn => btn.classList.remove('selected'));

        button.classList.add('selected');
        document.getElementById('selected_affirmation').value = button.value;
    }
</script>

<?php
include('layouts/footer.php');
?>