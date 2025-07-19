// 🧠 Main logic executes once the entire DOM content has been loaded
document.addEventListener('DOMContentLoaded', () => {
  // 🔍 Get the first form on the page
  const form = document.querySelector('form');
  if (!form) return;

  // 🎯 Get the submit button within the form
  const submitBtn = form.querySelector('button[type="submit"]');
  if (!submitBtn) return;

  // 📝 Get the subject and message input fields
  const subjectInput = form.querySelector('input[name="subject"]');
  const messageInput = form.querySelector('textarea[name="message"]');

  // 📌 Save the original text of the submit button for reuse
  const originalText = submitBtn.textContent;

  // 🚫 Define a blacklist of inappropriate or offensive words to filter
  const badWords = [
    'sex', 'sexual', 'intercourse', 'nude', 'naked', 'horny', 'porn', 'puke',
    'kantot', 'jakol', 'bj', 'blowjob', 'tite', 'puki', 'burat', 'vagina', 'penis',
    'fuck', 'shit', 'bitch', 'asshole', 'puta', 'pokpok', 'panty', 'libog', 'masturbate'
  ];

  /**
   * 🚨 Check if a given text contains any bad word from the blacklist
   * @param {string} text - The user-provided text input to validate
   * @returns {boolean} - Returns true if any offensive word is found
   */
  function containsBadWords(text) {
    const lowerText = text.toLowerCase(); // Normalize to lowercase
    return badWords.some(word => lowerText.includes(word));
  }

  /**
   * ❌ Highlight input field with red border to indicate error
   * @param {HTMLElement} input - The input or textarea DOM element
   */
  function showError(input) {
    input.classList.add('border', 'border-red-500', 'ring-1', 'ring-red-300');
  }

  /**
   * ✅ Remove error styles if input becomes valid
   * @param {HTMLElement} input - The input or textarea DOM element
   */
  function clearError(input) {
    input.classList.remove('border', 'border-red-500', 'ring-1', 'ring-red-300');
  }

  /**
   * 🔁 Real-time validation function triggered on user input
   * Validates both subject and message fields for bad words
   * and disables the submit button if any are found.
   */
  function validateLive() {
    const subjectHasBad = containsBadWords(subjectInput.value);
    const messageHasBad = containsBadWords(messageInput.value);

    // Apply or clear error styles based on content
    if (subjectHasBad) showError(subjectInput);
    else clearError(subjectInput);

    if (messageHasBad) showError(messageInput);
    else clearError(messageInput);

    // 🚫 Disable submit if either field is inappropriate
    if (subjectHasBad || messageHasBad) {
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
    } else {
      submitBtn.disabled = false;
      submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
    }
  }

  // 🔌 Attach live validation event listeners to input fields
  subjectInput.addEventListener('input', validateLive);
  messageInput.addEventListener('input', validateLive);

  /**
   * 🚀 On form submission, re-validate input fields and block form submission
   * if any offensive content is detected.
   */
  form.addEventListener('submit', (e) => {
    const subjectHasBad = containsBadWords(subjectInput.value);
    const messageHasBad = containsBadWords(messageInput.value);

    if (subjectHasBad || messageHasBad) {
      // 🛑 Block form submission
      e.preventDefault();

      // ⚠️ Show warning message
      alert('🚫 Your subject or message contains inappropriate words and cannot be submitted.');

      // 🔁 Reset button text to original
      submitBtn.textContent = originalText;

      // 🔍 Re-highlight the problematic inputs
      if (subjectHasBad) showError(subjectInput);
      if (messageHasBad) showError(messageInput);
      return;
    }

    // 🟢 Valid content: show submission state on button
    submitBtn.disabled = true;
    submitBtn.textContent = '🚀 Submitting...';
    submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
  });
});