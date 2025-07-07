<?php
if (!isset($_GET['id'])) {
    die("Form ID not provided.");
}

$formId = $_GET['id'];
$formFile = "forms/$formId.json";

if (!file_exists($formFile)) {
    die("Form not found.");
}

$formData = json_decode(file_get_contents($formFile), true);
$questionList = $formData['questions'];

// Collect all responses for this form
$responseDir = "responses/";
$responseFiles = glob($responseDir . "*.json");

$responses = [];

foreach ($responseFiles as $file) {
    $data = json_decode(file_get_contents($file), true);
    if ($data['form_id'] === $formId) {
        $responses[] = $data['answers'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Responses - Formly</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>📋 Responses for Form: <?php echo htmlspecialchars($formId); ?></h2>

    <?php if (count($responses) === 0): ?>
        <p>No responses submitted yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" style="background-color: #fff; border-collapse: collapse;">
            <thead>
                <tr>
                    <?php foreach ($questionList as $question): ?>
                        <th><?php echo htmlspecialchars($question); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($responses as $answerSet): ?>
                    <tr>
                        <?php foreach ($answerSet as $ans): ?>
                            <td>
        <?php
        if (is_array($ans)) {
            echo implode(", ", array_map('htmlspecialchars', $ans));
        } elseif (is_string($ans) && str_starts_with($ans, "uploads/")) {
            $filename = basename($ans);
            echo "<a href='$ans' target='_blank'>📎 $filename</a>";
        } else {
            echo htmlspecialchars($ans);
        }
        ?>
    </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
