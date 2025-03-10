document.addEventListener("DOMContentLoaded", function () {
  const signInButton = document.querySelector(".sign-in-button");
  const signUpLink = document.querySelector(".signup-link");

  // Xử lý đăng nhập
  signInButton.addEventListener("click", function (event) {
    event.preventDefault(); // Ngăn form submit mặc định

    const username = document.querySelector("#username").value.trim();
    const password = document.querySelector("#password").value.trim();

    if (username === "" || password === "") {
      showCustomAlert("Vui lòng nhập đầy đủ thông tin đăng nhập.");
    } else {
      showCustomAlert(`Đăng nhập với tài khoản: ${username}`);
      // Thêm logic đăng nhập nếu cần
    }
  });

  // Xử lý khi bấm Sign Up → chuyển hướng sang sign_up.html
  signUpLink.addEventListener("click", function (event) {
    event.preventDefault(); // Ngăn chặn hành động mặc định của thẻ <a>
    window.location.href = "/cupid/src/sign_up.html"; // Chuyển trang
  });
});
