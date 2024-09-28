<?php
$page_title = 'Patient Group';
include('layouts/header.php');
include('../common/config/database.php');

// Fetch existing groups from the database
$group_query = "SELECT g.id, g.group_name, COUNT(gp.patient_id) as total_members 
                FROM groups g
                LEFT JOIN group_patients gp ON g.id = gp.group_id
                GROUP BY g.id";
$group_result = mysqli_query($connection, $group_query);

// Fetch patients dynamically from the database
$patient_query = "SELECT id, name FROM patient where role = 'patient' and id NOT IN (SELECT gp.patient_id FROM group_patients gp)";
$patient_result = mysqli_query($connection, $patient_query);

// Handle form submission when the "Create" button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = $_POST['group_name'];
    $selected_patients = explode(',', $_POST['patient_ids']); // Get the patient IDs from the hidden input

    if (!empty($selected_patients) && !empty($group_name)) {
        // Check if the group name already exists
        $group_check_query = "SELECT id FROM groups WHERE group_name = '$group_name'";
        $group_check_result = mysqli_query($connection, $group_check_query);

        if (mysqli_num_rows($group_check_result) > 0) {
            // Group exists, get the group ID
            $group_row = mysqli_fetch_assoc($group_check_result);
            $group_id = $group_row['id'];

            // For each selected patient, check if they are already in the group
            foreach ($selected_patients as $patient_id) {
                $patient_check_query = "SELECT * FROM group_patients WHERE group_id = $group_id AND patient_id = $patient_id";
                $patient_check_result = mysqli_query($connection, $patient_check_query);

                if (mysqli_num_rows($patient_check_result) == 0) {
                    // Patient is not in the group, add them
                    $insert_patient_query = "INSERT INTO group_patients (group_id, patient_id) VALUES ('$group_id', '$patient_id')";
                    mysqli_query($connection, $insert_patient_query);
                }
            }
            echo "<script>alert('New patients have been added to the group: $group_name !');</script>";
            header("Refresh:0");
        } else {
            // Group does not exist, create a new group and add patients
            $insert_group_query = "INSERT INTO groups (group_name) VALUES ('$group_name')";
            if (mysqli_query($connection, $insert_group_query)) {
                $group_id = mysqli_insert_id($connection);

                foreach ($selected_patients as $patient_id) {
                    $insert_patient_query = "INSERT INTO group_patients (group_id, patient_id) VALUES ('$group_id', '$patient_id')";
                    mysqli_query($connection, $insert_patient_query);
                }
                echo "<script>alert('Group created successfully: $group_name !');</script>";
                header("Refresh:0");
            } else {
                echo "<script>alert('An error occurred while creating the group. Please try again!');</script>";
                header("Refresh:0");
            }
        }
    } else {
        echo "<script>alert('Please select at least one patient and provide a group name!');</script>";
        header("Refresh:0");
    }
}

?>

<div class="groups-section">
    <h2 class="center">Groups</h2>
    <table class="group-table">
        <thead>
            <tr>
                <th>Sr no</th>
                <th>Group Name</th>
                <th>Total Members</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($group_result) > 0) {
                $sr_no = 1;
                while ($group = mysqli_fetch_assoc($group_result)) {
                    echo '<tr>';
                    echo '<td>' . $sr_no++ . '</td>';
                    echo '<td><a href="edit_group.php?group_id=' . $group['id'] . '" class="group-info">' . $group['group_name'] . '</a></td>';
                    echo '<td>' . $group['total_members'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">No groups found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<div class="container">
    <form method="POST" action="">
        <div class="patient-list">
            <div class="search-bar">
                <input type="text" placeholder="Search Patient" id="searchInput" onkeyup="filterPatients()">
            </div>
            <div id="patientNames" class="patient-list">
                <?php
                if (mysqli_num_rows($patient_result) > 0) {
                    while ($patient = mysqli_fetch_assoc($patient_result)) {
                        echo '<div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient' . $patient['id'] . '">' . $patient['name'] . '</div>';
                    }
                } else {
                    echo "<p>No patients found</p>";
                }
                ?>
            </div>
        </div>

        <div class="group-area">
            <div id="group-name">
                <label>Group Name</label>
                <input class="group-name-input" type="text" name="group_name" placeholder="Enter Group Name..." required />
            </div>
            <ul id="group" class="group-list" ondrop="drop(event)" ondragover="allowDrop(event)">
                <!-- Dynamically add patients here via drag-and-drop -->
            </ul>

            <!-- Hidden input to store patient IDs for group creation -->
            <input type="hidden" name="patient_ids" id="patient_ids">

            <button type="submit" class="btn">Create</button>
        </div>
    </form>
</div>

<?php
include('layouts/footer.php');
?>

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

        // Append the dragged patient to the group list if not already there
        if (!document.getElementById('group').contains(patientElement)) {
            document.getElementById("group").appendChild(patientElement);

            // Add the patient ID to the hidden input for group creation
            var patientIdsInput = document.getElementById("patient_ids");
            var patientId = data.replace('patient', ''); // Extract the ID number

            // Update the hidden input field
            var currentValue = patientIdsInput.value ? patientIdsInput.value.split(',') : [];

            // Only add if it's not already in the list
            if (!currentValue.includes(patientId)) {
                currentValue.push(patientId);
                patientIdsInput.value = currentValue.join(',');
            }
        }
    }

    // Search filter for patient names
    function filterPatients() {
        var input, filter, patientList, items, i, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toLowerCase();
        patientList = document.getElementById("patientNames");
        items = patientList.getElementsByClassName('patient-item');

        for (i = 0; i < items.length; i++) {
            txtValue = items[i].textContent || items[i].innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    }
</script>