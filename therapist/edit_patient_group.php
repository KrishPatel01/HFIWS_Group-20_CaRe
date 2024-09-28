<?php
$page_title = 'Edit Patient Group';
include('layouts/header.php');
?>
        <div class="container">
            <div class="patient-list">
                <div class="patient-list-top">
                    <div class="search-bar">
                        <input type="text" placeholder="Search Patient" id="searchInput" onkeyup="filterPatients()">
                    </div>
                    <h3 class="group-name">ABC</h3>
                </div>
                <div id="patientNames" class="list">
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient1">Steve Smith</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient2">Alex</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient3">Jock Nilose</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient4">Chris Werner
                    </div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient5">Katy Salon</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient6">Wendy</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient7">Kat Werner</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient8">Ash</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient9">Treeny</div>
                    <div class="patient-item" draggable="true" ondragstart="drag(event)" id="patient10">Steven</div>
                </div>
            </div>
            <div class="group-area">
                <div id="group-name">
                    <label>Group Name</label>
                    <input class="group-name" type="text" placeholder="Enter Group Name..." />
                </div>
                <ul id="group" class="group-list" ondrop="drop(event)" ondragover="allowDrop(event)">
                </ul>
                <a href="#" class="edit-btn">Update</a>
            </div>
        </div>
    </div>
<?php
include('layouts/footer.php');
?>
