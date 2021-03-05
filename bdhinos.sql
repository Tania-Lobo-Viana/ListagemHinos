CREATE DATABASE bdhinos DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;</span></pre>

USE bdhinos;

CREATE TABLE hinos (
    id INT NOT NULL AUTO_INCREMENT,
    nomeHino BLOB NOT NULL,
    cantor BLOB NOT NULL,
    pasta VARCHAR(9) NOT NULL,
	qtdeCopias INT NOT NULL,
    PRIMARY KEY(id)
);

SELECT * FROM hinos;