document.addEventListener('DOMContentLoaded', () => {
  const departmentInput = document.getElementById('department');
  const subjectInput = document.getElementById('subject');
  const suggestionsList = document.getElementById('subjectSuggestions');
  const spinner = document.getElementById('subjectSpinner');

  let debounceTimeout;
  let suggestionSelected = false; // 🔹 Flag to stop repeated fetch after selection

  subjectInput.addEventListener('input', () => {
    const subject = subjectInput.value.trim();
    const department = departmentInput.value.trim();

    // 🔹 Reset flag because user is editing again
    suggestionSelected = false;

    if (subject.length < 3 || department === '') {
      suggestionsList.classList.add('hidden');
      spinner.classList.add('hidden');
      return;
    }

    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
      if (suggestionSelected) return; // ✅ Prevent new fetch if already selected

      spinner.classList.remove('hidden');

      fetch('/ai-support-desk/api/ai-suggest-subject.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ subject, department })
      })
      .then(response => response.json())
      .then(data => {
        spinner.classList.add('hidden');
        suggestionsList.innerHTML = '';

        if (Array.isArray(data.suggestions)) {
          data.suggestions.forEach(suggestion => {
            const li = document.createElement('li');
            li.textContent = suggestion;
            li.className = 'cursor-pointer px-3 py-2 hover:bg-blue-100';
            li.addEventListener('click', () => {
              subjectInput.value = suggestion;
              suggestionsList.classList.add('hidden');
              suggestionSelected = true; // ✅ Mark as selected
            });
            suggestionsList.appendChild(li);
          });
          suggestionsList.classList.remove('hidden');
        } else {
          suggestionsList.classList.add('hidden');
        }
      })
      .catch(error => {
        console.error('Error fetching suggestions:', error);
        spinner.classList.add('hidden');
        suggestionsList.classList.add('hidden');
      });
    }, 400);
  });

  document.addEventListener('click', (e) => {
    if (!suggestionsList.contains(e.target) && e.target !== subjectInput) {
      suggestionsList.classList.add('hidden');
    }
  });
});