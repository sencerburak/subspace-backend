# Subspace Chat Application Backend

Subspace is a simple chat application backend built with PHP and the Slim framework. It allows users to create chat groups, join groups, send messages, and list messages within a group.

## Features

- Create chat groups
- Join existing chat groups
- Send messages to groups
- List messages in a group
- SQLite database for data storage
- RESTful JSON API

## Requirements

- PHP 7.4 or newer
- Composer
- SQLite 3

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/sencerburak/subspace-backend.git
   cd subspace-backend
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Set up the environment:
   ```
   cp .env.example .env
   ```
   Edit the `.env` file and set the appropriate values for your environment.

4. Initialize the database:
   ```
   php db_setup.php
   ```

5. Start the PHP development server:
   ```
   composer start
   ```

The application should now be running at `http://localhost:8080`.

## Architecture

The application follows a layered architecture to separate concerns and improve maintainability:

1. **Routing Layer**: Implemented using Slim framework, defining API endpoints and HTTP methods.
2. **Controller Layer**: Handles incoming requests, processes data, and returns responses.
3. **Service Layer**: Contains business logic, orchestrates operations between repositories.
4. **Repository Layer**: Manages data persistence and retrieval from the SQLite database.
5. **Model Layer**: Defines data structures and entities used throughout the application.

### Key Components

1. **User Management**: Users are identified by a unique ID, which is used as a simple authentication mechanism for API requests.

2. **Chat Groups**: Public groups that any user can create or join. Groups are identified by a unique ID.

3. **Messaging**: Users can send messages within groups they've joined. Messages are associated with a user and a group.

4. **Database**: SQLite is used for simplicity and ease of setup. The database schema includes tables for users, chat_groups, user_group associations, and messages.

5. **API Endpoints**:
   - `POST /users`: Create a new user
   - `GET /users`: List all users
   - `GET /users/{id}`: Get a specific user
   - `POST /chat-groups`: Create a new chat group
   - `GET /chat-groups`: List all chat groups
   - `POST /chat-groups/{id}/join`: Join a chat group
   - `POST /chat-groups/{id}/messages`: Send a message to a group
   - `GET /chat-groups/{id}/messages`: List messages in a group

    All endpoints that require user identification should include a `User-Id` header in the request.

6. **Testing**: PHPUnit is used for unit and integration testing, ensuring the reliability and correctness of the implemented features.

## API Usage - Example Curl Commands

### Users

#### Create a new user
```bash
curl -X POST http://localhost:8080/users \
  -H "Content-Type: application/json" \
  -d '{"username": "johndoe"}'
```

#### List all users
```bash
curl -X GET http://localhost:8080/users \
  -H "User-Id: 1"
```

#### Get a specific user
```bash
curl -X GET http://localhost:8080/users/1 \
  -H "User-Id: 1"
```

### Chat Groups

#### Create a new chat group
```bash
curl -X POST http://localhost:8080/chat-groups \
  -H "Content-Type: application/json" \
  -H "User-Id: 1" \
  -d '{"name": "General Chat"}'
```

#### List all chat groups
```bash
curl -X GET http://localhost:8080/chat-groups \
  -H "User-Id: 1"
```

#### Join a chat group
```bash
curl -X POST http://localhost:8080/chat-groups/1/join \
  -H "User-Id: 1"
```

### Messages

#### Send a message to a group
```bash
curl -X POST http://localhost:8080/chat-groups/1/messages \
  -H "Content-Type: application/json" \
  -H "User-Id: 1" \
  -d '{"content": "Hello, everyone!"}'
```

#### List messages in a group
```bash
curl -X GET http://localhost:8080/chat-groups/1/messages \
  -H "User-Id: 1"
```

Note: Replace `localhost:8080` with the appropriate host and port if your server is running on a different address. Also, make sure to replace the User-Id with a valid user ID when making requests.

## Testing

To run the test suite, use the following command:

```
composer test
```
