-- Create Admin Table
CREATE TABLE IF NOT EXISTS `admin` (
                                       `admin_id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(300) NOT NULL,
    PRIMARY KEY (`admin_id`)
    );
INSERT INTO admin (username, email, password) VALUES ('admin', 'admin@gmail.com', 'admin');

-- Create User Registration Table
CREATE TABLE IF NOT EXISTS `user_registration` (
                                                   `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL,
    `middle_name` VARCHAR(255),
    `last_name` VARCHAR(255) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `contact` VARCHAR(10) NOT NULL,
    `latitude` DECIMAL(9, 6),
    `longitude` DECIMAL(9, 6),
    PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--Contact Form Table
CREATE TABLE IF NOT EXISTS `contact_form` (
                                              `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- Create Bookings Table
CREATE TABLE IF NOT EXISTS `bookings` (
                                          `booking_id` INT(11) NOT NULL AUTO_INCREMENT,
    `fees` INT(11) NOT NULL,

    `status` VARCHAR(50) DEFAULT 'Pending',
    `user_id` INT(11),
    `booking_date` DATE NOT NULL,
    `duration` INT(11) NOT NULL,
    `hostel_id` INT(11),
     `citizenship_photo` VARCHAR(255) NULL,
     `issue_date` DATE NULL,
  `issue_place` VARCHAR(255) NULL;
    PRIMARY KEY (`booking_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user_registration`(`user_id`),
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`hostel_id`)
    );


-- Create Hostels Table
CREATE TABLE IF NOT EXISTS `hostels` (
                                         `hostel_id` INT(11) NOT NULL AUTO_INCREMENT,
    `hostel_name` VARCHAR(255) NOT NULL,
    `hostel_address` VARCHAR(255) NOT NULL,
    `hostel_email` VARCHAR(255),
    `hostel_contact` VARCHAR(15) NOT NULL,
    `latitude` DECIMAL(9, 6) NOT NULL,
    `longitude` DECIMAL(9, 6) NOT NULL,
    `admin_id` INT(11) NOT NULL,
    `hostel_photo` VARCHAR(255),  -- New column to store the photo path
    PRIMARY KEY (`hostel_id`),
    FOREIGN KEY (`admin_id`) REFERENCES `admin`(`admin_id`)
    );

-- Create Userlog Table
CREATE TABLE IF NOT EXISTS `userlog` (
                                         `log_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`log_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user_registration`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--Notifications Table Creation
CREATE TABLE notifications (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               user_id INT NOT NULL,
                               count INT DEFAULT 0,
                               FOREIGN KEY (user_id) REFERENCES user_registration(user_id) ON DELETE CASCADE
);


CREATE TABLE hostel_images (
                               image_id INT AUTO_INCREMENT PRIMARY KEY,
                               hostel_id INT NOT NULL,
                               image LONGBLOB NOT NULL,
                               FOREIGN KEY (hostel_id) REFERENCES hostels(hostel_id)
);
