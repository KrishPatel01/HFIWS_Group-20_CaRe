<?php
$page_title = 'Edit Note';
include('layouts/header.php');

include('../common/config/database.php');

if ($_SESSION['role'] != "therapist") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = $_POST['note_title'];
	$observation = $_POST['observation'];
	$note_text = $_POST['notes'];
	$note_id = $_POST['note_id'];

	$sql = "UPDATE therapist_notes SET title = ?, note_text = ?, observation = ?, date_time = NOW() WHERE id = ?";
	$stmt = $connection->prepare($sql);
	$stmt->bind_param("sssi", $title, $note_text, $observation, $note_id);
	$result = $stmt->execute();
	if ($result) {
		header("Location: update_note.php?id=$note_id");
		exit();
	} else {
		echo "Error updating note.";
		print_r($sql);
	}
} else if (isset($_GET['type']) && $_GET['type'] === "delete") {
	$sql = "DELETE from therapist_notes where id=" . $_GET['id'];
	$result = $connection->query($sql);
	header("Location: dashboard.php");
}
if (isset($_GET['id'])) {
	$note_id = $_GET['id'];
	$sql = "SELECT tn.title, tn.note_text, tn.observation, tn.date_time, p.name FROM therapist_notes tn 
    JOIN patient p ON tn.patient_id = p.id WHERE tn.id = " . $note_id;
	$result = $connection->query($sql);
	$note = $result->fetch_assoc();
}
?>
<div class="note-container">
	<h1>Notes for <?php echo $note['name']; ?></h1>
	<form method="POST" action="">

		<input type="hidden" name="note_id" value="<?php echo $note_id; ?>">
		<label>Notes Title</label>
		<input type="text" id="note_title" name="note_title" placeholder="Title for note" value="<?php echo $note['title']; ?>">


		<label>Observation</label>
		<textarea id="observation" name="observation" placeholder="Patient Observation"><?php echo $note['observation']; ?></textarea>

		<label>Patient Notes</label>
		<textarea id="notes" name="notes" placeholder="Detailed note"><?php echo $note['note_text']; ?></textarea>

		<label>Last Updated</label>
		<p><?php echo date('Y-m-d H:i:s', strtotime($note['date_time'])); ?></p>

		<div class="center">
			<button type="submit" class="edit-btn">Update</button>
			<a href="update_note.php?type=delete&id=<?php echo $note_id; ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
		</div>
	</form>
</div>

<?php
include('layouts/footer.php');
?>