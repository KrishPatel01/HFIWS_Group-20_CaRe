const groupData = {
    "Yoga": ["Kate Watson", "John Doe", "Alice Green"],
    "Morning talk": ["Sarah Connor", "Bob Brown"],
    "Laughing Club": ["Charlie Day", "Emily Stone"]
};

function showGroup(groupName) {
    const groupTitle = document.getElementById("group-name");
    groupTitle.innerText = groupName;

    const groupMembersDiv = document.getElementById("group-members");
    groupMembersDiv.innerHTML = ''; // Clear previous members

    const members = groupData[groupName];

    if (members) {
        members.forEach(member => {
            const memberDiv = document.createElement("div");
            memberDiv.innerText = member;
            groupMembersDiv.appendChild(memberDiv);
        });
    } else {
        groupMembersDiv.innerText = "No members in this group.";
    }
}
