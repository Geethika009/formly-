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
$questions = $formData['questions'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fill Form - Formly</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>📝 Fill the Form</h2>
    <form action="submit_form.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_id" value="<?php echo htmlspecialchars($formId); ?>">

        <?php foreach ($questions as $index => $q): ?>
            <div class="question-block">
                <label><?php echo htmlspecialchars($q['text']); ?></label><br>

                <?php
                $name = "answers[$index]";
                switch ($q['type']) {
                    case 'text':
                        echo "<input type='text' name='$name' required>";
                        break;

                    case 'textarea':
                        echo "<textarea name='$name' required></textarea>";
                        break;

                    case 'radio':
                        foreach ($q['options'] as $opt) {
                            echo "<label><input type='radio' name='$name' value='" . htmlspecialchars($opt) . "' required> " . htmlspecialchars($opt) . "</label><br>";
                        }
                        break;

                    case 'checkbox':
                        foreach ($q['options'] as $opt) {
                            echo "<label><input type='checkbox' name='{$name}[]' value='" . htmlspecialchars($opt) . "'> " . htmlspecialchars($opt) . "</label><br>";
                        }
                        break;

                    case 'file':
                        echo "<input type='file' name='$name'>";
                        break;

                    default:
                        echo "<input type='text' name='$name'>";
                        break;
                }
                ?>
            </div>
        <?php endforeach; ?>

        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>

