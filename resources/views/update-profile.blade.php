<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Update Your Profile</h2>
    <div id="message" class="mb-4 hidden p-4 rounded-lg text-center"></div>

    <form id="updateProfileForm" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Your Name">
      </div>

      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="+1234567890">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="you@example.com">
      </div>

      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
        Update Profile
      </button>
    </form>
  </div>

  <script>
    document.getElementById('updateProfileForm').addEventListener('submit', async (event) => {
      event.preventDefault();

      const messageDiv = document.getElementById('message');
      messageDiv.classList.add('hidden');

      const data = {
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value
      };

      try {
        const response = await fetch('https://authify.test/api/v1/profile/update', {
          method: 'POST',
          headers: { 
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`, 
            'Content-Type': 'application/json' 
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.status === 200) {
          messageDiv.textContent = 'Profile updated successfully!';
          messageDiv.className = 'p-4 mb-4 bg-green-100 text-green-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        } else {
          messageDiv.textContent = `Error: ${result.message || 'Something went wrong.'}`;
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