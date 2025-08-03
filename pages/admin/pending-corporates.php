<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';

requireRole('admin');


// Fetch all pending corporate registrations
$stmt = $conn->prepare("SELECT id, company_name, contact_person, email, contact_number, address, created_at FROM users WHERE role = 'corporate' AND status = 'pending'");
$stmt->execute();
$pendingCorporates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define departments (this can be dynamic in future)
$departments = [
    'Customer Service',
    'Technical Support',
    'Billing & Payments',
    'Sales & Inquiries',
    'Account Management',
    'Product Support',
    'Compliance & Legal'
];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pending Corporate Registrations</h1>
    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
    <div id="toast"
         class="max-w-sm mx-auto mb-6 px-4 py-3 rounded-md shadow-lg flex items-center justify-between transition
                <?= $_GET['status'] === 'success'
                    ? 'bg-green-100 border border-green-400 text-green-700'
                    : 'bg-red-100 border border-red-400 text-red-700' ?>">
        <p class="text-sm font-medium">
            <?= htmlspecialchars(urldecode($_GET['message'])) ?>
        </p>
        <button onclick="document.getElementById('toast').remove()" class="ml-4 text-lg font-bold focus:outline-none">
            &times;
        </button>
    </div>

    <script>
        // Auto-hide toast after 4 seconds
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) toast.remove();
        }, 4000);
    </script>
<?php endif; ?>

    <?php if (count($pendingCorporates) === 0): ?>
        <p class="text-gray-600">No pending corporate accounts at this time.</p>
    <?php else: ?>
    
<!-- Wrapper -->
<div class="w-full">
  <!-- Mobile Layout -->
  <div class="space-y-4 sm:hidden">
    <?php foreach ($pendingCorporates as $corporate): ?>
      <div class="bg-white shadow-md rounded-xl p-4 space-y-2 border border-gray-200">
        <p class="text-sm"><span class="font-semibold">Company:</span> <?= htmlspecialchars($corporate['company_name']) ?></p>
        <p class="text-sm"><span class="font-semibold">Contact Person:</span> <?= htmlspecialchars($corporate['contact_person']) ?></p>
        <p class="text-sm"><span class="font-semibold">Email:</span> <?= htmlspecialchars($corporate['email']) ?></p>
        <p class="text-sm"><span class="font-semibold">Contact No.:</span> <?= htmlspecialchars($corporate['contact_number']) ?></p>
        <p class="text-sm"><span class="font-semibold">Address:</span> <?= htmlspecialchars($corporate['address']) ?></p>
        <p class="text-sm"><span class="font-semibold">Registered:</span> <?= date("M d, Y", strtotime($corporate['created_at'])) ?></p>

        <!-- Assign Dept -->
        <form method="POST" action="/ai-support-desk/pages/admin/handler/approve-corporate.php" class="space-y-2">
          <input type="hidden" name="corporate_id" value="<?= $corporate['id'] ?>">
          <select name="department" required class="border border-gray-300 text-sm rounded-md px-2 py-1 w-full focus:ring focus:ring-blue-500">
            <option value="" disabled selected>Select Department</option>
            <?php foreach ($departments as $dept): ?>
              <option value="<?= $dept ?>"><?= $dept ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" name="approve" class="w-full px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">Approve</button>
        </form>

        <!-- Reject Button -->
        <form method="POST" action="/ai-support-desk/pages/admin/handler/approve-corporate.php">
          <input type="hidden" name="corporate_id" value="<?= $corporate['id'] ?>">
          <button type="submit" name="reject" class="w-full mt-2 px-3 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">Reject</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Desktop Layout -->
  <div class="hidden sm:block overflow-x-auto rounded-xl shadow-md">
    <table class="min-w-full table-auto border-collapse divide-y divide-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Company</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Contact Person</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Email</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Contact No.</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Address</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Registered</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Assign Dept</th>
          <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <?php foreach ($pendingCorporates as $corporate): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 text-sm text-gray-800"><?= htmlspecialchars($corporate['company_name']) ?></td>
            <td class="px-4 py-2 text-sm text-gray-800"><?= htmlspecialchars($corporate['contact_person']) ?></td>
            <td class="px-4 py-2 text-sm text-gray-800"><?= htmlspecialchars($corporate['email']) ?></td>
            <td class="px-4 py-2 text-sm text-gray-800"><?= htmlspecialchars($corporate['contact_number']) ?></td>
            <td class="px-4 py-2 text-sm text-gray-800"><?= htmlspecialchars($corporate['address']) ?></td>
            <td class="px-4 py-2 text-sm text-gray-500"><?= date("M d, Y", strtotime($corporate['created_at'])) ?></td>
            <td class="px-4 py-2 text-sm text-gray-800">
              <form method="POST" action="/ai-support-desk/pages/admin/handler/approve-corporate.php" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <input type="hidden" name="corporate_id" value="<?= $corporate['id'] ?>">
                <select name="department" required class="border border-gray-300 text-sm rounded-md px-2 py-1 focus:ring focus:ring-blue-500 w-full sm:w-auto">
                  <option value="" disabled selected>Select</option>
                  <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept ?>"><?= $dept ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" name="approve" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">Approve</button>
              </form>
            </td>
            <td class="px-4 py-2 text-center">
              <form method="POST" action="/ai-support-desk/pages/admin/handler/approve-corporate.php">
                <input type="hidden" name="corporate_id" value="<?= $corporate['id'] ?>">
                <button type="submit" name="reject" class="px-4 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">Reject</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
    <?php endif; ?>
</div>