<?php
session_start();
$page_title = 'Auditor Dashboard';
include('layouts/header.php');
include('../common/config/database.php');


if ($_SESSION['role'] != 'auditor') {
    header("Location: " . BASE_URL . "login.php");
    exit();
}


$sql = "
SELECT
    patient.id AS therapist_id,
    patient.name AS therapist_name,
    COUNT(DISTINCT tc.patient_id) AS num_patients,
    GROUP_CONCAT(DISTINCT tc.type SEPARATOR ', ') AS case_types,
    AVG(tc.duration) AS avg_consultation_length
FROM
    patient
LEFT JOIN therapist_consultation tc ON
    patient.id = tc.therapist_id
WHERE
    role = 'therapist'
GROUP BY
    patient.id
";
    
$result = $connection->query($sql);
?>

<div class="filter-bar">
    <label>Search Patient</label>
    <input type="text" id="patient-search" placeholder="Enter patient name">
</div>
<div class="auditor-dashboard">
    <h2>Therapist Overview</h2>
    <table>
        <thead>
            <tr>
                <th>Therapist Name</th>
                <th>Number of Patients Treated</th>
                <th>Types of Cases</th>
                <th>Average Consultation Time (Minutes)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['therapist_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['num_patients']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['case_types']) . "</td>";
                    echo "<td>" . round($row['avg_consultation_length'], 2) . " minutes</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No therapist data available</td></tr>";
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
