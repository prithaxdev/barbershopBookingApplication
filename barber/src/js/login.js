const form = document.getElementById("form"),
  username = document.getElementById("username"),
  password = document.getElementById("password");
form.addEventListener("submit", (event) => {
  event.preventDefault();
  validateInputs();
});

const setError = (element, message) => {
  const inputcontrol = element.parentElement;
  const errorDisplay = inputcontrol.querySelector(".error");
  errorDisplay.innerText = message;
  inputcontrol.classList.remove("success");
  inputcontrol.classList.add("error");
};
const setSuccess = (element) => {
  const inputControl = element.parentElement;
  const errorDisplay = inputControl.querySelector(".error");

  errorDisplay.innerText = "";
  inputControl.classList.remove("error");
  inputControl.classList.add("success");
};

const validateInputs = () => {
  const usernameValue = username.value.trim();
  const passwordValue = password.value.trim();

  if (usernameValue === "") {
    setError(username, "Username is required");
  } else {
    setSuccess(username);
  }

  if (passwordValue === "") {
    setError(password, "Password is required");
  } else {
    setSuccess(password);
  }
};
