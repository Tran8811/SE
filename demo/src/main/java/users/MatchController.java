package users;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;
import java.util.List;

@CrossOrigin(origins = "*")  // Cho phép truy cập từ mọi nguồn
@RestController
@RequestMapping("/users")
public class MatchController {

    @Autowired
    private MatchService matchService;

    @GetMapping("/findNearby")
    public List<User> findNearby(@RequestParam double lat, @RequestParam double lon, @RequestParam int radius) {
        return matchService.findNearby(lat, lon, radius);
    }

    @GetMapping("/findMatches")
    public List<User> findMatches(@RequestParam Long id) {
        System.out.println("Received request for findMatches with ID: " + id);
        return matchService.findMatches(id);
    }
}
