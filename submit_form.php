<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formId = $_POST['form_id'];
    $answers = [];

    if (!is_dir("responses")) {
        mkdir("responses", 0777, true);
    }
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    foreach ($_POST['answers'] as $index => $answer) {
        // If it's an array (checkbox), store all selected
        if (is_array($answer)) {
            $answers[$index] = $answer;
        } else {
            $answers[$index] = htmlspecialchars($answer);
        }
    }

    // Handle file uploads
    foreach ($_FILES['answers']['name'] as $index => $fileName) {
        if (!empty($fileName)) {
            $tempPath = $_FILES['answers']['tmp_name'][$index];
            $targetPath = "uploads/" . uniqid("file_") . "_" . basename($fileName);

            if (move_uploaded_file($tempPath, $targetPath)) {
                $answers[$index] = $targetPath;
            } else {
                $answers[$index] = "Upload failed";
            }
        }
    }

    $responseId = uniqid("response_");
    $filePath = "responses/{$responseId}.json";

    $responseData = [
        "form_id" => $formId,
        "submitted_at" => date("Y-m-d H:i:s"),
        "answers" => $answers
    ];

    if (file_put_contents($filePath, json_encode($responseData, JSON_PRETTY_PRINT))) {
        echo "<h2>✅ Thank you! Your response has been recorded.</h2>";
    } else {
        echo "<h2>❌ Failed to save your response. Please try again.</h2>";
    }
} else {
    echo "<h2>Invalid request.</h2>";
}
?>
