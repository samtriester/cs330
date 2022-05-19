//Add Events
function addEvent() {
    console.log("adding events");
}

//TAGS
function loadAllTags(){
    console.log("inside loadAllTags");
    fetch("tags.php", {
        method: 'POST',
        //body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
            //console.log(JSON.stringify(data, null, 2));
            //Add tag elements to DOM
    }).catch(err => console.error(err));
}

window.onload = loadAllTags();

function addTag() {
    console.log("inside addTags");

    const tagNameParent = document.getElementById("newTag");
    const tagName = tagNameParent.getElementsByTagName("input")[0].value;
    console.log("tagNAme: " + tagName);
    const data = {"tagName": tagName};
    fetch("addTags.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
            console.log(JSON.stringify(data, null, 2));
            loadAllTags();
    }).catch(err => console.error(err));
    document.getElementById("newTag").innerHTML="Click Here to Create a New Tag";
    setTimeout(updateCalendar,10); 
}


//Load Events

function loadAllEvents() {
    //TODO how do I get the session token?
    //const data = { 'token': "<?php echo $_SESSION['token'];?>"};
    console.log("inside loadAllEvents");
    fetch("getEvents.php", {
        method: 'POST',
        //body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        //console.log("data2: " + JSON.stringify(data, null, 2));
        for(var event_id in data) {
            if (data.hasOwnProperty(event_id) && event_id != "token") {
                //Get corresponding day element, and add event to that day element
                var dayClassName = data[event_id]['date'].substring(0, 10);
                //console.log("day: " + dayClassName);
                var calendarNode = createCalendarNode(data[event_id]);
                //Only add to elements that exist
                if(document.getElementsByClassName(dayClassName).length > 0)
                    document.getElementsByClassName(dayClassName)[0].appendChild(calendarNode);
            }
            if(event_id=="token"){
                window.token=data[event_id];
            }
           

        }
        // console.log(data.success ? "Events loaded: " + data.events : `Events failed loading: + ${data.message}`)
    }).catch(err => console.error(err));
}


function editEvent(event) {
    console.log("showing edit event form: " + JSON.stringify(event, null, 2));
    console.log("inside editEvents");
    //console.log("showing edit event form: " + eventId);
    document.getElementById("editEvent").innerHTML="Title:<input type=\"text\" value=\""+event['title']+"\" name=\"title\" id=\"title\" required><br>Body:<input type=\"text\" value=\""+event['body']+"\"  name=\"body\" id=\"body\"><br>Date:<input type=\"text\" value=\""+event['date']+"\"  name=\"date\" id=\"date\" required><br>Tag:<input type=\"text\"  value=\""+event['tag']+"\" name=\"tag\" id=\"tag\"><br>Group:<input type=\"text\"  name=\"group\" value=\""+event['group']+"\" id=\"group\"><br><input type=\"submit\" id=\"editEventSub\" value=\"Edit\" name=\"submit\">";
    document.getElementById("editEventSub").addEventListener("click", function inner(){
        const editData= { "token":window.token,"title": document.getElementById('title').value, "body": document.getElementById('body').value, "date": document.getElementById('date').value, "tag": document.getElementById('tag').value, "group": document.getElementById('group').value };
    //Submit this API request
    fetch("editEvent.php", {
        method: "POST",
        body: JSON.stringify(editData),
        headers: { 'content-type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        console.log("edit Event response: " + JSON.stringify(data, null, 2));
    }).catch(err => console.error(err));
    updateCalendar();
        document.getElementById("editEvent").innerHTML="";
    },false);
   
}

// function deleteEvent(event) {
//     console.log("deleting events: " + event);
// }

function deleteEvent(eventId) {
    console.log("deleting events: " + eventId);
    document.getElementById("deleteEvent").innerHTML="Delete event? <br>Title: "+eventId['title']+"Date: "+eventId['date']+"<br><input type=\"submit\" id=\"deleteEventSub\" value=\"Delete\" name=\"submit\">";
    document.getElementById("deleteEventSub").addEventListener("click", function inner(){
        const deleteData= { "token":window.token,"event_id": eventId[event_id] };
    fetch("deleteEvent.php", {
        method: "POST",
        body: JSON.stringify(deleteData)
    });
    updateCalendar();
        document.getElementById("deleteEvent").innerHTML="";
    },false);
   
}

function addTagToEvent(eventId) {
    console.log("adding tag to event: " + eventId);
}

function createCalendarNode(event) {
    const rootDiv = document.createElement("div");

    //title
    const titleDiv = document.createElement("p");
    titleDiv.innerHTML = event['title'];
    titleDiv.setAttribute("style", "font-weight:bold; margin: 2px 2px 2px 2px")
    
    //Body
    const bodyDiv = document.createElement("p");
    bodyDiv.innerHTML = event['body'];
    bodyDiv.setAttribute("style", "margin: 2px 2px 2px 2px")


    //time
    const timeDiv = document.createElement("p");
    timeDiv.innerHTML = event['date'].substring(11, 16);
    timeDiv.setAttribute("style", "margin: 2px 2px 2px 2px")

    //Edit Delete buttons elements
    const buttonsDiv = document.createElement("div");

    //deleted button element
    const deleteButton = document.createElement("button");
    deleteButton.addEventListener("click", () => {
        deleteEvent(event)
    });
    deleteButton.innerHTML = "delete";
    deleteButton.setAttribute("style", "color: red; border: 2px red solid; background-color: transparent;")


    //Edit button element
    const editButton = document.createElement("button");
    editButton.addEventListener("click", () => {
        editEvent(event)
    });
    editButton.innerHTML = "edit";
    editButton.setAttribute("style", "color: orange; border: 2px orange solid; background-color: transparent;")

    //Add Tag button element
    const addTagButton = document.createElement("button");
    addTagButton.addEventListener("click", () => {
        addTagToEvent(event['id'])
    });
    addTagButton.innerHTML = "Set New Tag";
    addTagButton.setAttribute("style", "color: green; border: 2px green solid; background-color: transparent;")
    const addTagInput = document.createElement("div");
    addTagInput.innerHTML="Tag Name:<input type=\"text\" name=\"tagName\" required>";


    buttonsDiv.appendChild(editButton);
    buttonsDiv.appendChild(deleteButton);
    buttonsDiv.appendChild(addTagButton);
    buttonsDiv.appendChild(addTagInput);
    //buttonsDiv.setAttribute

    rootDiv.appendChild(timeDiv);
    rootDiv.appendChild(titleDiv);
    rootDiv.appendChild(bodyDiv);
    rootDiv.appendChild(buttonsDiv);
    rootDiv.setAttribute("id", event['id']);
    rootDiv.setAttribute("style", "border: 2px solid purple; display: flex; flex-direction: column;");

    return rootDiv;
}

//window.onload = loadAllEvents();


//Share Events

//Edit Events
