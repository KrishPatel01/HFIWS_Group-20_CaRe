<?php
session_start();
$page_title = 'Therapist Dashboard';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "therapist") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

?>

<head>
	<link rel="stylesheet" href="../styles/dashboard_styles.css">
</head>
<?php
if (isset($_GET['type']) && $_GET['type'] == "update") {
	$temp = $_GET['is_highlight'] == 1 ? 0 : 1;
	$sql = "UPDATE patient SET is_highlight =" . $temp . " WHERE id = " . $_GET['id'];
	$result = $connection->query($sql);
	header("Location: dashboard.php");
} else if (isset($_GET['type']) && $_GET['type'] === "delete") {
	$sql = "DELETE from therapist_notes where id=" . $_GET['id'];
	$result = $connection->query($sql);
	header("Location: dashboard.php");
}
$sql = "SELECT id, name, age, gender, phone, is_highlight FROM patient where role='patient'";
$result = $connection->query($sql);
?>
<div class="filter-bar">
	<label>Search Patient</label>
	<input type="text" id="patient-search" placeholder="Enter patient name">
</div>
<div class="patients">
	<h2>Today's Appointments</h2>
	<table id="patient-table">
		<thead>
			<tr>
				<th></th>
				<th>Sr no</th>
				<th>Patient Name</th>
				<th>Age</th>
				<th>Gender</th>
				<th>Phone Number</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ($result->num_rows > 0) {
				$sr_no = 1;
				while ($row = $result->fetch_assoc()) {
					if ($row['is_highlight']) {
						echo "<tr class='highlight'>";
						echo "<td><a href='dashboard.php?id=" . $row['id'] . "&type=update&is_highlight=" . $row['is_highlight'] . "' class='highlight-btn'><i class='fa-solid fa-star star-icon'></i></a></td>";
					} else {
						echo "<tr>";
						echo "<td><a href='dashboard.php?id=" . $row['id'] . "&type=update&is_highlight=" . $row['is_highlight'] . "' class='highlight-btn'><i class='fa-regular fa-star star-icon'></i></a></td>";
					}

					echo "<td>" . $sr_no . "</td>";
					echo "<td><a href='profile.php?id=" . $row['id'] . "' class='patient-info patient-name'>" . $row['name'] . "</a></td>";
					echo "<td>" . $row['age'] . "</td>";
					echo "<td>" . $row['gender'] . "</td>";
					echo "<td>" . $row['phone'] . "</td>";

					$note_sql = "SELECT id FROM therapist_notes WHERE patient_id = ?";
					$note_stmt = $connection->prepare($note_sql);
					$note_stmt->bind_param("i", $row['id']);
					$note_stmt->execute();
					$note_result = $note_stmt->get_result();

					if ($note_result->num_rows > 0) {
						$note = $note_result->fetch_assoc();
						$note_id = $note['id'];

						echo "<td>
								<a href='update_note.php?id=" . $note_id . "' class='edit-btn'>Edit</a>
								<a href='dashboard.php?id=" . $note_id . "&type=delete' class='cancel-btn' onclick='return confirmDelete();'>Delete</a>
							  </td>";
					} else {
						echo "<td><a href='add_note.php?id=" . $row['id'] . "' class='btn'>Add</a></td>";
					}
					$sr_no++;
					echo "</tr>";
				}
			} else {
				echo "<tr><td colspan='7'>No patients found</td></tr>";
			}
			?>
		</tbody>
	</table>
</div>
</div>
<script type="text/javascript">
	function confirmDelete() {
		return confirm('Are you sure you want to delete this note?');
	}
</script>
<script>
    // Get the search input element and the patient table
    const searchInput = document.getElementById('patient-search');
    const table = document.getElementById('patient-table');
    const rows = table.getElementsByTagName('tr');

    // Add event listener to the search input
    searchInput.addEventListener('keyup', function() {
        // Get the search term
        const searchTerm = searchInput.value.toLowerCase();

        // Loop through all the rows in the table (start from index 1 to skip the header row)
        for (let i = 1; i < rows.length; i++) {
            // Get the patient name cell for the current row
            const patientNameCell = rows[i].getElementsByClassName('patient-name')[0];
            if (patientNameCell) {
                const patientName = patientNameCell.textContent.toLowerCase();

                // Show the row if the patient name includes the search term, otherwise hide it
                if (patientName.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    });
</script>
<?php
include('layouts/footer.php');
?>