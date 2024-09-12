const patientData = {
    "Kate Watson": {
        "Journal": "Feeling calm and positive.",
        "Sleep Cycle": "8 hours",
        "Exercise": "30 minutes of yoga",
        "Eating Habits": "Healthy, balanced meals",
        "Affirmation": "I am in control of my life.",
        "ObservationNotes": [
            "Noted improvement in sleep habits.",
            "Patient is responding well to therapy."
        ]
    },
    "John Doe": {
        "Journal": "Feeling anxious.",
        "Sleep Cycle": "6 hours",
        "Exercise": "1 hour gym workout",
        "Eating Habits": "Skipped lunch, ate fast food",
        "Affirmation": "I can manage my stress.",
        "ObservationNotes": [
            "High levels of anxiety. Needs stress management techniques."
        ]
    },
    "Alice Green": {
        "Journal": "Excited about the week ahead.",
        "Sleep Cycle": "7 hours",
        "Exercise": "30 minutes jogging",
        "Eating Habits": "Fruit, vegetables, and grains",
        "Affirmation": "I attract positive energy.",
        "ObservationNotes": [
            "Very positive mood. Good progress in physical health."
        ]
    }
};

let currentPatient = ''; 

// Function to display the patient's details
function showPatientDetails(patientName) {
    currentPatient = patientName;
    const patientDetailsDiv = document.getElementById("patient-info");
    const observationNotesDiv = document.getElementById("observation-notes");
    patientDetailsDiv.innerHTML = ''; 
    observationNotesDiv.innerHTML = '';

    const data = patientData[patientName]; 

    if (data) {
        for (const [key, value] of Object.entries(data)) {
            if (key !== "ObservationNotes") {
                const detailDiv = document.createElement("div");
                detailDiv.innerHTML = `<strong>${key}:</strong> ${value}`;
                patientDetailsDiv.appendChild(detailDiv);
            }
        }

        // Add observation notes
        if (data.ObservationNotes && data.ObservationNotes.length > 0) {
            data.ObservationNotes.forEach(note => {
                const noteDiv = document.createElement("div");
                noteDiv.innerHTML = `• ${note}`;
                observationNotesDiv.appendChild(noteDiv);
            });
        } else {
            observationNotesDiv.innerHTML = '<p>No notes available.</p>';
        }
    } else {
        patientDetailsDiv.innerText = "No data available for this patient.";
    }
}

// Function to add observation notes
function addObservationNote() {
    const newNoteInput = document.getElementById("new-note");
    const newNote = newNoteInput.value.trim();

    if (newNote && currentPatient) {
        if (!patientData[currentPatient].ObservationNotes) {
            patientData[currentPatient].ObservationNotes = [];
        }
        patientData[currentPatient].ObservationNotes.push(newNote);

        showPatientDetails(currentPatient);

        newNoteInput.value = '';
    } else {
        alert('Please select a patient and enter a note.');
    }
}