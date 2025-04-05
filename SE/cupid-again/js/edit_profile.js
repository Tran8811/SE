document.addEventListener("DOMContentLoaded", function () {
  let form = document.getElementById("create-profile-form");
  let userAvatar = document.getElementById("user-avatar");
  let avatarInput = document.getElementById("avatar-input");
  let cropOk = document.getElementById("crop-ok");
  let croppedImage = ""; // Lưu ảnh base64

  // Kiểm tra nếu có userId thì lấy dữ liệu user
  fetch("../php/get_user_profile.php?action=get")
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        document.getElementById("username").value = data.user.username || "";
        document.getElementById("age").value = data.user.age || "";

        // Cập nhật radio button Gender
        if (data.user.gender) {
          let genderRadio = document.querySelector(`input[name="gender"][value="${data.user.gender}"]`);
          if (genderRadio) genderRadio.checked = true;
        }

        // Cập nhật radio button Interests
        if (data.user.interests) {
          let interestsRadio = document.querySelector(`input[name="interests"][value="${data.user.interests}"]`);
          if (interestsRadio) interestsRadio.checked = true;
        }

        // Cập nhật avatar
        document.getElementById("user-avatar").src = data.user.avatar_url || "../img/logo.png";
      } else {
        console.error("Lỗi khi lấy dữ liệu người dùng:", data.message);
      }
    })
    .catch(error => console.error("Lỗi khi fetch dữ liệu:", error));

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(form);
    formData.append("action", "update");

    let gender = document.querySelector('input[name="gender"]:checked');
    if (gender) {
      formData.append("gender", gender.value);
    }

    let interests = document.querySelector('input[name="interests"]:checked');
    if (interests) {
      formData.append("interests", interests.value);
    }

    if (croppedImage) {
      formData.append("avatar", croppedImage);
    }

    fetch("../php/edit_profile.php", {
      method: "POST",
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          alert("Cập nhật hồ sơ thành công!");
          userAvatar.src = data.avatar_url;

          // 🔹 Kiểm tra URL hiện tại để quyết định trang tiếp theo
          let currentPage = window.location.pathname.split("/").pop();
          if (currentPage === "create_profile.html") {
            window.location.href = "../html/mbti.html";
          } else if (currentPage === "edit_profile.html") {
            window.location.href = "../html/profile.html";
          }
        } else {
          alert("Lỗi: " + data.message);
        }
      })
      .catch(error => console.error("Lỗi khi gửi dữ liệu:", error));
  });

  avatarInput.addEventListener("change", function (event) {
    let file = event.target.files[0];
    if (file) {
      let reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("crop-image").src = e.target.result;
        document.getElementById("crop-modal").style.display = "flex";

        if (window.cropper) {
          window.cropper.destroy();
        }

        window.cropper = new Cropper(document.getElementById("crop-image"), {
          aspectRatio: 1,
          viewMode: 1,
          autoCropArea: 1,
          cropBoxResizable: false,
          ready() {
            // **Tạo khung tròn bằng CSS**
            document.querySelector(".cropper-view-box").style.borderRadius = "50%";
            document.querySelector(".cropper-face").style.borderRadius = "50%";
          }
        });
      };
      reader.readAsDataURL(file);
    }
  });

  cropOk.addEventListener("click", function () {
    let canvas = window.cropper.getCroppedCanvas({ width: 200, height: 200 });
    if (canvas) {
      croppedImage = canvas.toDataURL("image/png");
      userAvatar.src = croppedImage;
      document.getElementById("crop-modal").style.display = "none";
    }
  });

  document.getElementById("crop-cancel").addEventListener("click", function () {
    document.getElementById("crop-modal").style.display = "none";
  });
});
