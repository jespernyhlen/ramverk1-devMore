
--
-- Table Users
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `username` VARCHAR(40) UNIQUE NOT NULL,
    `name` VARCHAR(80) NOT NULL,
    `email` VARCHAR(30) UNIQUE NOT NULL,
    `gravatar` varchar(255) DEFAULT NULL,
    `presentation` VARCHAR(200),
    `password` VARCHAR(40) NOT NULL,
    `posts` INTEGER NOT NULL DEFAULT 0,
    `answers` INTEGER NOT NULL DEFAULT 0,
    `comments` INTEGER NOT NULL DEFAULT 0,
    `votes` INTEGER NOT NULL DEFAULT 0,
    `score` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Questions
--
DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Comment
--
DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `question_id` INTEGER NOT NULL,
    `message` TEXT NOT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `accepted` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Comment
--
DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `question_id` INTEGER NOT NULL,
    `answer_id` INTEGER NOT NULL,
    `message` TEXT NOT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT NULL,
    `deleted` DATETIME DEFAULT NULL
);


--
-- Table Tags
--
DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `tag` VARCHAR(50) NOT NULL,
    `question_id` INTEGER NOT NULL
);


--
-- Table Tags
--
DROP TABLE IF EXISTS Points;
CREATE TABLE Points (
    `id` INTEGER PRIMARY KEY NOT NULL,
    `point_for_id` INTEGER NOT NULL,
    `point_for_type` VARCHAR(40) NOT NULL,
    `username` VARCHAR(40) NOT NULL
);
