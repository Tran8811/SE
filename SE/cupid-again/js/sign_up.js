document.addEventListener("DOMContentLoaded", function () {
  // Chuyển hướng sang trang đăng nhập khi bấm "Log in"
  const loginBtn = document.querySelector(".LogIn");
  if (loginBtn) {
    loginBtn.addEventListener("click", function () {
      window.location.href = "sign_in.html";
    });
  }

  const passwordField = document.querySelector(".input-password");
  const eyeIcon = document.querySelector(".toggle-password");

  if (eyeIcon && passwordField) {
    eyeIcon.addEventListener("click", function () {
      const isPassword = passwordField.type === "password";
      passwordField.type = isPassword ? "text" : "password";

      // Đổi icon giữa 👁️ và 🙈
      eyeIcon.textContent = isPassword ? "🙈" : "👁️";
    });
  }


  // Xử lý checkbox "I accept the terms and privacy policy"
  const checkboxWrapper = document.querySelector("#terms-checkbox");
  let isChecked = false;

  if (checkboxWrapper) {
    checkboxWrapper.addEventListener("click", function () {
      isChecked = !isChecked;
      checkboxWrapper.classList.toggle("checked"); // Thêm class để đổi màu
    });
  }

  // Xử lý sự kiện khi nhấn "SIGN UP"
  document.getElementById("signUpBtn").addEventListener("click", function (event) {
    event.preventDefault();

    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMessage = document.getElementById("error-message");
    let termsCheckbox = document.getElementById("terms-checkbox");

    if (username === "" || email === "" || password === "") {
      errorMessage.textContent = "Vui lòng nhập đầy đủ thông tin!";
      errorMessage.style.display = "block";
      return;
    }

    if (!termsCheckbox.checked) {
      errorMessage.textContent = "Bạn phải đồng ý với điều khoản trước khi tiếp tục!";
      errorMessage.style.display = "block";
      return;
    }

    errorMessage.style.display = "none";

    fetch("../php/sign_up.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
      .then(response => {
        if (!response.ok) {
          throw new Error("Lỗi mạng hoặc server không phản hồi đúng.");
        }
        return response.json();
      })
      .then(data => {
        if (data.status === "success") {
          alert(data.message);
          window.location.href = "sign_up_2.html";
        } else {
          errorMessage.textContent = data.message;
          errorMessage.style.display = "block";
        }
      })
      .catch(error => {
        console.error("Fetch error:", error);
        errorMessage.textContent = "Có lỗi xảy ra, vui lòng thử lại!";
        errorMessage.style.display = "block";
      });
  });
});

