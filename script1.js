function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text", event.target.id);
}

/*
*Reference
*https://www.javascripttutorial.net/web-apis/javascript-drag-and-drop/
*/
function drop(event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    var draggedElement = document.getElementById(data);
    
    if (event.target.classList.contains('group-list')) {
        var groupItem = document.createElement('li');
        groupItem.className = 'group-patient-item';
        groupItem.innerHTML = draggedElement.textContent + ' <button onclick="removeFromGroup(\'' + data + '\')">Remove</button>';
        groupItem.id = 'group-' + data;
    
        event.target.appendChild(groupItem);
        draggedElement.remove();
    }
}

function removeFromGroup(patientId) {
    var groupItem = document.getElementById('group-' + patientId);
    groupItem.remove();
    var patientList = document.getElementById('patientNames');
    var patientItem = document.createElement('div');
    patientItem.className = 'patient-item';
    patientItem.id = patientId;
    patientItem.draggable = true;
    patientItem.ondragstart = drag;
    patientItem.innerHTML = groupItem.textContent.replace(' Remove', '');
    patientList.appendChild(patientItem);
}
