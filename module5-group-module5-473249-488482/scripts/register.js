
function register(event) {
    const new_username = document.getElementById("new_username").value; // Get the username from the form
    const new_password = document.getElementById("new_password").value; // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': new_username, 'password': new_password };
    console.log(JSON.stringify(data, null, 2));
    fetch("registerScript.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            // console.log(data.success ? "You've been logged in!" : `You were not logged in ${data.message}`)
            (data.success) ? (
                window.location.href = "home"
            ) : (
                document.getElementById("register-error").innerHTML = `You were not registerd: ${data.message}`
            //`You were not logged in ${data.message}`)
            )
        }).catch(err => console.error(err));
}

document.getElementById("register_btn").addEventListener("click", register, false); // Bind the AJAX call to button click
//Create New User
