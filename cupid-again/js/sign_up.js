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
    event.preventDefault();

    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMessage = document.getElementById("error-message");
    let termsCheckbox = document.getElementById("terms-checkbox");

    if (username === "" || password === "") {
      errorMessage.textContent = "Vui lòng nhập đầy đủ thông tin!";
      errorMessage.style.display = "block";
    } else if (!termsCheckbox.checked) {
      errorMessage.textContent = "Bạn phải đồng ý với điều khoản trước khi tiếp tục!";
      errorMessage.style.display = "block";
    } else {
      errorMessage.style.display = "none";

      // Gửi dữ liệu đến PHP
      //   fetch("../php/sign_up.php", {
      //     method: "POST",
      //     headers: { "Content-Type": "application/x-www-form-urlencoded" },
      //     body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
      //   })
      //     .then(response => response.json())
      //     .then(data => {
      //       if (data.status === "success") {
      //         alert(data.message);
      //         window.location.href = "sign_in.html";
      //       } else {
      //         errorMessage.textContent = data.message;
      //         errorMessage.style.display = "block";
      //       }
      //     })
      //     .catch(error => console.error("Lỗi:", error));
      // }
      fetch("/cupid-again/php/sign_up.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
      })
        .then(response => response.text())  // Chuyển sang nhận dưới dạng văn bản
        .then(data => {
          console.log(data);  // In ra dữ liệu để kiểm tra
          try {
            const jsonData = JSON.parse(data);  // Thử phân tích JSON
            if (jsonData.status === "success") {
              alert(jsonData.message);
              window.location.href = "sign_in.html";
            } else {
              errorMessage.textContent = jsonData.message;
              errorMessage.style.display = "block";
            }
          } catch (error) {
            console.error("Lỗi khi phân tích JSON:", error);
          }
        })
        .catch(error => console.error("Lỗi:", error));
    }
    });


  // Xử lý sự kiện đăng nhập bằng Google, Facebook, Apple
  const googleBtn = document.querySelector(".google");
  const facebookBtn = document.querySelector(".facebook");
  const appleBtn = document.querySelector(".apple");
// Đăng nhập bằng Google
  const CLIENT_ID = "986451075642-kd1t4q7ke1fhkac5fmielv3vk2mv3naq.apps.googleusercontent.com"; // Thay bằng Client ID từ Google Cloud

  google.accounts.id.initialize({
    client_id: CLIENT_ID,
    callback: handleGoogleSignIn
  });

  google.accounts.id.renderButton(
    document.getElementById("google-login-button"),
    { theme: "outline", size: "large" }
  );

  function handleGoogleSignIn(response) {
    const credential = response.credential;

    fetch("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" + credential)
      .then(res => res.json())
      .then(user => {
        fetch("../php/google_login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            email: user.email,
            name: user.name,
            picture: user.picture
          })
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === "success") {
              alert("Đăng nhập thành công!");
              window.location.href = "sign_up_2.html";
            } else {
              alert("Lỗi đăng nhập!");
            }
          })
          .catch(error => console.error("Lỗi:", error));
      })
      .catch(error => console.error("Lỗi khi lấy thông tin user:", error));
  }
});

