console.log("Script loaded");

document.getElementById("contact-form").addEventListener("submit", function(event){
    event.preventDefault();

    sendEmail();
});


function sendEmail() {
    var formData = new FormData(document.getElementById("contact-form"));

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                alert("Email sent successfully!"); // Show success message
                clearFormInputs(); // Clear form inputs
            } else {
                alert("Error: Email could not be sent."); // Show error message
            }
        }
    };

    xhr.open("POST", "send_email.php", true);
    xhr.send(formData);
}

function clearFormInputs() {
    document.getElementById("name").value = "";
    document.getElementById("email").value = "";
    document.getElementById("message").value = "";
}