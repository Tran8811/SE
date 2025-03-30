document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".form");

  const passwordInput = document.getElementById("password");
  const togglePassword = document.querySelector(".toggle-password");

  togglePassword.addEventListener("click", function () {
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  });

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Ngăn chặn load lại trang

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    fetch("/cupid-again/php/sign_in.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })
      .then(response => response.text())  // Đọc phản hồi dưới dạng văn bản
      .then(text => {
        console.log("Phản hồi từ server:", text);  // Debug

        try {
          const data = JSON.parse(text);  // Chuyển đổi thành JSON

          if (data.status === "success") {
            // 🔥 Lưu unique_id vào LocalStorage
            localStorage.setItem("unique_id", data.unique_id);
            localStorage.setItem("username", data.username);

            alert("✅ Đăng nhập thành công!");
            window.location.href = "../html/sign_up_2.html"; // Chuyển hướng
          } else {
            alert("❌ Lỗi: " + data.message);
          }
        } catch (error) {
          console.error("Lỗi phân tích JSON:", error);
          console.log("🔴 Nội dung phản hồi không phải JSON:", text);
          alert("Lỗi: Phản hồi không hợp lệ từ server.");
        }
      })
      .catch(error => {
        console.error("🔴 Lỗi kết nối:", error);
        alert("Không thể kết nối đến server!");
      });
  });
});

