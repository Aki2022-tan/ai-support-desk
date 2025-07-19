document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  if (!form) return;

  const submitBtn = form.querySelector('button[type="submit"]');
  if (!submitBtn) return;

  form.addEventListener('submit', () => {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
  });
});