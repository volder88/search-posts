<?php
    // Создание и запись в файлы json
    $url = [
        "posts" => "https://jsonplaceholder.typicode.com/posts",
        "comments" => "https://jsonplaceholder.typicode.com/comments"
    ];

    foreach ($url as $key => $value) {
        $ch = curl_init($value);
        $fp = fopen("../$key.json", "w");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        if(curl_error($ch)) {
            fwrite($fp, curl_error($ch));
        }
        curl_close($ch);
        fclose($fp);
    }

    //Добавление данных в БД
    require 'configDB.php';
    $countPosts = 0;
    $countComments = 0;

    foreach ($url as $key => $value) {
        $filename = "../$key.json";
        if (file_exists($filename)) {
            $data = file_get_contents($filename);
            $data = json_decode($data, true);
            if ($key === "posts") {
                foreach ($data as $key => $value) {
                    $id = $value['id'];
                    $user_id = $value['userId'];
                    $title = $value['title'];
                    $body = $value['body'];
                    $result = $db->prepare(
                        "INSERT INTO posts (id, user_id, title, body) VALUES (:id, :user_id, :title, :body)"); 
                    
                    $result->execute([':id' => $id, ':user_id' => $user_id, ':title' => $title, ':body' => $body]);
                    $countPosts++;
                }
            } else if ($key === "comments") {
                foreach ($data as $key => $value) {
                    $id = $value['id'];
                    $post_id = $value['postId'];
                    $name = $value['name'];
                    $email = $value['email'];
                    $body = $value['body'];
                    $result = $db->prepare(
                        "INSERT INTO comments (id, post_id, name, email, body) VALUES (:id, :post_id, :name, :email, :body)"); 
                    
                    $result->execute([':id' => $id, ':post_id' => $post_id, ':name' => $name, ':email' => $email, ':body' => $body]);
                    $countComments++;
                }
            }
            
        } else {
            echo $key.'json не найден'.'<br>';
        }
    }

    echo 'Загружено:<br>'.$countPosts.' записей<br>'.$countComments.' комментаривев';

    
?>