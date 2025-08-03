<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/session-handler.php';
requireRole('user');

$ticket = [
  'id' => 1041,
  'subject' => 'Can’t access HR portal',
  'status' => 'Open',
  'department' => 'HR Department',
  'submitted' => 'July 28, 2025',
];

$messages = [];
for ($i = 0; $i < 20; $i++) {
  $isAI = $i % 4 === 1;
  $isHR = $i % 4 === 2;
  $message = [
    'sender' => $isAI ? 'AI Assistant (on behalf of HR Staff)' : ($isHR ? 'HR Staff' : 'You'),
    'timestamp' => 'July 28, 2025, ' . (9 + floor($i / 2)) . ':' . str_pad(($i % 2) * 30, 2, '0', STR_PAD_LEFT) . ' AM',
    'content' => $isAI
      ? 'Based on your message, please try the recommended troubleshooting steps.'
      : ($isHR ? 'We’ve taken action. Please verify if the issue is resolved.' : 'Still experiencing the issue.'),
  ];
  if ($isAI) {
    $message['ai'] = true;
  }
  $messages[] = $message;
}

function renderStatusBadge($status) {
  return match ($status) {
    'Open' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Open</span>',
    'Awaiting Staff' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Awaiting Staff</span>',
    'Resolved' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-600 text-white">Resolved</span>',
    default => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Unknown</span>',
  };
}
?><style>
#conversation::-webkit-scrollbar {
  width: 6px;
}
#conversation::-webkit-scrollbar-thumb {
  background-color: rgba(107, 114, 128, 0.3);
  border-radius: 8px;
}
</style><script>
function previewAttachment(input) {
  const preview = document.getElementById('attachmentPreview');
  preview.innerHTML = '';
  preview.classList.add('hidden');
  if (input.files.length > 0) {
    const file = input.files[0];
    preview.innerHTML = `
      <div class="flex items-center gap-2 border border-gray-200 bg-gray-50 p-2 mt-2 rounded text-sm text-gray-700">
        <span class="text-blue-600">📎</span>
        <span>${file.name}</span>
      </div>`;
    preview.classList.remove('hidden');
  }
}

function scrollToBottomIfNear() {
  const chat = document.getElementById('conversation');
  if (chat.scrollTop + chat.clientHeight >= chat.scrollHeight - 100) {
    chat.scrollTop = chat.scrollHeight;
  }
}

