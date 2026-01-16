# Prompt Generation API

A powerful RESTful API built with Laravel that combines content management with AI-powered image generation capabilities. This API provides a complete solution for user authentication, post management, and intelligent image prompt generation using Google Gemini AI.

## Features

### 🔐 Authentication & Authorization
- **GitHub-style Personal Access Tokens**: Implemented using Laravel Sanctum for secure API authentication
- **User Registration & Login**: Complete authentication system with password hashing and token-based access
- **Token Management**: Users can generate and manage multiple access tokens for different applications
- **Secure Session Management**: Token expiration and revocation support

### 📝 Post Management (CRUD)
- **Create Posts**: Users can create new posts with full content management
- **Read Posts**: Retrieve posts with pagination and sorting capabilities
- **Update Posts**: Modify existing posts with validation
- **Delete Posts**: Remove posts with proper authorization checks
- **Search Functionality**: Full-text search across posts
- **Sorting Options**: Sort posts by creation date, updates, or custom parameters

### 🎨 AI Image Prompt Generation
- **Image Upload & Analysis**: Upload images in multiple formats (JPEG, PNG, etc.)
- **Intelligent Prompt Generation**: Uses Google Gemini AI to analyze images and generate descriptive prompts
- **Secure File Storage**: Images are securely stored with sanitized filenames and random identifiers
- **Metadata Tracking**: Captures file size, original filename, and MIME type for each upload
- **Generation History**: Track all generated prompts with timestamps

### 🖼️ Image Generation
- **Prompt-to-Image**: Generate images from text prompts using AI capabilities
- **Generation Management**: Store and retrieve previously generated images
- **Batch Operations**: Support for multiple simultaneous generations

## Technology Stack

- **Framework**: Laravel (latest)
- **Authentication**: Laravel Sanctum
- **AI Service**: Google Gemini API
- **Database**: MySQL/PostgreSQL
- **File Storage**: Local/Cloud storage support
- **Testing**: Pest PHP
- **API Documentation**: Scramble (OpenAPI)

## Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd api-test
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Gemini API**
- Add your Google Gemini API key to `.env`:
```
GEMINI_API_KEY=your_api_key_here
```

5. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

6. **Start the development server**
```bash
php artisan serve
```

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login and receive access token
- `POST /api/auth/logout` - Logout and revoke token

### Posts
- `GET /api/v1/posts` - List all posts (with search & sort)
- `POST /api/v1/posts` - Create a new post
- `GET /api/v1/posts/{id}` - Get a single post
- `PUT /api/v1/posts/{id}` - Update a post
- `DELETE /api/v1/posts/{id}` - Delete a post

### Image Prompt Generation
- `GET /api/v1/prompt-generations` - List generated prompts
- `POST /api/v1/prompt-generations` - Generate prompt from image upload
- `GET /api/v1/image-generations` - List generated images
- `POST /api/v1/image-generations` - Generate image from prompt

## Authentication Example

### Register a User
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Using the Token
```bash
curl -X GET http://localhost:8000/api/v1/posts \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

## Image Prompt Generation Example

### Upload Image & Generate Prompt
```bash
curl -X POST http://localhost:8000/api/v1/prompt-generations \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -F "image=@/path/to/image.jpg"
```

Response:
```json
{
  "data": {
    "id": 1,
    "image_path": "uploads/images/example_abc123.jpg",
    "generated_prompt": "A serene mountain landscape at sunset with golden light reflecting on a calm lake",
    "file_size": 2048576,
    "original_filename": "mountain.jpg",
    "mime_type": "image/jpeg",
    "created_at": "2026-01-17T10:30:00Z"
  }
}
```

## Search & Sort

### Search Posts
```bash
curl -X GET "http://localhost:8000/api/v1/posts?search=laravel" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Sort Posts
```bash
curl -X GET "http://localhost:8000/api/v1/posts?sort=-created_at" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/     # API Controllers
│   ├── Requests/        # Form request validation
│   └── Resources/       # API resource transformations
├── Models/
│   ├── User.php
│   ├── Post.php
│   └── ImageGeneration.php
└── Services/
    └── GeminiService.php # AI integration service

config/
├── gemini.php           # Gemini API configuration
├── sanctum.php          # Authentication configuration
└── ...

database/
├── migrations/          # Database schema
└── seeders/             # Data seeders
```

## Configuration

### Gemini Service Configuration
Configure your Gemini API settings in `config/gemini.php`:
- API Key
- Model preferences
- Request timeouts
- Response formats

### Sanctum Configuration
Authentication token settings in `config/sanctum.php`:
- Token expiration times
- Multiple device support
- CORS configuration

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test tests/Feature/Auth
```

## API Documentation

Access the interactive API documentation generated by Scramble:
```
http://localhost:8000/api/documentation
```

This provides a complete OpenAPI specification with all endpoints, parameters, and response examples.

## Security Considerations

- All passwords are hashed using bcrypt
- API tokens are cryptographically secure
- CORS is configured for allowed origins
- Input validation on all requests
- File uploads are sanitized and validated
- Rate limiting can be configured

## Environment Variables

Key environment variables to configure:

```env
APP_NAME=PromptGenerationAPI
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_test
DB_USERNAME=root
DB_PASSWORD=

GEMINI_API_KEY=your_key_here
SANCTUM_STATEFUL_DOMAINS=localhost

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
```

## Contributing

1. Create a feature branch (`git checkout -b feature/amazing-feature`)
2. Commit your changes (`git commit -m 'Add amazing feature'`)
3. Push to the branch (`git push origin feature/amazing-feature`)
4. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For issues, questions, or suggestions, please open an issue on the repository.
