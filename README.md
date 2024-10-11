# Movie API Documentation

This API provides endpoints for managing authentication, media uploads, and movie information. Below is a detailed description of each endpoint and its usage.

# Authentication Endpoints

## Base URL

The base URL for all endpoints is: `{{url}}/api`

## Authentication
### Login

Authenticate a user and obtain a token.

-   **URL:** `{{url}}/api/auth/login`
-   **Method:** `POST`
-   **Body (urlencoded):**
    -   `email`: _string_ (e.g., `admin2@gmail.com`)
    -   `password`: _string_ (e.g., `password`)
-   **Response:** Returns a token upon successful login.

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
    ```

### Register

Create a new user.

-   **URL:** `{{url}}/api/auth/register`
-   **Method:** `POST`
-   **Body (urlencoded):**
    -   `email`: _string_ (e.g., `admin2@gmail.com`)
    -   `password`: _string_ (e.g., `password`)
    -   `name`: _string_ (e.g., `dika`)
-   **Response:** Returns a token upon successful registration.
    Example:
    ```json
    {
        "meta": {
            "status": true,
            "message": "Registration successful",
            "code": 200
        },
        "data": {
            "token": true
        }
    }

### Logout
Endpoint Logout User 

-   **URL:** `{{url}}/api/auth/logout`
-   **Method:** `POST`
-   **Response:** return message logged out
    Example
    ```json
    {
        "message": "Successfully logged out"
    }

### Me - Current User Active (Authenticated User Details)
Retrieve the details of the authenticated user.

-   **URL:** `{{url}}/api/user`
-   **Method:** `GET`
-   **Authentication:** Bearer Token required
-   **Response:** return current user with token active
    Example
    ```json
    {
        "id": 1,
        "name": "dika admin",
        "email": "admin@gmail.com",
        "email_verified_at": null,
        "created_at": "2024-10-08T07:40:51.000000Z",
        "updated_at": "2024-10-08T07:40:51.000000Z"
    }

## Media Upload URL
### Upload Media
Upload an IMAGE FILE

-   **URL:** `{{url}}/api/media/upload`
-   **Method:** `POST`
-   **Authentication:** Bearer Token required
-   **Body (form-data):**
    - `image`: _file_ (e.g., `image.jpeg`)
-   **Response:** return data media and save to database medias
    ```json
    {
        "message": "Image uploaded successfully",
        "data": {
            "id": "DVQ3mnc",
            "url": "https://i.ibb.co/SsBqHpY/Mark-Isham-The-Mechanic-jpg.jpg",
            "width": 220,
            "height": 219
        }
    }

## Movie
### Get Data Movies
Retrieve a list of movies with media information.

-   **URL:** `{{url}}/api/movie`
-   **Method:** `GET`
-   **Authentication:** Bearer Token required
-   **Query Parameters**
    - `entities`: _string_ (e.g., `media`) to include media details in the response
-   **Response:**
    ```json
    {
            "meta": {
                "status": true,
                "message": "message.success",
                "code": 200,
                "total": 10,
                "per_page": 10,
                "current_page": 1,
                "last_page": 1,
                "from": 1,
                "to": 10
            },
            "links": {
                "next": null,
                "prev": null,
                "first": "http://api.movie.test/api/movie?page=1",
                "last": "http://api.movie.test/api/movie?page=1"
            },
            "data": [
                {
                    "id": 1,
                    "title": "Boruto Shipuden",
                    "publish": "2002",
                    "description": "Doloribus qui quo ea inventore neque quasi ea dolores quam.\nLabore nam non consequatur voluptatibus cum eos vel.\nDolorem atque et consequatur et.",
                    "created_at": "2024-10-08T07:43:42.000000Z",
                    "updated_at": "2024-10-11T15:35:13.000000Z",
                    "media_id": 20,
                    "media": {
                        "id": 20,
                        "data": "{\"data\":{\"id\":\"mBvhZ1v\",\"title\":\"QR-Code-png\",\"url_viewer\":\"https:\\/\\/ibb.co\\/mBvhZ1v\",\"url\":\"https:\\/\\/i.ibb.co\\/7zgrM8g\\/QR-Code-png.png\",\"display_url\":\"https:\\/\\/i.ibb.co\\/BP4TWd4\\/QR-Code-png.png\",\"width\":1000,\"height\":1000,\"size\":93453,\"time\":1728587136,\"expiration\":0,\"image\":{\"filename\":\"QR-Code-png.png\",\"name\":\"QR-Code-png\",\"mime\":\"image\\/png\",\"extension\":\"png\",\"url\":\"https:\\/\\/i.ibb.co\\/7zgrM8g\\/QR-Code-png.png\"},\"thumb\":{\"filename\":\"QR-Code-png.png\",\"name\":\"QR-Code-png\",\"mime\":\"image\\/png\",\"extension\":\"png\",\"url\":\"https:\\/\\/i.ibb.co\\/mBvhZ1v\\/QR-Code-png.png\"},\"medium\":{\"filename\":\"QR-Code-png.png\",\"name\":\"QR-Code-png\",\"mime\":\"image\\/png\",\"extension\":\"png\",\"url\":\"https:\\/\\/i.ibb.co\\/BP4TWd4\\/QR-Code-png.png\"},\"delete_url\":\"https:\\/\\/ibb.co\\/mBvhZ1v\\/ae949490cc2ae5fdae55ef0fd1f79c13\"},\"success\":true,\"status\":200}",
                        "created_at": "2024-10-10T19:05:36.000000Z",
                        "updated_at": "2024-10-10T19:05:36.000000Z"
                    }
                }
            ],
    }