window.onload = scrollToBottomIfNear;
</script><div class="max-w-7xl mx-auto p-6 space-y-6 lg:grid lg:grid-cols-3 gap-6">
  <!-- Left/Main Column -->
  <div class="lg:col-span-2 space-y-6">
    <a href="my-tickets.php" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-sm font-medium text-gray-700 rounded-md shadow-sm hover:bg-gray-100 hover:text-blue-600 transition-all">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 -ml-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
      Back to My Tickets
    </a><div class="bg-white border rounded-xl shadow-sm p-5 sticky top-0 z-10 bg-opacity-95 backdrop-blur">
  <h1 class="text-xl font-semibold text-gray-800 mb-1">[#<?= $ticket['id'] ?>] <?= htmlspecialchars($ticket['subject']) ?></h1>
  <p class="text-sm text-gray-600 mb-4">Submitted to <strong><?= $ticket['department'] ?></strong> on <?= $ticket['submitted'] ?></p>
  <div class="flex flex-wrap items-center gap-3">
    <?= renderStatusBadge($ticket['status']) ?>
    <?php if ($ticket['status'] === 'Resolved'): ?>
      <form action="../../handlers/reopen-ticket.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
        <button type="submit" class="text-xs bg-gray-100 text-gray-800 px-3 py-1 rounded hover:bg-gray-200">
          Reopen Ticket
        </button>
      </form>
    <?php endif; ?>
  </div>
</div>

<div id="conversation" class="bg-gray-50 border rounded-xl max-h-[500px] overflow-y-auto px-4 py-3 space-y-4 scroll-smooth">
  <?php foreach ($messages as $msg): ?>
    <div class="flex <?= $msg['sender'] === 'You' ? 'justify-end' : 'justify-start' ?>">
      <?php if ($msg['sender'] !== 'You'): ?>
        <div class="flex-shrink-0 w-8 h-8 bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-bold rounded-full mr-2">
          <?= str_starts_with($msg['sender'], 'AI') ? 'AI' : 'HR' ?>
        </div>
      <?php endif; ?>
      <div class="max-w-[75%] px-4 py-3 shadow-sm
          <?= $msg['sender'] === 'You'
            ? 'bg-blue-600 text-white rounded-tl-xl rounded-bl-xl rounded-tr-xl'
            : (isset($msg['ai'])
              ? 'bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-tr-xl rounded-br-xl rounded-tl-xl'
              : 'bg-white border border-gray-200 text-gray-800 rounded-tr-xl rounded-br-xl rounded-tl-xl') ?>">
        <div class="text-xs font-semibold mb-1">
          <?= $msg['sender'] ?>
          <?php if (isset($msg['ai'])): ?>
            <span class="ml-2 bg-yellow-100 text-yellow-700 text-[10px] px-2 py-0.5 rounded-full">AI</span>
          <?php endif; ?>
        </div>
        <div class="text-sm"><?= htmlspecialchars($msg['content']) ?></div>
        <div class="text-[11px] mt-1 text-gray-400"><?= $msg['timestamp'] ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="text-right">
  <button onclick="scrollToBottomIfNear()" class="text-xs text-blue-600 hover:underline">↓ Jump to Latest</button>
</div>

<div id="typingIndicator" class="text-sm text-gray-400 hidden">You are typing…</div>

<?php if ($ticket['status'] !== 'Resolved'): ?>
  <div class="reply-box">
    <h2 class="text-lg font-semibold text-gray-700 mb-2">Send a Reply</h2>
    <div class="bg-white border rounded-xl shadow-sm p-5 space-y-4">
      <form action="../../handlers/reply-ticket.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
        <div>
          <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
          <textarea name="message" id="message" rows="4" required
            class="w-full mt-1 border rounded-md p-2 text-sm focus:ring focus:outline-none"></textarea>
        </div>
        <div>
          <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment (Optional)</label>
          <input type="file" name="attachment" id="attachment" onchange="previewAttachment(this)"
            class="w-full text-sm text-gray-600">
          <div id="attachmentPreview" class="hidden"></div>
        </div>
        <button type="submit"
          class="bg-blue-600 text-white text-sm px-5 py-2 rounded hover:bg-blue-700 transition">
          Send Reply
        </button>
      </form>
    </div>
  </div>
<?php else: ?>
  <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 text-sm p-4 rounded shadow-sm">
    This ticket is resolved. You must reopen it before sending a reply.
  </div>
<?php endif; ?>

  </div>  <!-- Sidebar -->  <aside class="hidden lg:block bg-white border rounded-xl p-4 shadow-sm space-y-3">
    <h3 class="text-sm font-semibold text-gray-700">Ticket Info</h3>
    <p><strong>ID:</strong> <?= $ticket['id'] ?></p>
    <p><strong>Department:</strong> <?= $ticket['department'] ?></p>
    <p><strong>Status:</strong> <?= $ticket['status'] ?></p>
    <p><strong>Submitted:</strong> <?= $ticket['submitted'] ?></p>
    <hr>
    <p class="text-xs text-gray-500">Typical response: 3–6 hours</p>
  </aside>
</div><script>
const msgInput = document.getElementById('message');
const typingIndicator = document.getElementById('typingIndicator');
msgInput?.addEventListener('input', () => {
  typingIndicator.classList.toggle('hidden', msgInput.value.trim() === '');
});
</script>