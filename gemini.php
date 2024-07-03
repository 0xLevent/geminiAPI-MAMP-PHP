<?php
$api_key = "KEY";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    
    $companyInfo = "DND Yazılım, ERP ile AVM'lere kolaylık sağlayan bir yazılım firmasıdır. 

İletişim bilgileri istendiğinde, lütfen aşağıdaki formatta alt alta sıralayarak ver:

E-posta: info@www.dndyazilim.com.tr
Telefon: 0312 484 84 17
Adres: Ehlibeyt Mah. Ceyhun Atuf Kansu Cad. Üçler Plaza 126/5 Balgat
ÇANKAYA / ANKARA
Web Sitesi: https://www.dndyazilim.com.tr

Bu formatta her bilgi ayrı bir satırda olmalı ve aralarında boşluk olmamalıdır.";
    
    $prompt = "Sen bir AI asistanısın. Aşağıdaki bilgileri kullanarak soruları cevaplamalısın, ancak genel konularda da sohbet edebilirsin. Eğer soru DND Yazılım ile ilgiliyse, verilen bilgileri kullan. Değilse, genel bilgilerinle cevap ver.\n\nDND Yazılım Bilgileri:\n$companyInfo\n\nKullanıcı Sorusu: $question";

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $api_key;
    
    $data = array(
        "contents" => array(
            array(
                "parts" => array(
                    array(
                        "text" => $prompt
                    )
                )
            )
        )
    );
    
    $json_data = json_encode($data);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    
    if(curl_errno($ch)) {
        echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
    } else {
        $responseData = json_decode($response, true);
        
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $answer = $responseData['candidates'][0]['content']['parts'][0]['text'];
            echo json_encode(['answer' => $answer]);
        } else {
            echo json_encode(['error' => 'No answer found']);
        }
    }
    
    curl_close($ch);
}
?>
