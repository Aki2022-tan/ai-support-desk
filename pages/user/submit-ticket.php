<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('user');
?>

<div class="max-w-5xl mx-auto mt-10 px-4 md:grid md:grid-cols-3 md:gap-6">
  <!-- Main Form Section -->
  <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Submit a New Ticket</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 mb-4 rounded shadow-sm">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form id="ticketForm" action="../../handlers/submit-ticket-handler.php" method="POST" enctype="multipart/form-data" class="space-y-6">
<!-- Department Selection -->
<select id="department" name="department" class="border border-gray-300 px-4 py-2 w-full rounded focus:ring">
  <option value="">Select Department</option>
  <option value="Customer Service">Customer Service</option>
  <option value="Technical Support">Technical Support</option>
  <option value="Billing & Payments">Billing & Payments</option>
  <option value="Sales & Inquiries">Sales & Inquiries</option>
  <option value="Account Management">Account Management</option>
  <option value="Product Support">Product Support</option>
  <option value="Compliance & Legal">Compliance & Legal</option>
</select>

<!-- Subject Input with AI Suggestions -->
<div class="relative mt-4">
  <label for="subject" class="block text-gray-700 font-semibold mb-1">Subject</label>

  <!-- Input + Spinner Wrapper -->
  <div class="relative">
    <input type="text" id="subject" name="subject" autocomplete="off"
           class="w-full border rounded-md py-2 pl-3 pr-10 focus:ring focus:outline-none"
           required>

    <!-- Loading Spinner inside input -->
    <div id="subjectSpinner" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
      <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
  </div>

  <!-- Inline Error -->
  <div id="subjectError" class="text-sm text-red-500 mt-1 hidden">Subject is required.</div>

  <!-- AI Suggestions Dropdown -->
  <ul id="subjectSuggestions"
      class="absolute z-50 bg-white border rounded-md shadow-md mt-1 w-full hidden max-h-48 overflow-y-auto text-sm text-gray-700"></ul>
</div>

      <!-- Message -->
      <div>
        <label for="message" class="block text-gray-700 mb-1">Message</label>
        <textarea id="message" name="message" rows="6" class="w-full border rounded-md p-2 focus:ring focus:outline-none" required></textarea>
        <div id="messageError" class="text-sm text-red-500 mt-1 hidden">Message is required.</div>
      </div>

      <!-- File Upload -->
      <div>
        <label for="attachment" class="block text-gray-700 mb-1">Optional Attachment</label>
        <input type="file" id="attachment" name="attachment"
          class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border file:rounded file:text-sm file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
          accept=".jpg,.jpeg,.png,.pdf,.docx">
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
          Submit Ticket
        </button>
      </div>
<!-- Mobile-Only AI Insights (Accordion) -->
<div class="block md:hidden mt-8 space-y-4">
  <!-- AI Assistant Summary -->
  <details class="group bg-white border rounded-xl shadow-sm p-4 transition-all duration-300">
    <summary class="flex items-center justify-between cursor-pointer text-gray-800 font-medium group-open:text-blue-600">
      <span>AI Assistant Summary</span>
      <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </summary>
    <div class="mt-3 text-sm text-gray-600 space-y-2">
      <p><strong>Estimated Priority:</strong> Medium</p>
      <p><strong>Suggested Tone:</strong> Professional</p>
      <p><strong>Language Detected:</strong> English</p>
    </div>
  </details>

  <!-- Ticket Status Overview -->
  <details class="group bg-white border rounded-xl shadow-sm p-4 transition-all duration-300">
    <summary class="flex items-center justify-between cursor-pointer text-gray-800 font-medium group-open:text-blue-600">
      <span>Ticket Status Overview</span>
      <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </summary>
    <div class="mt-3 text-sm text-gray-600 space-y-2">
      <p><strong>Status:</strong> Not submitted</p>
      <p><strong>Expected Response:</strong> Within 24 hours</p>
      <p><strong>Tracking ID:</strong> Will be provided after submission</p>
    </div>
  </details>
</div>
    </form>
  </div>

  <!-- Sidebar: AI Suggestions (Placeholder) -->
  <div class="hidden md:block">
  <div class="bg-gradient-to-b from-gray-50 to-white p-4 rounded-xl shadow-sm border text-sm text-gray-600">
  <h3 class="font-medium text-gray-800 mb-2">Suggestions</h3>
  <p>Select a department to receive tailored AI suggestions about your issue.</p>
  <ul class="list-disc pl-5 mt-2 space-y-1">
    <li>Customer Service: General inquiries, service dissatisfaction</li>
    <li>Technical Support: Login issues, system bugs, connectivity</li>
    <li>Billing & Payments: Incorrect charges, invoice disputes</li>
    <li>Sales & Inquiries: Product pricing, service availability</li>
    <li>Account Management: Profile updates, account access</li>
    <li>Product Support: Defective items, usage help</li>
    <li>Compliance & Legal: Policy clarification, legal concern</li>
  </ul>
</div>
  
  <!-- Insights & Tips Panel (Below Suggestions) -->
<div class="mt-6 bg-white p-4 rounded-xl shadow-sm border text-sm text-gray-700">
  <h3 class="font-medium text-gray-800 mb-2">Ticket Writing Tips</h3>
  <ul class="list-disc pl-5 space-y-1">
    <li>Be specific: Mention exact error messages or system behaviors.</li>
    <li>Include timestamps if the issue is time-based.</li>
    <li>Attach screenshots or files that support your report.</li>
    <li>Avoid vague language (e.g., "It doesn’t work").</li>
    <li>Use a neutral, professional tone for clarity.</li>
  </ul>
</div>
<!-- AI Insights & Ticket Overview (Accordion Style) -->
<div class="mt-6 w-full space-y-4">
  <!-- AI Assistant Summary -->
  <details class="group bg-white border rounded-xl shadow-sm p-4 transition-all duration-300">
    <summary class="flex items-center justify-between cursor-pointer text-gray-800 font-medium group-open:text-blue-600">
      <span>AI Assistant Summary</span>
      <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </summary>
    <div class="mt-3 text-sm text-gray-600 space-y-2">
      <p><strong>Estimated Priority:</strong> Medium</p>
      <p><strong>Suggested Tone:</strong> Professional</p>
      <p><strong>Language Detected:</strong> English</p>
    </div>
  </details>

  <!-- Ticket Status Overview -->
  <details class="group bg-white border rounded-xl shadow-sm p-4 transition-all duration-300">
    <summary class="flex items-center justify-between cursor-pointer text-gray-800 font-medium group-open:text-blue-600">
      <span>Ticket Status Overview</span>
      <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </summary>
    <div class="mt-3 text-sm text
</div>
</div>

<script>
  // Minimal client-side validation
  document.getElementById('ticketForm').addEventListener('submit', function (e) {
    let hasError = false;

    const subject = document.getElementById('subject');
    const message = document.getElementById('message');

    if (subject.value.trim() === '') {
      document.getElementById('subjectError').classList.remove('hidden');
      hasError = true;
    } else {
      document.getElementById('subjectError').classList.add('hidden');
    }

    if (message.value.trim() === '') {
      document.getElementById('messageError').classList.remove('hidden');
      hasError = true;
    } else {
      document.getElementById('messageError').classList.add('hidden');
    }

    if (hasError) e.preventDefault();
  });
</script>


