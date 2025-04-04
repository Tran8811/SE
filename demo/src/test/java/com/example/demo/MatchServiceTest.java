//package com.example.demo;
//
//import org.junit.jupiter.api.Test;
//import org.junit.jupiter.api.extension.ExtendWith;
//import org.mockito.InjectMocks;
//import org.mockito.Mock;
//import org.mockito.junit.jupiter.MockitoExtension;
//import users.MatchService;
//import users.User;
//import users.UserRepository;
//
//import java.util.Arrays;
//import java.util.List;
//import java.util.Optional;
//import static org.junit.jupiter.api.Assertions.assertEquals;
//import static org.mockito.Mockito.when;
//
//@ExtendWith(MockitoExtension.class)
//public class MatchServiceTest {
//
//    @InjectMocks
//    private MatchService matchService; // Inject class cần test
//
//    @Mock
//    private UserRepository userRepository; // Giả lập repository
//
//    @Test
//    public void testFindMatches_MBTICompatibility() {
//        // 1️⃣ Giả lập dữ liệu test
//        User marie = new User();
//        marie.setId(1L);
//        marie.setUsername("Marie");
//        marie.setMbti("ISTJ");
//
//        User bob = new User();
//        bob.setId(2L);
//        bob.setUsername("Bob");
//        bob.setMbti("ISFJ"); // Hợp với ISTJ
//
//        User charlie = new User();
//        charlie.setId(3L);
//        charlie.setUsername("Charlie");
//        charlie.setMbti("ENTP"); // Không hợp ISTJ
//        User alice = new User(); // Hợp ISTJ
//        alice.setId(3L);
//        alice.setUsername("Alice");
//        alice.setMbti("ESFJ"); // Không hợp ISTJ
//        List<User> mockUsers = Arrays.asList(marie, bob, charlie,alice);
//
//        // 2️⃣ Định nghĩa giả lập khi gọi userRepository
//        when(userRepository.findById(1L)).thenReturn(Optional.of(marie));
//        when(userRepository.findAll()).thenReturn(mockUsers);
//
//        // 3️⃣ Gọi phương thức cần test
//        List<User> results = matchService.findMatches(1L);
//
//        // 4️⃣ Kiểm tra kết quả
//        assertEquals(3, results.size()); // Marie có 2 lựa chọn: Bob & Charlie
//        assertEquals("Bob", results.get(0).getUsername()); // Bob hợp nhất, phải đứng đầu danh sách
//
//        // 5️⃣ In kết quả ra màn hình
//        System.out.println("Danh sách gợi ý bạn bè cho Marie:");
//        results.forEach(user -> System.out.println(user.getUsername() + " - " + user.getMbti()));
//    }
//    @Test
//    public void testFindMatches_All() {
//        // 1️⃣ Tạo user giả lập
//        User marie = new User(1L, "Marie", "ISTJ", 25, 21.0285, 105.8542); // Hà Nội
//
//        User bob = new User(2L, "Bob", "ISFJ", 26, 21.0300, 105.8560); // Cách 0.2km, hợp MBTI
//        User alice = new User(3L, "Alice", "ESFJ", 24, 21.0290, 105.8550); // Cách 0.5km, hợp MBTI
//        User charlie = new User(4L, "Charlie", "ENTP", 27, 20.9950, 105.8450); // Cách xa 4km, không hợp MBTI
//        User dave = new User(5L, "Dave", "ESTJ", 29, 21.0350, 105.8590); // Cách 1.5km, hợp MBTI
//        User eve = new User(6L, "Eve", "INFJ", 23, 21.0700, 105.9000); // Cách 10km, không hợp MBTI
//
//        List<User> mockUsers = Arrays.asList(marie, bob, alice, charlie, dave, eve);
//
//        // 2️⃣ Mock dữ liệu từ repository
//        when(userRepository.findById(1L)).thenReturn(Optional.of(marie));
//        when(userRepository.findAll()).thenReturn(mockUsers);
//
//        // 3️⃣ Gọi phương thức cần test
//        List<User> results = matchService.findMatches(1L);
//
//        // 4️⃣ Kiểm tra kết quả
//        assertEquals(4, results.size()); // Chỉ có Bob, Alice, Dave đủ điều kiện (MBTI hợp + khoảng cách < 5km)
//// Af là đnag hiện tất cả mbti chỉ là xếp ko hợpở cuối
//        // Kiểm tra thứ tự sắp xếp theo độ phù hợp (Bob có điểm cao hơn vì gần hơn)
//        assertEquals("Bob", results.get(0).getUsername());
//        assertEquals("Alice", results.get(1).getUsername());
//        assertEquals("Dave", results.get(2).getUsername());
//
//        // In kết quả ra màn hình để debug
//        System.out.println("Danh sách gợi ý bạn bè cho Marie:");
//        results.forEach(user -> {
//            double distance = MatchService.calculateDistance(marie.getLatitude(), marie.getLongitude(), user.getLatitude(), user.getLongitude());
//            System.out.println(user.getUsername() + " - " + user.getMbti() + " - " + String.format("%.2f km", distance));
//        });
//    }
//
//    @Test
//    public void testFindSameMBTI() {
//        // 1️⃣ Tạo user giả lập
//        User marie = new User(1L, "Marie", "ISTJ", 25, 21.0285, 105.8542); // Hà Nội
//
//        User bob = new User(2L, "Bob", "ISTJ", 26, 21.0300, 105.8560); // Cùng MBTI
//        User alice = new User(3L, "Alice", "ISTJ", 24, 21.0290, 105.8550); // Cùng MBTI
//        User charlie = new User(4L, "Charlie", "ENTP", 27, 20.9950, 105.8450); // Không cùng MBTI
//        User dave = new User(5L, "Dave", "ISTJ", 29, 21.0350, 105.8590); // Cùng MBTI
//        User eve = new User(6L, "Eve", "INFJ", 23, 21.0700, 105.9000); // Không cùng MBTI
//
//        List<User> mockUsers = Arrays.asList(marie, bob, alice, charlie, dave, eve);
//
//        // 2️⃣ Mock dữ liệu từ repository
//        when(userRepository.findById(1L)).thenReturn(Optional.of(marie));
//        when(userRepository.findByMbti("ISTJ")).thenReturn(Arrays.asList(bob, alice, dave));
//
//        // 3️⃣ Gọi phương thức cần test
//        List<User> results = matchService.findMatches(1L);
//
//        // 4️⃣ Kiểm tra kết quả
//        assertEquals(3, results.size()); // Chỉ có Bob, Alice, Dave đủ điều kiện (cùng MBTI)
//
//        // Kiểm tra thứ tự kết quả
//        assertEquals("Bob", results.get(0).getUsername());
//        assertEquals("Alice", results.get(1).getUsername());
//        assertEquals("Dave", results.get(2).getUsername());
//
//        // In kết quả ra màn hình để debug
//        System.out.println("Danh sách gợi ý bạn bè cho Marie:");
//        results.forEach(user -> {
//            System.out.println(user.getUsername() + " - " + user.getMbti());
//        });
//    }
//
//}