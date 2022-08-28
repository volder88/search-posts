<?php 
    // Создание БД
    $createDatabase = 
    'CREATE DATABASE `posts`';

    // Создание таблицы posts
    $createTablePosts = 
    'CREATE TABLE posts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        title TEXT,
        body TEXT
    )'


    // Создание таблицы comments
    $createTableComments = 
    '   CREATE TABLE comments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        post_id INT,
        name TEXT,
        email VARCHAR(50),
        body TEXT,
        FOREIGN KEY(post_id) REFERENCES posts(id)
    )'
?>