### Create Movie Data
Create a new movie data entry

-   **URL:** `{{url}}/api/movie`
-   **Method:** `POST`
-   **Authentication:** Bearer Token required
-   **Body (urlencoded):**
    -   `title`: _string_ (e.g., `The Mechanic`)
    -   `publish`: _string_ (e.g., `2022`)
    -   `description`: _string_ (e.g., `An action-packed movie`)
    -   `media_id`: _string_ (e.g., `2`)
-   **Response:**
    ```json
    {
        "meta": {
            "status": true,
            "message": "message.success",
            "code": 200
        },
         "data": {
            "title": "The Mechanic",
            "publish": "2022",
            "description": "Voluptatem debitis qui.\nVoluptatum repellendus aut.",
            "media_id": "2",
            "updated_at": "2024-10-11T17:43:25.000000Z",
            "created_at": "2024-10-11T17:43:25.000000Z",
            "id": 12,
            "media": {
                "id": 2,
                "data": "{\"data\":{\"id\":\"YkLg0K7\",\"title\":\"20240411-144557-1712821512727-1-image-jpeg\",\"url_viewer\":\"https:\\/\\/ibb.co\\/YkLg0K7\",\"url\":\"https:\\/\\/i.ibb.co\\/DCRdr6D\\/20240411-144557-1712821512727-1-image-jpeg.jpg\",\"display_url\":\"https:\\/\\/i.ibb.co\\/Ph6kDbZ\\/20240411-144557-1712821512727-1-image-jpeg.jpg\",\"width\":1440,\"height\":1920,\"size\":303126,\"time\":1728372963,\"expiration\":0,\"image\":{\"filename\":\"20240411-144557-1712821512727-1-image-jpeg.jpg\",\"name\":\"20240411-144557-1712821512727-1-image-jpeg\",\"mime\":\"image\\/jpeg\",\"extension\":\"jpg\",\"url\":\"https:\\/\\/i.ibb.co\\/DCRdr6D\\/20240411-144557-1712821512727-1-image-jpeg.jpg\"},\"thumb\":{\"filename\":\"20240411-144557-1712821512727-1-image-jpeg.jpg\",\"name\":\"20240411-144557-1712821512727-1-image-jpeg\",\"mime\":\"image\\/jpeg\",\"extension\":\"jpg\",\"url\":\"https:\\/\\/i.ibb.co\\/YkLg0K7\\/20240411-144557-1712821512727-1-image-jpeg.jpg\"},\"medium\":{\"filename\":\"20240411-144557-1712821512727-1-image-jpeg.jpg\",\"name\":\"20240411-144557-1712821512727-1-image-jpeg\",\"mime\":\"image\\/jpeg\",\"extension\":\"jpg\",\"url\":\"https:\\/\\/i.ibb.co\\/Ph6kDbZ\\/20240411-144557-1712821512727-1-image-jpeg.jpg\"},\"delete_url\":\"https:\\/\\/ibb.co\\/YkLg0K7\\/06bc5a2733d6a0c94798283df31b6cfd\"},\"success\":true,\"status\":200}",
                "created_at": "2024-10-08T07:43:07.000000Z",
                "updated_at": "2024-10-08T07:43:07.000000Z"
            }
        }
    }

### Update Movie Data
Update an existing movie entry.

-   **URL:** `{{url}}/api/movie/{id}`
-   **Method:** `PUT`
-   **Authentication:** Bearer Token required
-   **Path Parameter:**
    -   `id` _integer_ (The ID of the movie to update)
-   **Body (urlencoded):**
    -   `title`: _string_ (e.g., `The Mechanic`)
    -   `publish`: _string_ (e.g., `2022`)
    -   `description`: _string_ (e.g., `An action-packed movie`)
    -   `media_id`: _string_ (e.g., `2`)
-   **Response:**
    ```json
    {
        "meta": {
            "status": true,
            "message": "message.success",
            "code": 200
        },
         "data": {
            "title": "The Mechanic",
            "publish": "2022",
            "description": "Voluptatem debitis qui.\nVoluptatum repellendus aut.",
            "media_id": "2",
            "updated_at": "2024-10-11T17:43:25.000000Z",
            "created_at": "2024-10-11T17:43:25.000000Z",
            "id": 12,
            "media": {
                "id": 2,
                "data": "{\"data\":{\"id\":\"YkLg0K7\",\"title\":\"20240411-144557-1712821512727-1-image-jpeg\",\"url_viewer\":\"https:\\/\\/ibb.co\\/YkLg0K7\",\"url\":\"https:\\/\\/i.ibb.co\\/DCRdr6D\\/20240411-144557-1712821512727-1-image-jpeg.jpg\",\"display_url\":\"https:\\/\\/i.ibb.co\\/Ph6kDbZ\\/20240411-144557-1712821512727-1-image-jpeg.jpg\",\"width\":1440,\"height\":1920,\"size\":303126,\"time\":1728372963,\"expiration\":0,\"image\":{\"filename\":\"20240411-144557-1712821512727-1-image-jpeg.jpg\",\"name\":\"20240411-144557-1712821512727-1-image-jpeg\",\"mime\":\"image\\/jpeg\",\"extension\":\"jpg\",\"url\":\"https:\\/\\/i.ibb.co\\/DCRdr6D\\/20240411-144557-1712821512727-1-image-jpeg.jpg\"},\"thumb\":{\"filename\":\"20240411-144557-1712821512727-1-image-jpeg.jpg\",\"name\":\"20240411-144557-1712821512727-1-image-jpeg\",\"mime\":\"image\\/jpeg\",\"extension\":\"jpg\",\"url\":\"https:\\/\\/i.ibb.co\\/YkLg0K7\\/20240411-144557-1712821512727-1-image-jpeg.jpg\"},\"medium\":{\"filename\":\"20240411-144557-1712821512727-1-image-jpeg.jpg\",\"name\":\"20240411-144557-1712821512727-1-image-jpeg\",\"mime\":\"image\\/jpeg\",\"extension\":\"jpg\",\"url\":\"https:\\/\\/i.ibb.co\\/Ph6kDbZ\\/20240411-144557-1712821512727-1-image-jpeg.jpg\"},\"delete_url\":\"https:\\/\\/ibb.co\\/YkLg0K7\\/06bc5a2733d6a0c94798283df31b6cfd\"},\"success\":true,\"status\":200}",
                "created_at": "2024-10-08T07:43:07.000000Z",
                "updated_at": "2024-10-08T07:43:07.000000Z"
            }
        }
    }
