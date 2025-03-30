document.addEventListener("DOMContentLoaded", function () {
  let userName = document.getElementById("user-name");
  let userAge = document.getElementById("user-age");
  let userMbti = document.getElementById("user-mbti");
  let userAvatar = document.getElementById("user-avatar");
  let logoutBtn = document.getElementById("logout-btn");

  // 🚀 Gọi API lấy dữ liệu người dùng
  fetch("../php/get_user_profile.php?action=get")
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        console.log("Dữ liệu từ server:", data); // Debug xem có đúng không

        // 📝 Cập nhật thông tin user
        userName.textContent = data.user.username || "Unknown";
        userAge.textContent = data.user.age || "N/A";
        userMbti.textContent = data.user.mbti || "N/A";
        userAvatar.src = data.user.avatar_url || "../img/logo.png";

        // 🎨 Thêm class màu MBTI sau khi có dữ liệu
        if (data.user.mbti) {
          let mbtiClass = "mbti-" + data.user.mbti.toUpperCase();
          userAvatar.classList.add(mbtiClass);
        }
      } else {
        console.error("Lỗi: " + data.message);
      }
    })
    .catch(error => console.error("Lỗi khi fetch dữ liệu:", error));

  // 🔒 Xử lý logout
  logoutBtn.addEventListener("click", function () {
    if (confirm("Bạn có chắc chắn muốn đăng xuất?")) {
      // Xóa thông tin đăng nhập khỏi localStorage (hoặc sessionStorage)
      localStorage.removeItem("userId");
      localStorage.removeItem("userToken");

      // Chuyển hướng về trang start.html
      window.location.href = "start.html";
    }
  });
});
