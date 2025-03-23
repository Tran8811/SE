package users;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface UserRepository extends JpaRepository<User, Long> {
    User findByUsername(String username);
    List<User> findByMbti(String mbti); // Tìm người dùng theo MBTI
    List<User> findByMbtiIn(List<String> mbtiList);

}