<?php

$curl = curl_init();

$host = "localhost";
$user = "root";
$pass = "";
$db   ="CSE_PSTU";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn -> connect_error) 
{
	die($conn -> error);
}
else
{
	//echo "database connected";
}

// Fetch data from the database
$query = "SELECT * FROM notice"; // Modify the query based on your database schema
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Input data for the POST request
    $templateId = $row['id'];
    $name = $row['title'];
    $dueDate = $row['blog'];

    $postData = [
        'template' => [
            'id' => $templateId,
            'data' => [
                'Name' => $name,
                'DueDate' => $dueDate,
            ],
        ],
        'format' => 'pdf',
        'output' => 'url',
        'name' => 'Certificate Example',
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://us1.pdfgeneratorapi.com/api/v4/documents/generate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer your_api_token",
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
} else {
    echo "No data found in the database.";
}

// Close database connection
$conn->close();

// Close cURL session
curl_close($curl);
?>
