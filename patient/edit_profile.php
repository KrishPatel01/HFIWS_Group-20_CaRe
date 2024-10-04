<?php
session_start();
$page_title = 'Edit Profile';
include('layouts/header.php');
include('../common/config/database.php');


if ($_SESSION['role'] != "patient") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone'];
    $emergency_phone_number = $_POST['emergency_phone_number'];

    $sql = "UPDATE patient SET name=?, email=?, dob=?, age=?, height=?, weight=?, gender=?, address=?, phone=?, emergency_phone_number=? WHERE id=?";

    $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssississsi", $name, $email, $dob, $age, $height, $weight, $gender, $address, $phone_number, $emergency_phone_number, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php"); 
        exit();
    } else {
        echo "Error updating profile: " . $connection->error;
        }
} else if (isset($_GET['type']) && $_GET['type'] === "delete") {
    $sql = "DELETE from patient where id=" . $_SESSION['user_id'];
    $result = $connection->query($sql);
    header("Location: dashboard.php");
}
if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM patient WHERE id = $user_id";
    $result = $connection->query($sql);
    $user = $result->fetch_assoc();
}
?>

<div class="container">
    <form method="POST" action="">
        <h3>Edit Profile</h3>
        <hr>

        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

        <div class="col-2">
            <div>
                <label>Name</label>
                <div class="input-group">
                    <input type="text" id="name" name="name" placeholder="Enter Name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
            </div>

            <div>
                <label>Email</label>
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <div>
                <label>Date of Birth</label>
                <div class="input-group">
                    <input type="date" id="dob" name="dob" value="<?php echo $user['dob']; ?>" required>
                </div>
            </div>

            <div>
                <label>Age</label>
                <div class="input-group">
                    <select name="age" id="age" required>
                        <option value="">Select Age</option>
                        <?php
                        for ($i = 15; $i <= 100; $i++) {
                            echo "<option value='$i' ".($user['age'] == $i ? 'selected' : '').">$i</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div>
                <label>Height</label>
                <div class="input-group">
                    <input type="text" id="height" name="height" placeholder="Enter Height" value="<?php echo htmlspecialchars($user['height']); ?>" required>
                </div>
            </div>

            <div>
                <label>Weight</label>
                <div class="input-group">
                    <input type="text" id="weight" name="weight" placeholder="Enter Weight" value="<?php echo htmlspecialchars($user['weight']); ?>" required>
                </div>
            </div>

            <div>
                <label>Gender</label>
                <div class="input-group">
                    <input type="text" id="gender" name="gender" placeholder="Enter Gender" value="<?php echo htmlspecialchars($user['gender']); ?>" required>
                </div>
            </div>

            <div>
                <label for="address">Address</label>
                <div class="input-group">
                    <textarea id="address" name="address" placeholder="Enter your address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                </div>
            </div>

            <div>
                <label>Phone Number</label>
                <div class="input-group">
                    <input type="text" id="phone" name="phone" placeholder="Enter Phone Number" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
            </div>

            <div>
                <label>Emergency Phone Number</label>
                <div class="input-group">
                    <input type="text" id="emergency-phone-number" name="emergency_phone_number" placeholder="Enter Emergency Phone Number" value="<?php echo htmlspecialchars($user['emergency_phone_number']); ?>" required>
                </div>
            </div>

        </div>
        <div class="center">
            <button type="submit" class="edit-btn">Update Profile</button>
            <a href="profile.php" class="cancel-btn">Cancel</a>
        </div>
    </form>
</div>

<?php
include('layouts/footer.php');
?>
