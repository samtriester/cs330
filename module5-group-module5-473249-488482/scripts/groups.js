//Create new Group

function createGroup() {
    console.log("creating group");
    const newGroupContainer = document.getElementById("newGroup");
    console.log(newGroupContainer.childNodes.length);

    const newGroupName = newGroupContainer.childNodes[1].value;
    const isCreate = newGroupContainer.childNodes[2].value;
    const isAdd = newGroupContainer.childNodes[3].value;
    const isRemove = newGroupContainer.childNodes[4].value;
    //const submit = document.getElementById("newGroup").child[4].value;

    console.log("new Group name: " + newGroupName+
                "\nisCreate: "+ isCreate+
                "\nisAdd: "+ isAdd+
                "\nisRemove: "+ isRemove);

    const data = {"group_name": newGroupName,
                    "isCreate": isCreate,
                    "isAdd": isAdd,
                    "isRemove": isRemove};
    fetch("groups.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log("data: " + JSON.stringify(data, null, 2));
        document.getElementById("newGroup").innerHTML="Click Here to Create a New Group";
        setTimeout(updateCalendar,10); 
        //Close form
        //document.getElementById("newGroup").innerHTML = "Click Here to Add a New Group";
    }).catch(err => console.error(err));

    //Update DOM
    // document.getElementById("newGroup").innerHTML="Click Here to Create a New Group";
    // setTimeout(updateCalendar,10); 
}

//Add user to Group
function addNewMemberToGroup() {
    console.log("creating group");
    const addMemberContainer = document.getElementById("newMemb");
    console.log(addMemberContainer.childNodes.length);

    const groupName = addMemberContainer.getElementsByTagName('input')[0].value;
    const usernameToAdd = addMemberContainer.getElementsByTagName('input')[1].value;  
    const isCreate = addMemberContainer.getElementsByTagName('input')[2].value;
    const isAdd = addMemberContainer.getElementsByTagName('input')[3].value;
    const isRemove = addMemberContainer.getElementsByTagName('input')[4].value;
    //const submit = document.getElementById("newGroup").child[4].value;

    console.log("gGroup name: " + groupName +
                "\nusernameToAdd: " + usernameToAdd +
                "\nisCreate: "+ isCreate +
                "\nisAdd: "+ isAdd +
                "\nisRemove: "+ isRemove);

    const data = {"group_name": groupName,
                    "usernameToAdd": usernameToAdd,
                    "isCreate": isCreate,
                    "isAdd": isAdd,
                    "isRemove": isRemove};
    fetch("groups.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log("data: " + JSON.stringify(data, null, 2));
        //Close form
        //document.getElementById("newGroup").innerHTML = "Click Here to Add a New Group";
    }).catch(err => console.error(err));
    
    //Update DOM
    document.getElementById("newMemb").innerHTML="Click Here to Add a New Member to an Existing Group";
    setTimeout(updateCalendar,10); 
}
//Remove user from group 
function removeMemberFromGroup() {
    //console.log("creating group");
    const removeMemberContainer = document.getElementById("remMemb");
    console.log(removeMemberContainer.childNodes.length);

    const groupName = removeMemberContainer.getElementsByTagName('input')[0].value;
    const usernameToRemove = removeMemberContainer.getElementsByTagName('input')[1].value;  
    const isCreate = removeMemberContainer.getElementsByTagName('input')[2].value;
    const isAdd = removeMemberContainer.getElementsByTagName('input')[3].value;
    const isRemove = removeMemberContainer.getElementsByTagName('input')[4].value;
    //const submit = document.getElementById("newGroup").child[4].value;

    console.log("gGroup name: " + groupName +
                "Username to Remove: " + usernameToRemove +
                "\nisCreate: "+ isCreate +
                "\nisAdd: "+ isAdd +
                "\nisRemove: "+ isRemove);

    const data = {"group_name": groupName,
                    "usernameToRemove": usernameToRemove,
                    "isCreate": isCreate,
                    "isAdd": isAdd,
                    "isRemove": isRemove};
    fetch("groups.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log("data: " + JSON.stringify(data, null, 2));
        //Close form
        //document.getElementById("newGroup").innerHTML = "Click Here to Add a New Group";
    }).catch(err => console.error(err));
    document.getElementById("remMemb").innerHTML="Click Here to Remove a Member from an Existing Group";
    setTimeout(updateCalendar,10); 
}



//load group Events


//Filter by group
