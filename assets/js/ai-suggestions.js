// Timer ID used for debouncing rapid input changes
let debounceTimer;

// DOM element references
const subjectInput = document.getElementById('subjectInput');       // The input field where user types subject
const suggestionList = document.getElementById('suggestionList');   // UL element to display suggestions
const loader = document.getElementById('aiLoader');                 // Loader indicator shown during API call

// In-memory cache object to store previously fetched suggestions for performance optimization
const cache = {};

// ✅ Attach event listener only if the subject input field exists in DOM
if (subjectInput) {
  subjectInput.addEventListener('input', () => {
    // ⏳ Cancel any pending debounce call
    clearTimeout(debounceTimer);

    const query = subjectInput.value.trim(); // Clean the input

    // 🧠 Skip suggestion logic if query is too short
    if (query.length < 3) {
      suggestionList.classList.add('hidden');
      return;
    }

    // 🚀 Show loader while preparing to call API
    loader.classList.remove('hidden');

    // 🕒 Debounce API request: Wait 600ms after user stops typing
    debounceTimer = setTimeout(() => {
      // ✅ Use cache if suggestion for query is already available
      if (cache[query]) {
        renderSuggestions(cache[query]);
        loader.classList.add('hidden');
        return;
      }

      // 🌐 Fetch AI-generated suggestions from backend API
      fetch(`../api/suggest-subject.php?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
          loader.classList.add('hidden');

          // ✅ Save fresh results to cache for future reuse
          cache[query] = data.suggestions;

          // 🎯 Render new suggestions
          renderSuggestions(data.suggestions);
        })
        .catch(err => {
          // ❌ Gracefully handle errors (e.g., network issues or API failure)
          console.error('AI API Error:', err);
          loader.classList.add('hidden');
          suggestionList.classList.add('hidden');
        });
    }, 600); // Delay duration before triggering API
  });

  // 🔒 Optional UX enhancement: hide suggestion list if user clicks outside the input area
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      suggestionList.classList.add('hidden');
    }
  });
}

/**
 * 🔁 Function: renderSuggestions
 * ------------------------------
 * Dynamically creates and displays a list of clickable suggestion items
 * based on AI response or cached result.
 *
 * @param {Array<string>} suggestions - Array of suggestion strings to display
 */
function renderSuggestions(suggestions) {
  // 🧹 Clear previous list
  suggestionList.innerHTML = '';

  // 💤 Hide list if no suggestions found
  if (suggestions.length === 0) {
    suggestionList.classList.add('hidden');
    return;
  }

  // 🛠️ Build and append each suggestion item
  suggestions.forEach(text => {
    const li = document.createElement('li');
    li.textContent = text;
    li.className = "px-4 py-2 hover:bg-blue-50 cursor-pointer";

    // 🖱️ On click: insert suggestion into input and hide the list
    li.addEventListener('click', () => {
      subjectInput.value = text;
      suggestionList.classList.add('hidden');
    });

    suggestionList.appendChild(li);
  });

  // 📤 Show the suggestion list container
  suggestionList.classList.remove('hidden');
}