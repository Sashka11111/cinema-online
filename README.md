# 🎬 Cinema Online

A comprehensive web platform for watching movies and series with extended social features, built on Laravel 11 using Livewire 3 and Filament 3.

## 📋 Table of Contents

- [Project Overview](#-project-overview)
- [Key Features](#-key-features)
- [Technology Stack](#-technology-stack)
- [Architecture](#-architecture)
- [Usage](#-usage)
- [Development](#-development)
- [License](#-license)

## 🎯 Project Overview

Cinema Online is a modern web platform for watching movies and series with a strong emphasis on social interaction and co-watching. The platform supports the Ukrainian language and includes full-text search with Ukrainian localization.

### Highlighted Features:
- 🎭 Full catalog of movies, series, cartoons, and anime
- 👥 Role system (user/moderator/admin)
- 🔍 Full-text search with Ukrainian localization
- 🏠 Co-watching rooms with WebSocket synchronization
- 📱 Responsive design with dark/light theme support
- 🔐 Social login (Discord, Telegram)
- 📊 Ratings, comments, and reviews system
- 📋 Personalized user lists
- 🎨 Admin panel

## ✨ Key Features

### 🎬 Content Management
- Manage movies, series, and episodes
- Tags and genres system
- Information about actors, directors, and crew
- Studios and production companies
- Content collections
- Rating system

### 👤 User Features
- Registration and authentication
- User profiles
- Personal lists (favorites, watched, planned)

### 💬 Social Features
- Comments on movies, episodes, and collections
- Like/dislike system
- Nested comments (replies)

### 🏠 Co-Watching Rooms
- Private and public rooms
- Video synchronization via WebSocket
- Viewer limits
- Password protection for private rooms
- QR code invitations

### 🔍 Search and Filtering
- Full-text search with Ukrainian localization
- Filtering by genres, years, ratings, etc.

### 🎨 Administration
- Admin panel using Filament 3
- Manage all entities
- Moderation tools
- Analytics and statistics
- User and role management

## 🛠 Technology Stack

### Backend
- **PHP 8.3+** – Main programming language
- **Laravel 11** – Web framework
- **Livewire 3** – Reactive components
- **Filament 3** – Admin panel
- **PostgreSQL** – Database with full-text search
- **Laravel Reverb** – WebSocket server for real-time features
- **Laravel Socialite** – Social authentication

### Frontend
- **CSS** – Styling
- **Alpine.js** (via Livewire) – Reactive JavaScript
- **Vite** – Frontend asset bundler
- **Laravel Echo** – WebSocket client

### Additional Packages
- **Laravel Breeze** – Authentication
- **Laravel Trend** – Analytics and trends
- **Blade Icons** – Icons (Clarity, BoxIcons, FontAwesome)
- **Laravel Lang** – Ukrainian localization

## 🏗 Architecture

### Core Models and Relationships

#### Users and Authentication
- `User` – Users with roles and profiles

#### Content
- `Movie` – Movies and series with metadata
- `Episode` – Episodes of series
- `Person` – Actors, directors, and crew
- `Studio` – Studios and production companies
- `Tag` – Genres and tags
- `Selection` – Curated content collections

#### Interaction
- `Comment` – Polymorphic comments
- `CommentLike` – Likes/dislikes on comments
- `CommentReport` – Comment reports
- `Rating` – Movie ratings
- `UserList` – Polymorphic personal lists

#### Rooms and Viewing
- `Room` – Co-watching rooms
- `WatchHistory` – Viewing history
- `SearchHistory` – Search history

### Architectural Highlights
- **ULID** as primary keys for all models
- **Polymorphic relationships** for flexibility (comments, lists, selections)
- **JSON fields** for performance optimization
- **Scoped models** for automatic filtering
- **Custom Query Builders** for complex queries
- **Policy classes** for role-based authorization

## 📖 Usage

The application is available at: http://cinema-online.pp.ua:99/

### Main Routes

#### Public Pages
- `/` – Home page with trending content
- `/movies` – Movies catalog
- `/series` – Series catalog
- `/cartoons` – Cartoons catalog
- `/anime` – Anime catalog
- `/selections` – Curated collections
- `/person/{person}` – Person profile

#### Content Pages
- `/movies/{movie}` – Movie detail page
- `/movies/{movie}/comments` – Comments on movie
- `/movies/{movie}/watch` – Watch page
- `/movies/{movie}/watch/{episode}` – Watch specific episode
- `/movies/{movie}/watch/{episode}/{room}` – Watch with others in room

#### User Pages (Authentication required)
- `/profile` – User profile
- `/my-lists` – Personal lists
- `/rooms` – Co-watching rooms

## 🔧 Development

### Project Structure
```

cinema-online/  
├── app/  
│ ├── Enums/ # Enums (statuses, roles, types)  
│ ├── Events/ # WebSocket events  
│ ├── Filament/ # Admin panel  
│ ├── Http/ # Controllers and middleware  
│ ├── Livewire/ # Livewire components  
│ ├── Models/ # Eloquent models  
│ ├── Policies/ # Authorization policies  
│ └── ValueObjects/ # Value objects  
├── database/  
│ ├── factories/ # Factories for testing  
│ ├── migrations/ # Database migrations  
│ ├── seeders/ # Seeders for data population  
│ └── fts-dict/ # Dictionaries for full-text search  
├── resources/  
│ ├── css/ # CSS styles  
│ ├── js/ # JavaScript files  
│ └── views/ # Blade templates  
└── tests/ # Tests

```
### Code Standards

The project follows:
- **PSR-12** for PHP code
- **BEM methodology** for CSS class naming
- **Conventional Commits** for commit messages
- **Ukrainian localization** for all UI strings

### Contributing

1. Fork the repository
2. Create a new branch for your feature (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Create a Pull Request

## 📄 License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
