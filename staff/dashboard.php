<?php
session_start();
$page_title = 'Professional Staff Dashboard';
include('layouts/header.php');
include('../common/config/database.php');


if ($_SESSION['role'] != 'staff') {
    header("Location: " . BASE_URL . "login.php");
    exit();
}


$sql = "SELECT id, name, age, gender, height, weight FROM patient WHERE role='patient'";
$result = $connection->query($sql);
?>

<div class="filter-bar">
    <label>Search Patient</label>
    <input type="text" id="patient-search" placeholder="Enter patient name">
</div>

<div class="patients">
    <h2>Patient Demographics</h2>
    <table id="patient-table">
        <thead>
            <tr>
                <th>Sr no</th>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Height (cm)</th>
                <th>Weight (kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $sr_no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $sr_no . "</td>";
                    echo "<td><a href='profile.php?id=" . $row['id'] . "' class='patient-info patient-name'>" . $row['name'] . "</a></td>";
                    echo "<td>" . $row['age'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>" . $row['height'] . "</td>";
                    echo "<td>" . $row['weight'] . "</td>";
                    echo "</tr>";
                    $sr_no++;
                }
            } else {
                echo "<tr><td colspan='6'>No patients found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    const searchInput = document.getElementById('patient-search');
    const table = document.getElementById('patient-table');
    const rows = table.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = searchInput.value.toLowerCase();
        for (let i = 1; i < rows.length; i++) {
            const patientNameCell = rows[i].getElementsByClassName('patient-name')[0];
            if (patientNameCell) {
                const patientName = patientNameCell.textContent.toLowerCase();
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
