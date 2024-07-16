document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    // do nothing if form not validated
    if (!validateForm(form)) return;

    // If form is validated, submit the form using JavaScript
    form.submit();
  });

  const validateForm = (form) => {
    let valid = true;

    // check for empty fields
    let name = form.querySelector(".name");
    let message = form.querySelector(".message");
    let email = form.querySelector(".email");

    if (name.value.trim() === "") {
      giveError(name, "Please enter a name");
      valid = false;
    } else if (/\d/.test(name.value)) {
      giveError(name, "Name should not contain numbers");
      valid = false;
    }

    if (message.value.trim() === "") {
      giveError(message, "Please enter a message.");
      valid = false;
    } else if (/\d/.test(message.value)) {
      giveError(message, "Message should not contain numbers");
      valid = false;
    }

    // email validation
    let emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    let emailValue = email.value.trim();

    if (!emailRegex.test(emailValue)) {
      giveError(email, "Please enter a valid email!");
      valid = false;
    }

    return valid; // Return the validation status
  };

  const giveError = (field, message) => {
    let parentElement = field.parentElement;

    parentElement.classList.add("error");
    // if error msg already exist remove it
    let existingError = parentElement.querySelector(".err-msg");
    if (existingError) {
      existingError.remove();
    }
    let error = document.createElement("span");
    error.textContent = message;
    error.classList.add("err-msg");
    parentElement.appendChild(error);
  };

  // lets remove error on input
  const input = document.querySelectorAll("input");
  const textarea = document.querySelectorAll("textarea");

  let allFields = [...input, ...textarea];

  allFields.forEach((field) => {
    field.addEventListener("input", () => {
      removeError(field);
    });
  });

  const removeError = (field) => {
    let parentElement = field.parentElement;
    parentElement.classList.remove("error");
    let error = parentElement.querySelector(".err-msg");

    if (error) {
      error.remove();
    }
  };
});
