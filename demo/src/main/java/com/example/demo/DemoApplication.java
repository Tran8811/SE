package com.example.demo;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.autoconfigure.domain.EntityScan;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;

import static org.springframework.boot.SpringApplication.run;

@SpringBootApplication
@EnableJpaRepositories("users")  // Quét repository
@EntityScan("users")  // Quét entity
@ComponentScan("users") // Quét toàn bộ package users
public class DemoApplication {

	public static void main(String[] args) {
		run(DemoApplication.class, args);
	}
}
