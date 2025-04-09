//package users;
//
//import org.springframework.beans.factory.annotation.Autowired;
//import org.springframework.web.bind.annotation.*;
//
//@RestController
//@RequestMapping("/users")
//public class UserController {
//
//    @Autowired
//    private UserRepository userRepository;
//
//    // API lấy thông tin user theo id
//    @GetMapping("/{id}")
//    public User getUserById(@PathVariable("id") Long id) {
//        return userRepository.findById(id)
//                .orElseThrow(() -> new RuntimeException("User not found"));
//    }
//}