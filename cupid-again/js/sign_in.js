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

    //   fetch("/cupid-again/php/sign_in.php", {
    //     method: "POST",
    //     headers: { "Content-Type": "application/x-www-form-urlencoded" },
    //     body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    //   })
    //     .then(response => response.json())
    //     .then(data => {
    //       if (data.status === "success") {
    //         alert("Login successful");
    //         window.location.href = "../html/sign_up_2.html"; // Điều hướng sau khi đăng nhập thành công
    //       } else {
    //         alert("Invalid username or password");
    //       }
    //     })
    //     .catch(error => console.error("Error:", error));
    // });

    // fetch("/cupid-again/php/sign_in.php", {
    //   method: "POST",
    //   headers: { "Content-Type": "application/x-www-form-urlencoded" },
    //   body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    // })
    //   .then(response => response.text())  // Đọc phản hồi dưới dạng văn bản
    //   .then(text => {
    //     console.log("Phản hồi từ server:", text);  // Log nội dung phản hồi
    //     try {
    //       const data = JSON.parse(text);  // Chuyển đổi thành JSON
    //       if (data.status === "success") {
    //         alert("Login successful");
    //         window.location.href = "../html/sign_up_2.html"; // Điều hướng sau khi đăng nhập thành công
    //       } else {
    //         alert("Invalid username or password");
    //       }
    //     } catch (error) {
    //       console.error("Không thể phân tích cú pháp JSON:", error);
    //       console.log("Phản hồi không phải JSON:", text);  // In ra phản hồi nếu không thể parse JSON
    //     }
    //   })
    //   .catch(error => console.error("Error:", error));
    fetch("/cupid-again/php/sign_in.php", {
      method: "POST",
      headers: {"Content-Type": "application/x-www-form-urlencoded"},
      body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
    })
      .then(response => response.text())  // Đọc phản hồi dưới dạng văn bản
      .then(text => {
        console.log("Phản hồi từ server:", text);  // In ra phản hồi từ server
        try {
          const data = JSON.parse(text);  // Thử chuyển đổi thành JSON
          if (data.status === "success") {
            alert("Login successful");
            window.location.href = "../html/sign_up_2.html"; // Điều hướng sau khi đăng nhập thành công
          } else {
            alert("Invalid username or password");
          }
        } catch (error) {
          // Nếu không thể phân tích cú pháp JSON, in ra thông báo và phản hồi
          console.error("Không thể phân tích cú pháp JSON:", error);
          console.log("Phản hồi không phải JSON:", text);  // In ra phản hồi nếu không phải JSON
          alert("No No");  // In thông báo "No No"
        }
      })
      .catch(error => {
        console.error("Lỗi:", error);  // In ra lỗi nếu có
        alert("Có lỗi xảy ra khi gửi yêu cầu.");
      });
      });

  });

