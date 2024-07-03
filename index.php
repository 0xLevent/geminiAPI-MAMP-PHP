<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Levent KINACI">
    <title>DND YAZILIM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #bdc3c7 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .typewriter-text {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            margin: 0;
            letter-spacing: 0.15em;
            animation: typing 3.5s steps(40, end);
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }
        .container {
            background-color: #f5f5f5;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
            transition: transform 0.9s ease;
            display: flex;
            flex-direction: column;
        }
        .container:hover {
            transform: scale(1.02);
        }
        .input-group {
            margin-top: 30px;
            display: flex;
            align-items: center;
        }
        .input-group input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            margin-right: 10px;
        }
        .input-group button {
            padding: 10px 20px;
            border: none;
            background-color: #34495e;
            color: #ffffff;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .input-group button:hover {
            background-color: #1abc9c;
        }
        .response {
            transition: opacity 0.8s ease;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: left;
            min-height: 40px;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #logo {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 100px;
            height: auto;
            z-index: 1000;
            opacity: 0.8;
        }
        #logo img {
            width: 100%;
            height: auto;
            filter: brightness(0) invert(1);
        }
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .custom-loader {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
    <div id="logo"></div>
    <div class="container">
        <div class="response" id="responseContainer"></div>
        <div class="loading" id="loadingAnimation" style="display: none;">
            <img src="animation4.gif" alt="Loading..." class="custom-loader">
        </div>
        <div class="input-group">
            <input type="text" id="question" name="question" required placeholder="Firmamiz ile ilgili bilgi almak icin soru sorabilirsiniz.">
            <button type="button" onclick="getAnswer()">Soru sor</button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function getAnswer() {
                console.log("getAnswer fonksiyonu çağrıldı");
                var questionInput = document.getElementById('question');
                var question = questionInput.value;
                var xhr = new XMLHttpRequest();
                var loadingAnimation = document.getElementById('loadingAnimation');
                var responseContainer = document.getElementById('responseContainer');

                loadingAnimation.style.display = 'flex';
                responseContainer.style.display = 'none';

                xhr.open("POST", "gemini.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        loadingAnimation.style.display = 'none';

                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);

                            if (response.answer && response.answer !== "No answer found") {
                                responseContainer.style.display = 'flex';
                                typeWriter(response.answer, responseContainer);
                            } else {
                                responseContainer.textContent = "No answer found";
                                responseContainer.style.display = 'flex';
                            }

                            questionInput.value = '';
                        } else {
                            console.error("AJAX isteği başarısız oldu");
                            responseContainer.textContent = "Bir hata oluştu. Lütfen tekrar deneyin.";
                            responseContainer.style.display = 'flex';
                        }
                    }
                };
                xhr.onerror = function() {
                    console.error("AJAX isteği başarısız oldu");
                    loadingAnimation.style.display = 'none';
                    responseContainer.textContent = "Bir hata oluştu. Lütfen tekrar deneyin.";
                    responseContainer.style.display = 'flex';
                };
                xhr.send("question=" + encodeURIComponent(question));
            }

            function typeWriter(text, element) {
                element.innerHTML = '';
                element.className = 'typewriter-text';
                var i = 0;
                var speed = 50; 

                function type() {
                    if (i < text.length) {
                        if (text.charAt(i) === '\n') {
                            element.innerHTML += '<br>';
                        } else {
                            element.innerHTML += text.charAt(i);
                        }
                        i++;
                        setTimeout(type, speed);
                    } else {
                        element.className = ''; 
                    }
                }

                type();
            }

            window.getAnswer = getAnswer;

            document.getElementById('responseContainer').style.display = 'none';
        });

        window.onload = function() {
            var logoDiv = document.getElementById('logo');
            var img = document.createElement('img');
            img.src = 'son.png'; 
            img.alt = 'DND Yazılım Logo';
            logoDiv.appendChild(img);
        }
    </script>
</body>
</html>
