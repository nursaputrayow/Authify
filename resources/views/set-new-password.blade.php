<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set New Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Set a New Password</h2>

    <!-- Display Error or Success Message -->
    <div id="message" class="mb-4 hidden p-4 rounded-lg text-center"></div>

    <form id="resetPasswordForm" class="space-y-4">
      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
        <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="+1234567890" required>
      </div>

      <div>
        <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
        <input type="text" id="code" name="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="123456" required>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
        <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter new password" required>
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Confirm new password" required>
      </div>

      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
        Set New Password
      </button>
    </form>
  </div>

  <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', async (event) => {
      event.preventDefault();

      const messageDiv = document.getElementById('message');
      messageDiv.classList.add('hidden'); // Hide previous messages

      const data = {
        phone: document.getElementById('phone').value,
        code: document.getElementById('code').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value,
      };

      try {
        const response = await fetch('https://authify.test/api/v1/set-new-password', {
          method: 'POST',
          headers: { 
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`, 
            'Content-Type': 'application/json' 
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.status === 200) {
          // Success message
          messageDiv.textContent = 'Your password has been successfully updated!';
          messageDiv.className = 'p-4 mb-4 bg-green-100 text-green-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        } else {
          // Validation errors
          messageDiv.textContent = `Error: ${result.message || 'Something went wrong.'}`;
          messageDiv.className = 'p-4 mb-4 bg-red-100 text-red-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        }
      } catch (error) {
        // Network or unexpected errors
        messageDiv.textContent = 'Error: Unable to connect to the server.';
        messageDiv.className = 'p-4 mb-4 bg-red-100 text-red-800 rounded-lg text-center';
        messageDiv.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>
