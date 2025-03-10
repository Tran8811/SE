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
  document.getElementById("signUpBtn").addEventListener("click", function(event) {
    event.preventDefault(); // Ngăn form gửi đi ngay lập tức

    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMessage = document.getElementById("error-message");
    let termsCheckbox = document.getElementById("terms-checkbox"); // Checkbox điều khoản

    if (username === "" || password === "") {
      showCustomAlert("Vui lòng nhập đầy đủ thông tin!", "error");
    } else if (!termsCheckbox.classList.contains("checked")) {
      showCustomAlert("Bạn phải đồng ý với điều khoản trước khi tiếp tục!", "error");
    } else {
      showCustomAlert("Đăng ký thành công!", "success");
      setTimeout(() => {
        window.location.href = "sign_up_2.html"; // Chuyển trang sau 1.5s
      }, 1500);
    }
  });





  // Xử lý sự kiện đăng nhập bằng Google, Facebook, Apple
  const googleBtn = document.querySelector(".google");
  const facebookBtn = document.querySelector(".facebook");
  const appleBtn = document.querySelector(".apple");

  if (googleBtn) {
    googleBtn.addEventListener("click", function () {
      alert("Đăng nhập bằng Google!");
    });
  }

  if (facebookBtn) {
    facebookBtn.addEventListener("click", function () {
      alert("Đăng nhập bằng Facebook!");
    });
  }

  if (appleBtn) {
    appleBtn.addEventListener("click", function () {
      alert("Đăng nhập bằng Apple!");
    });
  }
});


