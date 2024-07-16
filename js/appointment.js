// Function to clear error messages when user starts typing
function clearErrorMessage(elementId) {
  document.getElementById(elementId).textContent = "";
}

// Function to validate the form
function validateForm() {
  // Fetching form inputs
  let name = document.getElementById("name").value;
  let phone = document.getElementById("phone").value;
  let email = document.getElementById("email").value;
  let services = document.getElementById("services").selectedOptions; // Array of selected options
  let date = document.getElementById("date").value;
  let time = document.getElementById("time").value;

  // Resetting previous error messages
  document.getElementById("nameError").textContent = "";
  document.getElementById("phoneError").textContent = "";
  document.getElementById("emailError").textContent = "";
  document.getElementById("servicesError").textContent = "";
  document.getElementById("dateError").textContent = "";
  document.getElementById("timeError").textContent = "";

  // Validation logic
  var isValid = true;
  if (name === "") {
    document.getElementById("nameError").textContent = "Please enter your name";
    isValid = false;
  }
  if (phone === "") {
    document.getElementById("phoneError").textContent =
      "Please enter your phone number";
    isValid = false;
  } else if (!/^\d{10}$/.test(phone)) {
    document.getElementById("phoneError").textContent =
      "Please enter a valid phone number";
    isValid = false;
  }
  if (email === "") {
    document.getElementById("emailError").textContent =
      "Please enter your email";
    isValid = false;
  } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
    document.getElementById("emailError").textContent =
      "Please enter a valid email address";
    isValid = false;
  }
  if (services.length === 0) {
    // Check if no services are selected
    document.getElementById("servicesError").textContent =
      "Please select at least one service";
    isValid = false;
  }
  if (date === "") {
    document.getElementById("dateError").textContent = "Please select a date";
    isValid = false;
  }
  if (time === "") {
    document.getElementById("timeError").textContent = "Please select a time";
    isValid = false;
  }

  return isValid;
}

// Add event listeners to input fields to clear error messages
document.getElementById("name").addEventListener("input", function () {
  clearErrorMessage("nameError");
});
document.getElementById("phone").addEventListener("input", function () {
  clearErrorMessage("phoneError");
});
document.getElementById("email").addEventListener("input", function () {
  clearErrorMessage("emailError");
});
document.getElementById("services").addEventListener("change", function () {
  clearErrorMessage("servicesError");
});
document.getElementById("date").addEventListener("input", function () {
  clearErrorMessage("dateError");
});
document.getElementById("time").addEventListener("input", function () {
  clearErrorMessage("timeError");
});
