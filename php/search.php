<?php
    // Получение данных из БД

    require 'configDB.php';
    $input = $_POST['form__input'];
    $input = str_replace(" ", "_", $input);

    $result = $db->prepare(
        "SELECT posts.id AS posts_id, posts.title, comments.body
        FROM posts
        JOIN comments ON posts.id = comments.post_id
        WHERE comments.body LIKE ?");
    $result->execute(['%'.$input.'%']);
    $posts = $result->fetchAll(PDO::FETCH_ASSOC);

    // заполнение массива data 
    $data = [];

    for ($i = 0; $i < count($posts); $i++) {
        $arr = [];
        $comments = [];
        array_push($data, $posts[$i]);
        array_push($comments, $posts[$i]['body']);
        for ($j = 0; $j < count($posts); $j++) {
            if ($i !== $j) {
                if ($posts[$i]['posts_id'] === $posts[$j]['posts_id']) {
                    array_push($comments, $posts[$j]['body']);
                } 
            } 
        }
        $data[$i]['body'] = $comments ;
    }

    // Удаление из массива data одинаковых массивов
    function array_unique_key($array, $key) { 
        $tmp = $key_array = array(); 
        $i = 0; 
     
        foreach($array as $k => $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $tmp[$i] = $val;  
            } 
            $i++; 
        } 

        return $tmp; 
    }

    $data = array_unique_key($data, 'posts_id'); 

    // Отправка результата 

    if (count($posts) == 0) {
        $response = [
            "status" => false,
            "message" => "*Нет записей!"
        ];
    
        echo json_encode($response);
    } else {
        $response = [
            "status" => true,
            "data" => $data
        ];
    
        echo json_encode($response);
    }
?>