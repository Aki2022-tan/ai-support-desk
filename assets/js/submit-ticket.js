// Get references to input field, suggestion list container, and loading spinner
const input = document.getElementById('subjectInput');
const suggestionList = document.getElementById('suggestionList');
const aiLoader = document.getElementById('aiLoader');

// Static list of predefined local suggestions for instant filtering
const localSuggestions = [
  "Login Issue",
  "Cannot reset password",
  "Payment not reflected",
  "Bug on dashboard",
  "Unable to submit ticket",
  "App crashes on open",
  "Account locked",
];

/**
 * Displays suggestion items in the dropdown list.
 * Clears any existing items, populates with new suggestions,
 * and handles click behavior for selection.
 */
function showSuggestions(suggestions) {
  suggestionList.innerHTML = ''; // Clear current suggestions

  // Hide the suggestion list if no suggestions are available
  if (suggestions.length === 0) {
    suggestionList.classList.add('hidden');
    return;
  }

  // Create and append each suggestion as a list item
  suggestions.forEach(text => {
    const li = document.createElement('li');
    li.textContent = text;
    li.className = "cursor-pointer px-4 py-2 hover:bg-blue-100 transition";
    
    // When a suggestion is clicked, populate the input and hide the list
    li.onclick = () => {
      input.value = text;
      suggestionList.classList.add('hidden');
    };

    suggestionList.appendChild(li);
  });

  // Make the suggestion list visible
  suggestionList.classList.remove('hidden');
}

// Timer holder for debounce mechanism (to prevent spamming the AI API)
let aiDebounceTimeout;

/**
 * Event listener for the subject input field.
 * Implements debounce, shows local suggestions instantly,
 * and queries AI suggestions after 500ms of user inactivity.
 */
input.addEventListener('input', () => {
  const query = input.value.trim().toLowerCase(); // Normalize input
  clearTimeout(aiDebounceTimeout); // Cancel any previous pending API call

  // If input is empty, clear suggestions and hide loader
  if (query === '') {
    showSuggestions([]);
    aiLoader.classList.add('hidden');
    return;
  }

  // Filter local suggestions that match current input
  const localMatches = localSuggestions.filter(s =>
    s.toLowerCase().includes(query)
  );
  showSuggestions(localMatches); // Display local matches immediately

  // Only proceed to call AI suggestion API if input is at least 3 characters
  if (query.length >= 3) {
    aiLoader.classList.remove('hidden'); // Show loading spinner

    // Debounced API call after 500ms
    aiDebounceTimeout = setTimeout(async () => {
      try {
        const res = await fetch('../api/ai-suggest.php?q=' + encodeURIComponent(query));
        const data = await res.json();
        const aiMatches = data.suggestions || [];

        // Merge and deduplicate local and AI suggestions
        const combined = [...new Set([...localMatches, ...aiMatches])];

        // Display the final combined suggestion list
        showSuggestions(combined);
      } catch (err) {
        console.error('AI Suggestion Error:', err); // Log any fetch errors
      } finally {
        aiLoader.classList.add('hidden'); // Always hide loader after attempt
      }
    }, 500); // Debounce delay in milliseconds
  } else {
    aiLoader.classList.add('hidden'); // Hide loader for short inputs
  }
});