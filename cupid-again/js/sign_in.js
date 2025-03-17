document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".form");

  const passwordInput = document.getElementById("password");
  const togglePassword = document.querySelector(".toggle-password");

  togglePassword.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
    } else {
      passwordInput.type = "password";
    }
  });

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Ngăn chặn load lại trang

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    fetch("/cupid-again/php/sign_in.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          alert("Login successful");
          window.location.href = "../html/sign_up_2.html"; // Điều hướng sau khi đăng nhập thành công
        } else {
          alert("Invalid username or password");
        }
      })
      .catch(error => console.error("Error:", error));
  });
});

