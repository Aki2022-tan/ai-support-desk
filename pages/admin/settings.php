<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('admin');
?>

<section class="min-h-screen bg-gray-50 px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">⚙️ System Settings</h1>

  <!-- Tabs -->
  <div class="flex flex-wrap gap-3 border-b border-gray-200 pb-3 mb-8">
    <button class="tab-button active-tab" data-tab="profile">Admin Profile</button>
    <button class="tab-button" data-tab="logs">System Logs</button>
    <button class="tab-button" data-tab="api">API Keys</button>
    <button class="tab-button" data-tab="rules">Auto-Assign Rules</button>
  </div>

  <!-- Tab Content -->
  <div id="tab-content">
    
    <!-- Admin Profile -->
    <div class="tab-panel" data-tab="profile">
      <form class="bg-white p-6 rounded-xl shadow space-y-5">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">🧑 Admin Profile</h2>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
          <input type="email" name="admin_email" class="form-input w-full" placeholder="admin@example.com">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Change Password</label>
          <input type="password" name="admin_password" class="form-input w-full" placeholder="New Password">
        </div>

        <div class="flex items-center space-x-2">
          <input type="checkbox" id="2fa" class="form-checkbox">
          <label for="2fa" class="text-sm text-gray-700">Enable Two-Factor Authentication</label>
        </div>

        <div class="flex items-center space-x-2">
          <input type="checkbox" id="email_notify" class="form-checkbox">
          <label for="email_notify" class="text-sm text-gray-700">Receive Email Notifications</label>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">Save Changes</button>
      </form>
    </div>

    <!-- System Logs -->
    <div class="tab-panel hidden" data-tab="logs">
      <form class="bg-white p-6 rounded-xl shadow space-y-5">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">🧾 System Logging</h2>

        <div class="flex items-center space-x-2">
          <input type="checkbox" id="enable_logs" class="form-checkbox">
          <label for="enable_logs" class="text-sm text-gray-700">Enable activity logging (ticket updates, logins, etc.)</label>
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">Save Logging Settings</button>
      </form>
    </div>

    <!-- API Keys -->
    <div class="tab-panel hidden" data-tab="api">
      <form class="bg-white p-6 rounded-xl shadow space-y-5">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">🔐 API Keys</h2>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Cohere API Key</label>
          <input type="text" name="cohere_api_key" class="form-input w-full" placeholder="sk-...">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Cloudmersive API Key</label>
          <input type="text" name="cloudmersive_api_key" class="form-input w-full" placeholder="xxxxx-xxxx-xxxx">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">OpenAI API Key (Optional)</label>
          <input type="text" name="openai_api_key" class="form-input w-full" placeholder="sk-...">
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">Save API Keys</button>
      </form>
    </div>

    <!-- Auto-Assign Rules -->
    <div class="tab-panel hidden" data-tab="rules">
      <form class="bg-white p-6 rounded-xl shadow space-y-5">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">📩 Auto-Assign Rules</h2>

        <div class="space-y-4 rule-container">
          <div class="grid md:grid-cols-2 gap-4">
            <input type="text" name="keyword_rule[]" class="form-input" placeholder="Keyword (e.g. password)">
            <select name="assign_department[]" class="form-select">
              <option value="">Assign to...</option>
              <option>Technical Support</option>
              <option>Customer Service</option>
              <option>Billing</option>
              <option>Product Feedback</option>
            </select>
          </div>
        </div>

        <button type="button" class="add-rule text-sm bg-gray-100 px-4 py-1.5 rounded hover:bg-gray-200">+ Add Rule</button>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded mt-4">Save Rules</button>
      </form>
    </div>

  </div>
</section>

<!-- Tabs & Rule Script -->
<script>
  const tabs = document.querySelectorAll('.tab-button');
  const panels = document.querySelectorAll('.tab-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active-tab'));
      panels.forEach(p => p.classList.add('hidden'));

      tab.classList.add('active-tab');
      document.querySelector(`.tab-panel[data-tab="${tab.dataset.tab}"]`).classList.remove('hidden');
    });
  });

  // Add new rule row
  document.querySelectorAll('.add-rule').forEach(button => {
    button.addEventListener('click', () => {
      const container = button.closest('form').querySelector('.rule-container');
      const ruleRow = document.createElement('div');
      ruleRow.className = 'grid md:grid-cols-2 gap-4';
      ruleRow.innerHTML = `
        <input type="text" name="keyword_rule[]" class="form-input" placeholder="Keyword (e.g. login)">
        <select name="assign_department[]" class="form-select">
          <option value="">Assign to...</option>
          <option>Technical Support</option>
          <option>Customer Service</option>
          <option>Billing</option>
          <option>Product Feedback</option>
        </select>
      `;
      container.appendChild(ruleRow);
    });
  });
</script>

<style>
  .tab-button {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s;
  }
  .tab-button:hover {
    background-color: #f3f4f6;
  }
  .active-tab {
    color: #2563eb;
    background-color: #eff6ff;
  }
</style>

<?php include '../../includes/footer.php'; ?>