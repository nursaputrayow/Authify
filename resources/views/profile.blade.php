<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Your Profile</h2>
    
    <!-- Profile Information -->
    <div class="space-y-4">
      <div>
        <h3 class="text-sm font-medium text-gray-700">Name</h3>
        <p id="profileName" class="text-lg font-semibold text-gray-800">John Doe</p>
      </div>
      <div>
        <h3 class="text-sm font-medium text-gray-700">Email</h3>
        <p id="profileEmail" class="text-lg font-semibold text-gray-800">john.doe@example.com</p>
      </div>
      <div>
        <h3 class="text-sm font-medium text-gray-700">Phone</h3>
        <p id="profilePhone" class="text-lg font-semibold text-gray-800">+1234567890</p>
      </div>
    </div>

    <div class="mt-6 text-center">
      <a href="/update-profile" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
        Update Profile
      </a>
    </div>
  </div>

  <script>
    // Fetch the user's profile information
    async function loadProfile() {
      const response = await fetch('https://authify.test/api/v1/profile', {
        method: 'GET',
        headers: { 
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`, 
          'Content-Type': 'application/json' 
        }
      });
      const result = await response.json();

      if (response.status === 200) {
        document.getElementById('profileName').textContent = result.data.name;
        document.getElementById('profileEmail').textContent = result.data.email;
        document.getElementById('profilePhone').textContent = result.data.phone;
      }
    }

    // Call the function to load the profile on page load
    loadProfile();
  </script>
</body>
</html>
