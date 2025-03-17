package users;

import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;

@Service
public class ApiService {

    public String callApi() {
        RestTemplate restTemplate = new RestTemplate();
        String url = "http://localhost:8081/match/findMatches?id=1";
        return restTemplate.getForObject(url, String.class);
    }
}
