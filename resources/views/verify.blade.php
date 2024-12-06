<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Phone</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Phone Verification</h2>
    <div id="message" class="mb-4 hidden p-4 rounded-lg text-center"></div>
    <form id="verifyForm" class="space-y-4">
      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="+62xxxxxxxx">
        <p id="phoneError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <div>
        <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
        <input type="text" id="code" name="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="123456">
        <p id="codeError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">Verify</button>
    </form>
  </div>

  <script>
    document.getElementById('verifyForm').addEventListener('submit', async (event) => {
      event.preventDefault();

      const errorFields = ['phone', 'code'];
      errorFields.forEach(field => {
        const errorElement = document.getElementById(`${field}Error`);
        if (errorElement) {
          errorElement.textContent = '';
          errorElement.classList.add('hidden');
        }
      });

      const messageDiv = document.getElementById('message');
      messageDiv.classList.add('hidden');

      const data = {
        phone: document.getElementById('phone').value,
        code: document.getElementById('code').value
      };

      try {
        const response = await fetch('https://authify.test/api/v1/verify', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.status === 200) {
          messageDiv.textContent = 'Phone number successfully verified!';
          messageDiv.className = 'p-4 mb-4 bg-green-100 text-green-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        } else {
          messageDiv.textContent = result.message || 'Error occurred.';
          messageDiv.className = 'p-4 mb-4 bg-red-100 text-red-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        }
      } catch (error) {
        messageDiv.textContent = 'Error: Unable to connect to the server.';
        messageDiv.className = 'p-4 mb-4 bg-red-100 text-red-800 rounded-lg text-center';
        messageDiv.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>