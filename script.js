$(document).ready(function () {
  let questionCount = 0;

  // Add Question
  $("#add-question").click(function () {
    questionCount++;
    let selectedType = $("#question-type").val();

    let questionHTML = `<div class="question-block">
      <label>Question ${questionCount}</label>
      <input type="text" name="question_texts[]" placeholder="Enter your question here" required>
      <input type="hidden" name="question_types[]" value="${selectedType}">`;

    if (selectedType === "radio" || selectedType === "checkbox") {
      questionHTML += `
        <input type="text" name="options_${questionCount}[]" placeholder="Option 1" required>
        <input type="text" name="options_${questionCount}[]" placeholder="Option 2" required>
      `;
    }

    questionHTML += `</div>`;
    $("#questions-area").append(questionHTML);
  });

  // Save form
  $("#formly-form").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "save_form.php",
      method: "POST",
      data: $(this).serialize(),
      success: function (res) {
        let response = JSON.parse(res);
        if (response.status === "success") {
          alert("✅ Form saved! View at: form_view.php?id=" + response.form_id);
        } else {
          alert("❌ Failed to save form.");
        }
      },
      error: function (err) {
        console.error("AJAX error:", err);
      }
    });
  });
});


