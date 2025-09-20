<?php

function count_students(){
    global $conn;
 
    $students = $conn->query("SELECT COUNT(*) AS total_students FROM students WHERE deleted_at IS NULL");

    $allStudents = 0;


    if($students){
        $row=$students->fetch_assoc();
        $allStudents = $row['total_students'];
    }
    

    return [
        'total_students' => $allStudents
    ];
}

function notification(){
    
}

