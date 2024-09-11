function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
}

function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    var draggedElement = document.getElementById(data);
    
    // Check if the drop target is the group list
    if (event.target.classList.contains('group-list')) {
        // Create a new element for the group area with a remove button
        var groupItem = document.createElement('li');
        groupItem.className = 'group-patient-item';
        groupItem.innerHTML = draggedElement.textContent + ' <button onclick="removeFromGroup(\'' + data + '\')">Remove</button>';
        groupItem.id = 'group-' + data;
        
        // Add the patient to the group
        event.target.appendChild(groupItem);
        
        // Remove the original patient from the patient list
        draggedElement.remove();
    }
}

function removeFromGroup(patientId) {
    var groupItem = document.getElementById('group-' + patientId);
    groupItem.remove(); // Remove the patient from the group list
    
    // Add the patient back to the patient list
    var patientList = document.getElementById('patientNames');
    var patientItem = document.createElement('div');
    patientItem.className = 'patient-item';
    patientItem.id = patientId;
    patientItem.draggable = true;
    patientItem.ondragstart = drag;
    patientItem.innerHTML = groupItem.textContent.replace(' Remove', ''); // Remove the 'Remove' text
    
    // Add the patient back to the patient list
    patientList.appendChild(patientItem);
}
