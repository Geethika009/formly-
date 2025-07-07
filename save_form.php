<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $questions = $_POST['question_texts'];
    $types = $_POST['question_types'];

    $formData = [];

    foreach ($questions as $index => $qText) {
        $qType = $types[$index];
        $question = [
            "text" => $qText,
            "type" => $qType
        ];

        // Handle options if question is radio or checkbox
        if ($qType === "radio" || $qType === "checkbox") {
            $optKey = "options_" . ($index + 1); // +1 because questionCount starts from 1
            if (isset($_POST[$optKey])) {
                $question["options"] = $_POST[$optKey];
            }
        }

        $formData[] = $question;
    }

    $formId = uniqid("form_");
    $filePath = "forms/$formId.json";

    if (!is_dir("forms")) {
        mkdir("forms", 0777, true);
    }

    if (file_put_contents($filePath, json_encode(["questions" => $formData], JSON_PRETTY_PRINT))) {
        echo json_encode(["status" => "success", "form_id" => $formId]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>

