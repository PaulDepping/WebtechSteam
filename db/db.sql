CREATE OR REPLACE DATABASE Webtech;

CREATE TABLE Webtech.Users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE Webtech.Watching (
    series_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    seasons INT NOT NULL,
    genre VARCHAR(255) NOT NULL,
    platform VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    PRIMARY KEY (series_id)
);

-- password: demo
INSERT INTO Webtech.Users (username, password_hash) 
VALUES ('demo', '$2y$10$eNx2Apk0p4VlmsWDan5d5ut9df3teNMpmp34U9C13i98L64lyuhiC');

INSERT INTO Webtech.Watching (user_id, title, seasons, genre, platform, rating)
VALUES ((SELECT id FROM Webtech.Users WHERE username='demo'), 'Suits', 9, 'Drama', 'Netflix', 4);  
