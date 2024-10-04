<?php
session_start();
$page_title = 'Edit Note';
include('layouts/header.php');

include('../common/config/database.php');

if ($_SESSION['role'] != "therapist") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$p_id = $_GET['id'];
	$id = $_SESSION['user_id'];
	$title = $_POST['note_title'];
	$observation = $_POST['observation'];
	$note_text = $_POST['notes'];
	$note_id = $_POST['note_id'];

	$sql = "INSERT INTO therapist_notes (therapist_id, patient_id, title, observation, note_text, date_time) VALUES ( '$id','$p_id','$title','$observation', '$note_text' , NOW())";
	$result = $connection->query($sql);
	header("Location: dashboard.php");
}
if (isset($_GET['id'])) {
	$sql = "SELECT name FROM patient where id=" . $_GET['id'];
	$result = $connection->query($sql);
	$note = $result->fetch_assoc();
}
?>
<div class="note-container">
	<h1>Notes for <?php echo $note['name']; ?></h1>
	<form method="POST" action="">
		<label>Notes Title</label>
		<input type="text" id="note_title" name="note_title" placeholder="Title for note">

		<label>Observation</label>
		<textarea id="observation" name="observation" placeholder="Patient Observation"></textarea>

		<label>Patient Notes</label>
		<textarea id="notes" name="notes" placeholder="Detailed note"></textarea>

		<button type="submit" class="btn">Add</button>
	</form>
</div>

<?php
include('layouts/footer.php');
?>