<?php
session_start();
$page_title = 'Patient Journal Entry';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $patient_id = $_SESSION['user_id'];  // Assuming patient ID is stored in the session
    $emotion = $_POST['emotion'];
    $state_of_mind = $_POST['state_of_mind'];
    $sleep_last_night = $_POST['sleep_last_night'];
    $eating_habits = $_POST['eating_habits'];
    $activity_type = $_POST['activity_type'];
    $daily_affirmation = $_POST['daily_affirmation'];
   
    $sql = "INSERT INTO journal_entries (patient_id, emotion, state_of_mind, sleep_last_night, eating_habits, activity_type, daily_affirmation, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $connection->prepare($sql);
    if ($stmt === false) {
        // Output error if preparation fails
        echo "Error preparing the query: " . $connection->error;
        exit();
    }
    $stmt->bind_param("ississs", $patient_id, $emotion, $state_of_mind, $sleep_last_night, $eating_habits, $activity_type, $daily_affirmation);

    if ($stmt->execute()) {
        echo "<p>Journal entry added successfully!</p>";
        header("Location: view_journal.php");
        exit();
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>

<div class="container">
    <h1>Daily Journal Entry</h1>

    <form method="POST" action="">
        <label for="emotion">How are you feeling today? (Emotion)</label>
        <select id="emotion" name="emotion" required>
            <option value="">Select your emotion</option>
            <option value="happy">Happy</option>
            <option value="sad">Sad</option>
            <option value="anxious">Anxious</option>
            <option value="excited">Excited</option>
            <option value="angry">Angry</option>
            <option value="calm">Calm</option>
        </select>

        <label>State of Mind</label>
        <textarea id="state_of_mind" name="state_of_mind" placeholder="Describe your state of mind..." required></textarea>

        <label>Hours of Sleep Last Night</label>
        <input type="number" id="sleep_last_night" name="sleep_last_night" min="0" max="24" placeholder="Enter number of hours slept" required>

        <label>Eating Habits Today</label>
        <textarea id="eating_habits" name="eating_habits" placeholder="Describe your eating habits today..." required></textarea>

        <label>Activity type Today (Describe)</label>
        <textarea id="activity_type" name="activity_type" placeholder="Describe any physical activity today..." required></textarea>

        <div class="affirmations">
            <label>Select a Daily Affirmation</label>
            <div class="affirmation-options">
                <button type="button" onclick="selectAffirmation(this)" value="I am strong and capable">I am strong and capable</button>
                <button type="button" onclick="selectAffirmation(this)" value="Today is a new beginning">Today is a new beginning</button>
                <button type="button" onclick="selectAffirmation(this)" value="I choose to be happy">I choose to be happy</button>
                <button type="button" onclick="selectAffirmation(this)" value="I believe in myself">I believe in myself</button>
                <button type="button" onclick="selectAffirmation(this)" value="I am grateful for today">I am grateful for today</button>
            </div>
        </div>

        <input type="hidden" id="daily_affirmation" name="daily_affirmation" value="">

        <button type="submit" class="btn">Submit Journal Entry</button>
    </form>
</div>

<script>
    function selectAffirmation(button) {
        const buttons = document.querySelectorAll('.affirmation-options button');
        buttons.forEach(btn => btn.classList.remove('selected'));

        button.classList.add('selected');
        document.getElementById('daily_affirmation').value = button.value;
    }
</script>

<?php
include('layouts/footer.php');
?>
