<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Gemini Q&A</title>
</head>
<body>
    <h1>Ask a Question</h1>
    <form action="gemini.php" method="POST">
        <label for="question">Your Question:</label><br>
        <input type="text" id="question" name="question" required><br><br>
        <input type="hidden" name="history" value="<?php echo htmlspecialchars(json_encode($_SESSION['history'] ?? [])); ?>"><br><br>
        <button type="submit">Get Answer</button>
    </form>
    <?php
    if (isset($_SESSION['history'])) {
        foreach ($_SESSION['history'] as $exchange) {
            echo "<p><strong>You:</strong> {$exchange['question']}</p>";
            echo "<p><strong>Gemini:</strong> {$exchange['answer']}</p>";
        }
    }
    ?>
</body>
</html>
