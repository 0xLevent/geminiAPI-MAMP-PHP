<?php
session_start();
$api_key = "Enter your api key"; // Buraya kendi Gemini API anahtarınızı ekleyin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $history = json_decode($_POST['history'], true) ?? [];

    // Yeni soru ve mevcut geçmişle API'ye istek oluşturma
    $contents = [];
    foreach ($history as $exchange) {
        $contents[] = [
            "role" => "user",
            "parts" => [["text" => $exchange['question']]]
        ];
        $contents[] = [
            "role" => "model",
            "parts" => [["text" => $exchange['answer']]]
        ];
    }
    $contents[] = [
        "role" => "user",
        "parts" => [["text" => $question]]
    ];

    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key={$api_key}";
    $data = ["contents" => $contents];

    $json_data = json_encode($data);

    // CURL ile API'ye POST isteği gönderme
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    // JSON formatında gelen cevabı parse etme ve ekrana yazdırma
    $responseData = json_decode($response, true);

    // Cevabın doğru şekilde parse edildiğinden emin olun
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        $answer = $responseData['candidates'][0]['content']['parts'][0]['text'];
    } else {
        $answer = "No answer found";
    }

    // Yeni soru ve cevabı oturumda saklama
    $_SESSION['history'][] = [
        'question' => $question,
        'answer' => $answer
    ];

    // HTML çıktısını oluşturma
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<title>Gemini Q&A</title>";
    echo "</head>";
    echo "<body>";
    echo "<h1>Question:</h1>";
    echo "<p>{$question}</p>";
    echo "<h1>Answer:</h1>";
    echo "<p>{$answer}</p>";
    echo "<h1>Ask another question</h1>";
    echo "<form action='gemini.php' method='POST'>";
    echo "<label for='question'>Your Question:</label><br>";
    echo "<input type='text' id='question' name='question' required><br><br>";
    echo "<input type='hidden' name='history' value='" . htmlspecialchars(json_encode($_SESSION['history'])) . "'><br><br>";
    echo "<button type='submit'>Get Answer</button>";
    echo "</form>";
    echo "</body>";
    echo "</html>";
}
?>
