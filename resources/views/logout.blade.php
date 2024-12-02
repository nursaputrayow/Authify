<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Are you sure you want to log out?</h2>
    <div id="message" class="mb-4 hidden p-4 rounded-lg text-center"></div>
    
    <!-- Logout Button -->
    <button id="logoutButton" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700">
      Log Out
    </button>

    <!-- Cancel Button -->
    <a href="/dashboard" class="mt-4 block text-blue-600 hover:underline">
      Cancel
    </a>
  </div>

  <script>
    document.getElementById('logoutButton').addEventListener('click', async () => {
      const messageDiv = document.getElementById('message');
      messageDiv.classList.add('hidden'); // Hide any previous messages

      try {
        const response = await fetch('https://authify.test/api/v1/logout', {
          method: 'POST',
          headers: { 
            'Authorization': `Bearer ${localStorage.getItem('auth_token')}`, // Assuming the token is stored in localStorage
            'Content-Type': 'application/json' 
          }
        });

        const result = await response.json();

        if (response.status === 200) {
          // Success message
          messageDiv.textContent = 'You have successfully logged out.';
          messageDiv.className = 'p-4 mb-4 bg-green-100 text-green-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
          
          // Redirect after logout (optional)
          setTimeout(() => window.location.href = '/login', 2000);
        } else {
          // Error message
          messageDiv.textContent = `Error: ${result.message || 'An unexpected error occurred.'}`;
          messageDiv.className = 'p-4 mb-4 bg-red-100 text-red-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        }
      } catch (error) {
        // Network or other unexpected errors
        messageDiv.textContent = 'Error: Unable to connect to the server.';
        messageDiv.className = 'p-4 mb-4 bg-red-100 text-red-800 rounded-lg text-center';
        messageDiv.classList.remove('hidden');
      }
    });
  </script>
</body>
</html>
