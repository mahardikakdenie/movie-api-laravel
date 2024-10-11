
# Movie API Documentation

This API provides endpoints for managing authentication, media uploads, and movie information. Below is a detailed description of each endpoint and its usage.


# Authentication Endpoints

# Movie API

This API allows users to authenticate, upload media, and manage movie data. Below is the documentation for each endpoint available in this collection.

## Base URL
The base URL for all endpoints is: {{url}}/API


## Authentication

### Login
Authenticate a user and obtain a token.

- **URL:** `{{url}}/api/auth/login`
- **Method:** `POST`
- **Body (urlencoded):**
  - `email`: _string_ (e.g., `admin2@gmail.com`)
  - `password`: _string_ (e.g., `password`)
- **Response:** Returns a token upon successful login.
  
  Example:
  ```json
    {
        "meta": {
            "status": true,
            "message": "message.success",
            "code": 200
        },
        "data": "<your-auth-token>"
    }


### Register
Create a new user.

- **URL:** `{{url}}/api/auth/register`
- **Method:** `POST`
- **Body (urlencoded):**
    -   `email`: _string_ (e.g., `admin2@gmail.com`)
    -   `password`: _string_ (e.g., `password`)
    -   `name`: _string_ (e.g., `dika`)
