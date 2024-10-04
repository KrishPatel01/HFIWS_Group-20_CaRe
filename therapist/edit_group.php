<?php
session_start();
$page_title = 'Edit Patient Group';
include('layouts/header.php');
include('../common/config/database.php');

if ($_SESSION['role'] != "therapist") {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Check if a group_id is provided
$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
// Fetch group details
$group_query = "SELECT group_name FROM groups WHERE id = $group_id";
$group_result = mysqli_query($connection, $group_query);
$group = mysqli_fetch_assoc($group_result);

// Fetch patients not in this group
$patient_query = "SELECT id, name FROM patient WHERE id NOT IN (SELECT patient_id FROM group_patients WHERE group_id = $group_id) and role='patient'";
$patient_result = mysqli_query($connection, $patient_query);

// Fetch patients in this group
$group_patient_query = "SELECT p.id, p.name FROM patient p JOIN group_patients gp ON p.id = gp.patient_id WHERE gp.group_id = $group_id and p.role='patient'";
$group_patient_result = mysqli_query($connection, $group_patient_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_group_name = $_POST['group_name']; // Capture the new group name from the form

    // Update the group name in the database
    $update_group_name_query = "UPDATE groups SET group_name = ? WHERE id = ?";
    $stmt = $connection->prepare($update_group_name_query);
    $stmt->bind_param("si", $new_group_name, $group_id);
    $stmt->execute();

    // Only update patients if patient_ids is provided
    if (!empty($_POST['patient_ids'])) {
        $selected_patients = explode(',', $_POST['patient_ids']);

        // Clear current group patients
        $delete_query = "DELETE FROM group_patients WHERE group_id = ?";
        $stmt = $connection->prepare($delete_query);
        $stmt->bind_param("i", $group_id);
        $stmt->execute();

        // Add new set of patients to the group
        $insert_query = "INSERT INTO group_patients (group_id, patient_id) VALUES (?, ?)";
        $stmt = $connection->prepare($insert_query);
        foreach ($selected_patients as $patient_id) {
            $stmt->bind_param("ii", $group_id, $patient_id);
            $stmt->execute();
        }
    }

    echo "<script>alert('Group updated successfully!');</script>";
    header("Refresh:0");
}

?>

<!-- Flexbox Layout for the patient list and group area -->
<form method="POST" action="">
    <div class="container-group">
        <div class="patient-list">
            <div class="search-bar">
                <input type="text" placeholder="Search Patient" id="searchInput" onkeyup="filterPatients()">
            </div>
            <div class="group-name">
                <label for="group_name">Group Name:</label>
                <input type="text" name="group_name" id="group_name" value="<?= htmlspecialchars($group['group_name']) ?>" required>
            </div>
            <div id="patientNames" class="list">
                <?php while ($patient = mysqli_fetch_assoc($patient_result)): ?>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient<?= $patient['id'] ?>">
                        <?= htmlspecialchars($patient['name']) ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="group-area">
            <ul id="group" class="group-list" ondrop="drop(event)" ondragover="allowDrop(event)">
                <?php while ($patient = mysqli_fetch_assoc($group_patient_result)): ?>
                    <div class="group-patient-item" draggable="true" ondragstart="drag(event)" id="patient<?= $patient['id'] ?>">
                        <?= htmlspecialchars($patient['name']) ?>
                        <button type="button" class="btn remove-btn" onclick="removePatient('patient<?= $patient['id'] ?>')">Remove</button>
                    </div>
                <?php endwhile; ?>
            </ul>
            <input type="hidden" name="patient_ids" id="patient_ids">
            <button type="submit" class="btn">Update Group</button>
        </div>
    </div>
</form>




<?php include('layouts/footer.php'); ?>
<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var patientElement = document.getElementById(data);

        // Append to group only if not already in the group list
        if (!patientElement.closest('.group-area')) {
            appendToGroup(patientElement);
        }
        updatePatientIds();
    }

    function appendToGroup(element) {
        // Create a new container div for the patient
        var newElement = element.cloneNode(true); // Clone the node
        newElement.className = 'group-patient-item'; // Adjust class name if necessary

        // Add the remove button
        var removeButton = document.createElement('button');
        removeButton.textContent = 'Remove';
        removeButton.className = 'btn remove-btn';
        removeButton.onclick = function() {
            removePatient(element.id);
        };

        newElement.appendChild(removeButton);
        document.getElementById('group').appendChild(newElement);

        // Remove the original element from the patient list
        element.parentNode.removeChild(element);
    }

    function updatePatientIds() {
        var groupList = document.getElementById("group").getElementsByClassName("group-patient-item");
        var patientIds = Array.from(groupList).map(item => item.id.replace('patient', ''));
        document.getElementById("patient_ids").value = patientIds.join(',');
    }

    function removePatient(patientId) {
        var element = document.getElementById(patientId);
        if (element) {
            document.getElementById("patientNames").appendChild(element); // Move back to patient list
            element.classList.replace('group-patient-item', 'patient-item'); // Adjust class for style
            element.removeChild(element.querySelector('button')); // Remove the button
        }
        updatePatientIds();
    }

    function filterPatients() {
        var input = document.getElementById('searchInput');
        var filter = input.value.toUpperCase();
        var patientList = document.getElementById("patientNames");
        var items = patientList.getElementsByClassName('patient-item');

        for (var i = 0; i < items.length; i++) {
            var txtValue = items[i].textContent || items[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    }
</script>
