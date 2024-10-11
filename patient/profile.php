<?php
session_start();
$page_title = 'Profile';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM patient WHERE id = $user_id";
$result = $connection->query($sql);
$user = $result->fetch_assoc();
?>

<div class="container">
    <h1>Patient Profile</h1>
    <div class="profile-section">
    <h2>Profile Picture</h2>
    <?php
    if (!empty($user['profile_image'])) {
        echo '<img src="' . BASE_URL . htmlspecialchars($user['profile_image']) . '" alt="Profile Image" style="max-width: 150px; height: auto;">';
    } else {
        echo '<p>No profile image uploaded.</p>';
    }?>
</div>
    <div class="profile-section">
        <h2>Full Name</h2>
        <p><span><?= htmlspecialchars($user['name']) ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Email</h2>
        <p><span><?= htmlspecialchars($user['email']) ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Date of Birth</h2>
        <p><span><?= htmlspecialchars($user['dob']) ?></span></p>
    </div>
    
    <div class="profile-section">
        <h2>Age</h2>
        <p><span><?= htmlspecialchars($user['age']) ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Gender</h2>
        <p><span><?= htmlspecialchars($user['gender']) ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Height</h2>
        <p><span><?= htmlspecialchars($user['height']) ?> cm</span></p>
    </div>

    <div class="profile-section">
        <h2>Weight</h2>
        <p><span><?= htmlspecialchars($user['weight']) ?> kg</span></p>
    </div>

    <div class="profile-section">
        <h2>Phone Number</h2>
        <p><span><?= htmlspecialchars($user['phone']) ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Address</h2>
        <p><span><?= htmlspecialchars($user['address']) ?></span></p>
    </div>

    <div class="profile-section">
        <h2>Emergency Contact</h2>
        <p><span><?= htmlspecialchars($user['emergency_phone_number']) ?></span></p>
    </div>

    <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
</div>

<?php
include('layouts/footer.php');
?>