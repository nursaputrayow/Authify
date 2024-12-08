<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Register</h2>
    <div id="message" class="mb-4 hidden p-4 rounded-lg text-center"></div>
    <form id="registerForm" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Your Name">
        <p id="nameError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="+62xxxxxxxx">
        <p id="phoneError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="you@example.com">
        <p id="emailError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Your Password">
        <p id="passwordError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Confirm Password">
        <p id="password_confirmationError" class="mt-1 text-red-600 text-sm hidden"></p>
      </div>
      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">Register</button>
    </form>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', async (event) => {
      event.preventDefault();

      const errorFields = ['name', 'phone', 'email', 'password', 'password_confirmation'];
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
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value
      };

      try {
        const response = await fetch('https://authify.test/api/v1/register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.status === 201) {
          messageDiv.textContent = 'Registration successful! Please verify your phone.';
          messageDiv.className = 'p-4 mb-4 bg-green-100 text-green-800 rounded-lg text-center';
          messageDiv.classList.remove('hidden');
        } else if (response.status === 422) {
          const errors = result.errors || {};
          for (const field in errors) {
            const errorElement = document.getElementById(`${field}Error`);
            if (errorElement) {
              errorElement.textContent = errors[field][0];
              errorElement.classList.remove('hidden');
            }
          }
        } else {
          messageDiv.textContent = `Error: ${result.message || 'An unexpected error occurred.'}`;
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
