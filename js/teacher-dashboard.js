// Teacher Dashboard JavaScript Functions

async function loadStudents(subjectId) {
  const studentSelect = document.getElementById("student_id");
  studentSelect.innerHTML = '<option value="">Loading...</option>';

  // Add hard-coded values for testing Programiranje 1
  if (subjectId == 2) {
    console.log("Hard-coding students for Programiranje 1");
    studentSelect.innerHTML = `
      <option value="">Select Student</option>
      <option value="1">Marko Marić (12345)</option>
      <option value="5">Ivan Horvat (54321)</option>
    `;
    return;
  }

  if (!subjectId) {
    studentSelect.innerHTML = '<option value="">Select Student</option>';
    return;
  }

  try {
    console.log(`Fetching students for subject ID: ${subjectId}`);

    const response = await fetch(
      `/StudentGrader/api.php?action=get_enrolled_students&subject_id=${subjectId}`
    );

    if (!response.ok) {
      console.error(`API error: ${response.status} ${response.statusText}`);
      studentSelect.innerHTML = '<option value="">API error</option>';
      return;
    }

    const responseText = await response.text();
    console.log("Raw API response:", responseText);

    let students;
    try {
      students = JSON.parse(responseText);
      console.log("Parsed API response:", students);
    } catch (parseError) {
      console.error("JSON parse error:", parseError);
      studentSelect.innerHTML =
        '<option value="">Invalid API response</option>';
      return;
    }

    studentSelect.innerHTML = '<option value="">Select Student</option>';

    if (!Array.isArray(students)) {
      console.error("API did not return an array");
      studentSelect.innerHTML = '<option value="">Invalid data format</option>';
      return;
    }

    if (students.length === 0) {
      console.log("No students found for this subject");
      studentSelect.innerHTML =
        '<option value="">No students enrolled</option>';
      return;
    }

    console.log(`Found ${students.length} enrolled students`);
    students.forEach((student) => {
      console.log("Processing student:", student);
      const option = document.createElement("option");
      option.value = student.id;
      option.textContent = `${student.ime} ${student.prezime} (${student.jbmag})`;
      studentSelect.appendChild(option);
    });
  } catch (error) {
    console.error("Error loading students:", error);
    studentSelect.innerHTML =
      '<option value="">Error loading students</option>';
  }
}
