## Authify: API Authentication Routes

This project includes authentication routes under the `/v1` API version for user management:

### Available Routes:

- **POST `/v1/register`**  
  Registers a new user.

  ```json
  {
    "name": "test",
    "phone": "+6212345678910",
    "email": "test@gmail.com",
    "password": "12345678",
    "password_confirmation": "12345678"
  }
  ```

- **POST `/v1/verify`** (Rate-limited)  
  Verifies a user after registration.  
  *Rate limit: 5 requests per minute.*

  ```json
  {
    "phone": "+6212345678910",
    "code": "123456"
  }
  ```

- **POST `/v1/set-new-password`** (Rate-limited)  
  Allows a user to set a new password.

  ```json
  {
    "phone": "+6212345678910",
    "password": "12312312",
    "password_confirmation": "12312312",
    "code": "123123"
  }
  ```

- **POST `/v1/reset-password`** (Rate-limited)  
  Allows a user to reset their password.

  ```json
  {
    "phone": "+6212345678910",
  }
  ```

- **POST `/v1/login`**  
  Logs a user in and returns an authentication token.

  ```json
  {
    "phone": "+6212345678910",
    "password": "12345678"
  }
  ```

- **POST `/v1/logout`** (Authenticated)  
  Logs the user out, invalidating their token.

  ```json
  "Bearer Token : 3|hh6JX5lcpf70oTqNqaapRdMLJtzyfyQbbVXDO961a49bf0d0"
  ```

- **GET `/v1/profile`** (Authenticated)  
  Retrieves the user's profile.

  ```json
  "Bearer Token : 3|hh6JX5lcpf70oTqNqaapRdMLJtzyfyQbbVXDO961a49bf0d0"
  ```

- **POST `/v1/profile/update`** (Authenticated)  
  Updates the user's profile.

  ```json
  "Bearer Token : 3|hh6JX5lcpf70oTqNqaapRdMLJtzyfyQbbVXDO961a49bf0d0"

  {
    "name": "testing"
  }
  ```

- **Command to Switch Database Configuration (MySQL or SQLite)**
  ```json
  php artisan db:switch sqlite
  php artisan db:switch mysql

  php artisan config:cache
  php artisan clean          
  ```

### Authentication Middleware

Routes that require authentication use the `auth:sanctum` middleware to protect user-specific actions, such as profile retrieval and updating.

### Rate Limiting

Some routes, such as verification, password reset, and new password set routes, are rate-limited to prevent abuse. The rate limit is set to 5 requests per minute.

## License

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).