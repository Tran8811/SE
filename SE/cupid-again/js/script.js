document.getElementById("requestOTPForm").addEventListener("submit", function(event) {
  event.preventDefault();

  const username = document.getElementById("username").value;
  const email = document.getElementById("email").value;

  fetch("/cupid-again/php/send_otp.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `username=${username}&email=${email}`
  })
    .then(response => response.text())
    .then(data => {
      alert(data);
      if (data.includes("OTP sent")) {
        document.getElementById("requestOTPForm").style.display = "none";
        document.getElementById("resetPasswordForm").style.display = "block";
      }
    })
    .catch(error => console.error("Error:", error));
});

document.getElementById("resetPasswordForm").addEventListener("submit", function(event) {
  event.preventDefault();

  const otp = document.getElementById("otp").value;
  const newPassword = document.getElementById("new_password").value;

  fetch("/cupid-again/php/reset_password.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `otp=${otp}&new_password=${newPassword}`
  })
    .then(response => response.text())
    .then(data => {
      alert(data);
      if (data.includes("Password updated")) {
        window.location.href = "sign_in.html";
      }
    })
    .catch(error => console.error("Error:", error));
});

function goToSignIn() {
  window.location.href = "sign_in.html";
}
