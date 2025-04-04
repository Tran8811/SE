package users;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.*;
import java.util.stream.Collectors;

@Service
public class MatchService {

    @Autowired
    private UserRepository userRepository;

    public List<User> findMatches(Long userId) {
        System.out.println("🔍 API findMatches được gọi với ID: " + userId);

        User user = userRepository.findById(userId).orElse(null);
        if (user == null) {
            System.out.println("⚠️ Không tìm thấy user với ID: " + userId);
            return List.of();
        }

        Map<String, List<String>> compatibilityChart = new HashMap<>();
        compatibilityChart.put("INTJ", List.of("ENFP", "ENTJ", "INFJ"));
        compatibilityChart.put("INTP", List.of("ENTJ", "ENTP", "INFP"));
        compatibilityChart.put("ENTJ", List.of("INTP", "INTJ", "ENFP"));
        compatibilityChart.put("ENTP", List.of("INFJ", "INFP", "ENTJ"));
        compatibilityChart.put("INFJ", List.of("ENTP", "ENFP", "INTJ"));
        compatibilityChart.put("INFP", List.of("ENFP", "INFJ", "ENTP"));
        compatibilityChart.put("ENFJ", List.of("INFP", "INFJ", "ENTP"));
        compatibilityChart.put("ENFP", List.of("INFJ", "INTJ", "INFP"));
        compatibilityChart.put("ISTJ", List.of("ESTP", "ISFJ", "ENTJ"));
        compatibilityChart.put("ISFJ", List.of("ESFJ", "ISTJ", "ENFP"));
        compatibilityChart.put("ESTJ", List.of("ISTJ", "ESTP", "ESFJ"));
        compatibilityChart.put("ESFJ", List.of("ISFJ", "ENFJ", "ESTP"));
        compatibilityChart.put("ISTP", List.of("ESTP", "INTP", "ENTJ"));
        compatibilityChart.put("ISFP", List.of("ESFP", "INFJ", "ENFP"));
        compatibilityChart.put("ESTP", List.of("ISTP", "ESFP", "ENTJ"));
        compatibilityChart.put("ESFP", List.of("ISFP", "ESTP", "ENFP"));


        // MBTI của user hiện tại
        String userMbti = user.getMbti();

        // Lấy danh sách MBTI phù hợp
        List<String> compatibleMbtiList = compatibilityChart.getOrDefault(userMbti, List.of());

        // Tìm danh sách user có MBTI thuộc danh sách trên
        return userRepository.findByMbtiIn(compatibleMbtiList).stream()
                .filter(u -> !u.getId().equals(userId)) // Loại bỏ chính user đó
                .collect(Collectors.toList());
    }

    public List<User> findMatches(Long userId, Integer minAge, Integer maxAge, String location) {
        System.out.println("🔍 API findMatches được lọc thêm nữa: " + userId);

        User user = userRepository.findById(userId).orElse(null);
        if (user == null) {
            System.out.println("⚠DEBUG: Không tìm thấy user với ID: " + userId);
            return List.of();
        }

        Map<String, List<String>> compatibilityChart = new HashMap<>();
        compatibilityChart.put("INTJ", List.of("ENFP", "ENTJ", "INFJ"));
        compatibilityChart.put("INTP", List.of("ENTJ", "ENTP", "INFP"));
        compatibilityChart.put("ENTJ", List.of("INTP", "INTJ", "ENFP"));
        compatibilityChart.put("ENTP", List.of("INFJ", "INFP", "ENTJ"));
        compatibilityChart.put("INFJ", List.of("ENTP", "ENFP", "INTJ"));
        compatibilityChart.put("INFP", List.of("ENFP", "INFJ", "ENTP"));
        compatibilityChart.put("ENFJ", List.of("INFP", "INFJ", "ENTP"));
        compatibilityChart.put("ENFP", List.of("INFJ", "INTJ", "INFP"));
        compatibilityChart.put("ISTJ", List.of("ESTP", "ISFJ", "ENTJ"));
        compatibilityChart.put("ISFJ", List.of("ESFJ", "ISTJ", "ENFP"));
        compatibilityChart.put("ESTJ", List.of("ISTJ", "ESTP", "ESFJ"));
        compatibilityChart.put("ESFJ", List.of("ISFJ", "ENFJ", "ESTP"));
        compatibilityChart.put("ISTP", List.of("ESTP", "INTP", "ENTJ"));
        compatibilityChart.put("ISFP", List.of("ESFP", "INFJ", "ENFP"));
        compatibilityChart.put("ESTP", List.of("ISTP", "ESFP", "ENTJ"));
        compatibilityChart.put("ESFP", List.of("ISFP", "ESTP", "ENFP"));

        // Lọc theo MBTI
        List<String> compatibleMbtiList = compatibilityChart.getOrDefault(user.getMbti(), List.of());
        List<User> matchedUsers = userRepository.findByMbtiIn(compatibleMbtiList);

        // Lọc theo tuổi nếu có yêu cầu
//        if (minAge != null && maxAge != null) {
//            matchedUsers = matchedUsers.stream()
//                    .filter(u -> u.getAge() >= minAge && u.getAge() <= maxAge)
//                    .collect(Collectors.toList());
//        }

        // Lọc theo tuổi nếu có yêu cầu
        if (minAge != null) {
            matchedUsers = matchedUsers.stream()
                    .filter(u -> u.getAge() >= minAge)
                    .collect(Collectors.toList());
        }

        if (maxAge != null) {
            matchedUsers = matchedUsers.stream()
                    .filter(u -> u.getAge() <= maxAge)
                    .collect(Collectors.toList());
        }
        // Lọc theo location nếu có yêu cầu
        if (location != null) {
            matchedUsers = matchedUsers.stream()
                    .filter(u -> location.equalsIgnoreCase(u.getLocation()))
                    .collect(Collectors.toList());
        }

        return matchedUsers;
    }


    // 📌 Class hỗ trợ lưu thông tin match
    private static class UserMatch {
        private final User user;
        private final double compatibilityScore;

        public UserMatch(User user, double compatibilityScore) {
            this.user = user;
            this.compatibilityScore = compatibilityScore;
        }

        public User getUser() {
            return user;
        }

        public double getCompatibilityScore() {
            return compatibilityScore;
        }
    }
}
