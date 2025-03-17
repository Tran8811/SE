package users;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class HomeController {

    @GetMapping("/")
    public String home() {
        return "home";  // Trả về view 'home.html' nếu người dùng đã đăng nhập
    }

    @GetMapping("/public")
    public String publicPage() {
        return "public";  // Trả về view 'public.html' cho các trang không bảo vệ
    }

    @GetMapping("/login")
    public String login() {
        return "login";  // Trả về view login.html khi người dùng chưa đăng nhập
    }
}