CREATE DATABASE sce_football;

use sce_football;

CREATE TABLE profils(
    profil_id INT(11) NOT NULL PRIMARY KEY,
    profil_name VARCHAR(255) NOT NULL
);

INSERT INTO profils VALUES(1, 'Administrateur');
INSERT INTO profils VALUES(2, 'Entraîneur');
INSERT INTO profils VALUES(3, 'Joueur');

CREATE TABLE users(
    user_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    lastname VARCHAR(255) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    date_birth DATE NOT NULL,
    job VARCHAR (255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    password_system TINYINT NOT NULL,
    statut TINYINT NOT NULL
);

CREATE TABLE postes(
	Poste_id INT(11) PRIMARY KEY NOT NULL,
	PosteName VARCHAR(200)
);

INSERT INTO Postes VALUES (1, 'Gardien');
INSERT INTO Postes VALUES (2, 'Défenseur Gauche');
INSERT INTO Postes VALUES (3, 'Défenseur Centre');
INSERT INTO Postes VALUES (4, 'Défenseur Droit');
INSERT INTO Postes VALUES (5, 'Milieu Défensif');
INSERT INTO Postes VALUES (6, 'Milieu Centre');
INSERT INTO Postes VALUES (7, 'Milieu Gauche');
INSERT INTO Postes VALUES (8, 'Milieu Droit');
INSERT INTO Postes VALUES (9, 'Milieu Offensif');
INSERT INTO Postes VALUES (10, 'Attaquant');

CREATE TABLE roles(
    profil_id INT(11),
    FOREIGN KEY (profil_id)REFERENCES profils(profil_id),
    user_id INT(11) NOT NULL,
    FOREIGN KEY (user_id)REFERENCES users(user_id),
    PRIMARY KEY (profil_id, user_id)
);

INSERT INTO roles VALUES(1,1);

CREATE TABLE teams(
    team_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    comment VARCHAR (255) NOT NULL,
    img VARCHAR(255) NOT NULL
);

CREATE TABLE teams_users(
    team_id INT(11),
    user_id INT(11) NOT NULL,
    FOREIGN KEY (team_id)REFERENCES teams(team_id),
    FOREIGN KEY (user_id)REFERENCES users(user_id),
    PRIMARY KEY (team_id, user_id)
);

CREATE TABLE matches(
    match_id INT(11) NOT NULL PRIMARY KEY,
    date DATE NOT NULL,
    hour TIME NOT NULL, 
    address VARCHAR(255)NOT NULL,
    ground VARCHAR(255)NOT NULL,
    score_local INT(11),
    score_opponent INT(11),
    opponent VARCHAR(255)NOT NULL,
    img_opponent VARCHAR(255) NOT NULL,
    team_id INT,
    FOREIGN KEY (team_id)REFERENCES teams(team_id)
);

CREATE TABLE convocations(
    convocation_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    player INT(11),
    FOREIGN KEY (player)REFERENCES users(user_id),
    match_id INT,
    FOREIGN KEY (match_id)REFERENCES matches(match_id),
    present TINYINT NOT NULL
);

CREATE TABLE reports(
    player INT(11),
    match_id INT(11)NOT NULL,
    timeplay TIME NOT NULL,
    goal INT(11),
    cards INT(11),
    pass INT(11),
    FOREIGN KEY (player)REFERENCES users(user_id),
    FOREIGN KEY (match_id)REFERENCES matches(match_id)
);