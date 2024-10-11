<?php
include('common/config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone_number'];
    $emergency_phone = $_POST['emergency_phone_number'];
    $address = $_POST['address'];
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpeg", "png" => "image/png", "jpeg" => "image/jpeg", "gif" => "image/gif"];
        $filename = $_FILES["profile_image"]["name"];
        $filetype = $_FILES["profile_image"]["type"];
        $filesize = $_FILES["profile_image"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size (5MB maximum)
        if ($filesize > 5 * 1024 * 1024) die("Error: File size is too large.");

        // Check MIME type
        if (in_array($filetype, $allowed)) {

            $new_filename = uniqid() . "." . $ext;
            $upload_dir = "images/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            move_uploaded_file($_FILES["profile_image"]["tmp_name"], $upload_dir . $new_filename);

            $profile_image = $upload_dir . $new_filename;
        } else {
            die("Error: There was a problem with your upload.");
        }
    } else {
        $profile_image = null; // No file uploaded
    }

    // Insert data into the database
    $query = "INSERT INTO patient (name, email, password, dob, age, height, weight, gender, phone, emergency_phone_number, address, role, profile_image)
              VALUES ('$name', '$email', '$password', '$dob', '$age', '$height', '$weight', '$gender', '$phone', '$emergency_phone', '$address', 'patient', '$profile_image')";

    if (mysqli_query($connection, $query)) {
        header("Location: " . BASE_URL . "login.php");
    } else {
        echo "<p>Error: " . mysqli_error($connection) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Sign Up</title>
</head>

<body>
    <form class="center" method="POST" action="" enctype="multipart/form-data">
        <div>
            <img class="logo" src="img/logo.png" alt="CARE">
            <h3> Sign Up </h3>
            <hr>
            <div class="col-2">
                <div>
                    <label>Name</label>
                    <div class="input-group">
                        <input type="text" name="name" id="name" placeholder="Enter Name" required>
                    </div>
                </div>
                <div>
                    <label>Email</label>
                    <div>
                        <input type="email" name="email" id="email" placeholder="Enter Email" required>
                    </div>
                </div>

                <div>
                    <label>Password</label>
                    <div>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                    </div>
                </div>
                <div>
                    <label>Date of Birth</label>
                    <div class="input-group">
                        <input type="date" name="dob" id="dob" placeholder="Enter Date of Birth" required>
                    </div>
                </div>
                <div>
                    <label for="age">Age</label>
                    <div class="input-group">
                        <select name="age" id="age" required>
                            <option value="">Select Age</option>
                            <?php
                            for ($i = 15; $i <= 100; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label>Height</label>
                    <div class="input-group">
                        <input type="text" name="height" id="height" placeholder="Enter Height" required>
                    </div>
                </div>

                <div>
                    <label>Weight</label>
                    <div class="input-group">
                        <input type="text" name="weight" id="weight" placeholder="Enter Weight" required>
                    </div>
                </div>
                <div>
                    <label>Gender</label>
                    <div class="input-group">
                        <input type="text" name="gender" id="gender" placeholder="Enter Gender" required>
                    </div>
                </div>

                <div>
                    <label>Phone Number</label>
                    <div class="input-group">
                        <input type="text" name="phone_number" id="phone_number" placeholder="Enter Phone Number" required>
                    </div>
                </div>

                <div>
                    <label>Emergency Phone Number</label>
                    <div class="input-group">
                        <input type="text" name="emergency_phone_number" id="emergency_phone_number" placeholder="Enter Emergency Phone Number" required>
                    </div>
                </div>

                <div>
                    <label for="address">Address</label>
                    <div class="input-group">
                        <textarea name="address" id="address" placeholder="Enter your address" required></textarea>
                    </div>
                </div>
                <div>
                <label>Profile Picture</label>
                <div class="input-group">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                </div>
            </div>
            </div>
            <div>
                <button type="submit" class="btn">Sign Up</button>
            </div>

            <div>
                <p>
                    Having account!
                    <a href="login.php"> Login </a>
                </p>
            </div>
        </div>
    </form>
    <script src="js/navbar.js"></script>
</body>

</html>