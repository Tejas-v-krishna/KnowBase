# KnowBase

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-777bb4.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/laravel-%3E%3D%2011.0-ff2d20.svg)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)

**KnowBase** is a premium, modern academic knowledge-sharing platform and gamified discussion ecosystem built for students. Students can ask questions, write insightful articles, create community discussion threads, follow other users, and unlock badges for their contributions.

---

## 🌟 Key Features

### 1. Interactive Q&A System
- Ask academic questions classified by school levels (Middle School, High School, College, etc.).
- Mark the best response as the **Brainliest Answer** to award extra reputation points.
- Upvote/downvote posts with real-time reputation adjustments (+10 for upvotes, -2 for downvotes).

### 2. Discussion Forums & Articles
- Write community articles featuring dynamic topic-relevant cover thumbnails.
- Create multi-category forum threads with full Markdown and rich text support using the customized **Trix Rich Text Editor**.
- Clean, outlines-based editor toolbar styling with Lucide icons.

### 3. Social Network & Follow System
- Follow/unfollow other students to build a learning network.
- Dynamic profile tabs rendering followers and following grids with avatars, usernames, and mini-stats.
- Real-time database notifications sent to users when they get followed.

### 4. Gamified Badges & Daily Trivia
- Complete the **Daily Trivia Challenge** (academic trivia queried from the Open Trivia DB API) to earn **+5 XP (Reputation)** on correct answers.
- Automated milestones system that grants emojis-styled badges (e.g. `First Post` 📝, `Answer Machine` ⚡, `Expert` 🏆) as students participate.

### 5. Premium Admin & Moderation Panel
- **Overview Dashboard**: Displays live metrics (Total Registered, Staff Count, Suspended Members).
- **User Moderation**: Review student profiles, change roles (Member, Moderator, Admin), or suspend accounts.
- **Badge Management**: Create custom badges, set criteria targets (Reputation, Articles, Answers), and select emoji icons.
- **Topics & Tags Management**: Manage standard content categories and tags in card-based interfaces.

### 6. High-Fidelity UI/UX Design
- **Grayscale Theme**: Premium slate-bordered aesthetic featuring circular avatars and squircle panels (`1.5rem` radius).
- **Smooth Transitions**: Staggered entrance blur-in animations and global page transitions.
- **Typewriter Hero**: Animated hero text with character backspacing and typewriter effects, guarded against layout shifts.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 11](https://laravel.com/)
- **Frontend Compiler**: [Vite](https://vite.dev/)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Interactions**: [Alpine.js](https://alpinejs.dev/)
- **Rich Text Editor**: [Trix Editor](https://trix-editor.org/)
- **Testing**: [PHPUnit](https://phpunit.de/)

---

## 🚀 Installation & Setup

Follow these steps to run the project locally:

### Prerequisites
- PHP $\ge$ 8.2
- Composer
- Node.js & npm

### Setup Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Tejas-v-krishna/KnowBase.git
   cd KnowBase
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure the environment:**
   Copy the example environment file and configure your database settings (defaults to SQLite/MySQL):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations and seed the database:**
   ```bash
   php artisan migrate --seed
   ```

5. **Build/Compile front-end assets:**
   ```bash
   # Development hot-reload compiler
   npm run dev

   # Production bundle compiler
   npm run build
   ```

6. **Start the local server:**
   ```bash
   php artisan serve
   ```
   Visit the app at `http://127.0.0.1:8000`.

---

## 🧪 Testing

Run the automated test suite containing 34 feature and unit tests (covering user following, question voting, reputation flows, and registration auth):
```bash
php artisan test
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
