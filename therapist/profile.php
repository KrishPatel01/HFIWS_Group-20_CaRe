<?php
$page_title = 'Profile';
include('layouts/header.php');

if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];
} else {

    header("Location: dashboard.php");
    exit;
}

include('../common/config/database.php');

$sql = "SELECT name, email, dob, gender, height, weight, phone, address, emergency_phone_number FROM patient WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $patient = $result->fetch_assoc();
} else {
    echo "Patient not found!";
    exit;
}

$note_sql = "SELECT id FROM therapist_notes WHERE patient_id = ?";
$note_stmt = $connection->prepare($note_sql);
$note_stmt->bind_param("i", $patient_id);
$note_stmt->execute();
$note_result = $note_stmt->get_result();
?>
<div class="pro-container">
    <h1><?php echo $patient['name']; ?>'s Profile</h1>

    <div class="profile-section">
        <h2>Email</h2>
        <p><span><?php echo $patient['email']; ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Date of Birth</h2>
        <p><span><?php echo $patient['dob']; ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Gender</h2>
        <p><span><?php echo $patient['gender']; ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Height</h2>
        <p><span><?php echo $patient['height']; ?> cm</span></p>
    </div>

    <div class="profile-section">
        <h2>Weight</h2>
        <p><span><?php echo $patient['weight']; ?> kg</span></p>
    </div>

    <div class="profile-section">
        <h2>Phone Number</h2>
        <p><span><?php echo $patient['phone']; ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Address</h2>
        <p><span><?php echo $patient['address']; ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Emergency Contact</h2>
        <p><span><?php echo $patient['emergency_phone_number']; ?></span></p>
    </div>
    <a href="dashboard.php" class="btn">Home</a>
    <br>
    <?php

    if ($note_result->num_rows > 0) {
        $note = $note_result->fetch_assoc();
    $note_id = $note['id'];

        echo '<a href="update_note.php?id=' . $note_id . '" class="edit-btn">Edit Note</a>';
        echo '<a href="delete_note.php?id=' . $note_id . '" class="cancel-btn">Delete Note</a>';
    } else {
        echo '<a href="add_note.php?id=' . $patient_id . '" class="btn">Add Note</a>';
    }
    ?>
</div>

<?php
include('layouts/footer.php');
?>