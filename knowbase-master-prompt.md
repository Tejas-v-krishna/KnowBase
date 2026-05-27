# KnowBase — Laravel Project Master Prompt

> This is a complete specification for an agentic coding IDE to scaffold, build, and wire up the **KnowBase** web application — a community-driven knowledge sharing platform built with Laravel 11. Read this file fully before writing any code. Follow every section in order.

---

## 1. Project Overview

**Name:** KnowBase  
**Tagline:** Write. Ask. Discuss. All in one place.  
**Type:** Full-stack Laravel web application  
**Purpose:** A unified knowledge-sharing platform combining long-form articles, Stack Overflow-style Q&A, and community forum discussions — open to all users regardless of background.

---

## 2. Tech Stack

| Layer            | Technology                                      |
|------------------|-------------------------------------------------|
| Framework        | Laravel 11                                      |
| PHP Version      | PHP 8.2+                                        |
| Frontend         | Blade templates + Alpine.js + Tailwind CSS v3   |
| Reactivity       | Laravel Livewire v3                             |
| Auth             | Laravel Breeze (Blade stack)                    |
| Rich Text Editor | Trix Editor (via ActionText-style integration)  |
| Database         | MySQL 8.0                                       |
| Storage          | Laravel Storage (local driver, public disk)     |
| Queue            | Laravel Queues (database driver)                |
| Search           | Laravel Scout + database driver (TNTSearch optional) |
| Cache            | File driver (upgradeable to Redis)              |

---

## 3. Database Schema

Create migrations for each of the following tables in order.

### 3.1 `users`
```
id               bigint PK auto_increment
name             varchar(100)
username         varchar(50) unique
email            varchar(150) unique
password         varchar(255)
avatar           varchar(255) nullable
bio              text nullable
reputation       int default 0
role             enum('guest','member','moderator','admin') default 'member'
email_verified_at timestamp nullable
remember_token   varchar(100) nullable
created_at       timestamp
updated_at       timestamp
```

### 3.2 `topics`
```
id               bigint PK
name             varchar(100) unique
slug             varchar(110) unique
description      text nullable
cover_image      varchar(255) nullable
followers_count  int default 0
created_at       timestamp
updated_at       timestamp
```

### 3.3 `tags`
```
id               bigint PK
name             varchar(80) unique
slug             varchar(90) unique
description      text nullable
created_at       timestamp
updated_at       timestamp
```

### 3.4 `articles`
```
id               bigint PK
user_id          bigint FK -> users.id
topic_id         bigint FK -> topics.id nullable
title            varchar(255)
slug             varchar(270) unique
excerpt          text nullable
body             longtext
cover_image      varchar(255) nullable
status           enum('draft','published') default 'draft'
reading_time     int default 0   -- in minutes
views_count      int default 0
likes_count      int default 0
bookmarks_count  int default 0
published_at     timestamp nullable
created_at       timestamp
updated_at       timestamp
```

### 3.5 `questions`
```
id               bigint PK
user_id          bigint FK -> users.id
topic_id         bigint FK -> topics.id nullable
title            varchar(255)
slug             varchar(270) unique
body             longtext
status           enum('open','closed','resolved') default 'open'
accepted_answer_id bigint nullable
views_count      int default 0
answers_count    int default 0
votes_count      int default 0
created_at       timestamp
updated_at       timestamp
```

### 3.6 `answers`
```
id               bigint PK
question_id      bigint FK -> questions.id
user_id          bigint FK -> users.id
body             longtext
is_accepted      boolean default false
votes_count      int default 0
created_at       timestamp
updated_at       timestamp
```

### 3.7 `threads` (forums)
```
id               bigint PK
user_id          bigint FK -> users.id
topic_id         bigint FK -> topics.id nullable
title            varchar(255)
slug             varchar(270) unique
body             longtext
is_pinned        boolean default false
is_resolved      boolean default false
replies_count    int default 0
views_count      int default 0
created_at       timestamp
updated_at       timestamp
```

### 3.8 `replies`
```
id               bigint PK
thread_id        bigint FK -> threads.id
user_id          bigint FK -> users.id
parent_id        bigint FK -> replies.id nullable   -- for nested replies
body             longtext
created_at       timestamp
updated_at       timestamp
```

### 3.9 `votes`
```
id               bigint PK
user_id          bigint FK -> users.id
votable_type     varchar(100)   -- polymorphic: App\Models\Question, App\Models\Answer
votable_id       bigint
value            tinyint        -- 1 = upvote, -1 = downvote
created_at       timestamp
updated_at       timestamp
UNIQUE(user_id, votable_type, votable_id)
```

### 3.10 `likes`
```
id               bigint PK
user_id          bigint FK -> users.id
likeable_type    varchar(100)   -- polymorphic: App\Models\Article
likeable_id      bigint
created_at       timestamp
UNIQUE(user_id, likeable_type, likeable_id)
```

### 3.11 `bookmarks`
```
id               bigint PK
user_id          bigint FK -> users.id
bookmarkable_type varchar(100)  -- polymorphic
bookmarkable_id  bigint
created_at       timestamp
UNIQUE(user_id, bookmarkable_type, bookmarkable_id)
```

### 3.12 `comments`
```
id               bigint PK
user_id          bigint FK -> users.id
commentable_type varchar(100)   -- polymorphic: Article, Thread etc.
commentable_id   bigint
body             text
created_at       timestamp
updated_at       timestamp
```

### 3.13 `taggables` (pivot)
```
tag_id           bigint FK -> tags.id
taggable_type    varchar(100)
taggable_id      bigint
PRIMARY KEY(tag_id, taggable_type, taggable_id)
```

### 3.14 `topic_user` (pivot — following)
```
topic_id         bigint FK -> topics.id
user_id          bigint FK -> users.id
created_at       timestamp
PRIMARY KEY(topic_id, user_id)
```

### 3.15 `notifications`
```
id               uuid PK
type             varchar(255)
notifiable_type  varchar(255)
notifiable_id    bigint
data             json
read_at          timestamp nullable
created_at       timestamp
updated_at       timestamp
```

### 3.16 `badges`
```
id               bigint PK
name             varchar(100)
description      text
icon             varchar(100)
criteria_type    varchar(100)   -- e.g. 'reputation', 'answers_count'
criteria_value   int
created_at       timestamp
updated_at       timestamp
```

### 3.17 `badge_user` (pivot)
```
badge_id         bigint FK -> badges.id
user_id          bigint FK -> users.id
awarded_at       timestamp
PRIMARY KEY(badge_id, user_id)
```

### 3.18 `collections`
```
id               bigint PK
user_id          bigint FK -> users.id
name             varchar(150)
description      text nullable
is_public        boolean default true
created_at       timestamp
updated_at       timestamp
```

### 3.19 `collection_items` (pivot)
```
id               bigint PK
collection_id    bigint FK -> collections.id
collectable_type varchar(100)
collectable_id   bigint
order            int default 0
created_at       timestamp
```

---

## 4. Eloquent Models

Generate a model for each table above. Apply the following traits and relationships:

### Shared Traits
- All content models (Article, Question, Thread) should use `HasSlug` — auto-generate slug from title on creation using `Str::slug()`, append a short unique suffix if duplicate.
- All content models should use `HasFactory`.
- All content models should implement `Searchable` (Laravel Scout).

### Key Relationships

**User**
- hasMany: Articles, Questions, Answers, Threads, Replies, Comments, Collections
- belongsToMany: Topics (via topic_user — "following")
- belongsToMany: Badges (via badge_user)
- morphMany: Notifications

**Article**
- belongsTo: User, Topic
- morphToMany: Tags (via taggables)
- morphMany: Comments, Likes, Bookmarks

**Question**
- belongsTo: User, Topic
- hasMany: Answers
- belongsTo: Answer (acceptedAnswer)
- morphToMany: Tags
- morphMany: Votes, Bookmarks

**Answer**
- belongsTo: Question, User
- morphMany: Votes

**Thread**
- belongsTo: User, Topic
- hasMany: Replies
- morphToMany: Tags

**Reply**
- belongsTo: Thread, User
- hasMany: Replies (self, for nesting via parent_id)

---

## 5. Application Features

### 5.1 Authentication
- Registration with name, username, email, password
- Login / logout
- Email verification (Laravel's built-in)
- Forgot password / reset password
- OAuth optional (can stub for later)

### 5.2 User Profiles
- Public profile page at `/u/{username}`
- Shows: avatar, bio, reputation, badges, join date
- Tabs: Articles, Questions, Answers, Collections
- Edit profile (avatar upload, bio, name — not username after set)
- Reputation displayed as a score with a visual badge tier

### 5.3 Articles Module
- **Create:** Rich text editor (Trix), title, excerpt, topic, tags, cover image upload, save as draft or publish
- **Read:** Article detail page with reading time, author card, like button, bookmark button, comment section, related articles sidebar
- **List:** Paginated list at `/articles` — sortable by Latest, Most Liked, Most Viewed
- **Edit/Delete:** Author only
- **My Drafts:** Dashboard section for author's unpublished articles

### 5.4 Q&A Module
- **Ask:** Post a question with title, rich-text body, topic, tags
- **Answer:** Submit an answer with rich text
- **Accept Answer:** Question author can mark one answer as accepted (updates `is_accepted`, sets `accepted_answer_id`)
- **Voting:** Upvote/downvote on questions and answers (members only, cannot self-vote)
- **Filters:** All, Unanswered, My Questions, Resolved
- **Sort:** Newest, Most Voted, Most Viewed

### 5.5 Forum/Discussions Module
- **Create Thread:** Title, rich text body, topic, tags
- **Reply:** Nested replies (2 levels deep max)
- **Pin/Resolve:** Moderators can pin threads; authors can mark as resolved
- **Filters:** All, Pinned, Resolved, My Threads
- **Sort:** Newest, Most Replies, Most Viewed

### 5.6 Topics
- Public topic pages at `/topics/{slug}`
- Each topic aggregates Articles, Questions, and Threads under tabs
- Users can follow/unfollow topics
- Admin can create and manage topics

### 5.7 Tags
- Tags are shared across Articles, Questions, Threads
- Tag pages at `/tags/{slug}` listing all content with that tag
- Auto-suggest on content creation

### 5.8 Search
- Global search bar in the navbar
- Searches across Articles, Questions, Threads
- Results page grouped by content type
- Uses Laravel Scout

### 5.9 Notifications
- In-app notifications (stored in DB)
- Triggered by:
  - Someone answers your question
  - Your answer is accepted
  - Someone replies to your thread/reply
  - Someone comments on your article
  - You earn a badge
- Unread count badge on navbar bell icon
- Mark all as read action

### 5.10 Reputation & Badges System

**Reputation Points:**
| Action                        | Points |
|-------------------------------|--------|
| Article published              | +10    |
| Article liked                  | +2     |
| Question asked                 | +5     |
| Answer upvoted                 | +10    |
| Answer downvoted               | -2     |
| Answer accepted                | +15    |
| Answer accepted (asker gives)  | +2     |
| Thread started                 | +3     |

**Seed these starter badges:**
| Badge           | Criteria                        |
|-----------------|---------------------------------|
| First Post      | Published first article         |
| Curious         | Asked first question            |
| Helpful         | Got first accepted answer       |
| Rising Star     | Reached 100 reputation          |
| Contributor     | Reached 500 reputation          |
| Expert          | Reached 1000 reputation         |
| Prolific        | Published 10 articles           |
| Answer Machine  | Posted 50 answers               |

### 5.11 Collections
- Users can create named collections (public or private)
- Add Articles, Questions, or Threads to collections
- Collection pages at `/u/{username}/collections/{id}`
- Can reorder items

### 5.12 "Ask the Community" from Articles
- Button on article detail page: "Got a question about this?"
- Pre-fills new question form with a link back to the article as context

### 5.13 Moderation (Moderator/Admin)
- Report button on all content types
- Reports queue in admin panel
- Moderators can: delete content, pin threads, close questions, ban users
- Admin can: manage topics, assign moderators, manage badges

### 5.14 Admin Panel
Build a simple Blade-based admin panel at `/admin` (no external package required):
- Dashboard: counts of users, articles, questions, threads, reports
- Users table: list, edit role, ban/unban
- Topics: CRUD
- Tags: CRUD
- Reports: list and resolve
- Badges: CRUD

---

## 6. Routes

```
GET    /                          → Home (feed of latest across all types)
GET    /articles                  → Article listing
GET    /articles/create           → Create article (auth)
POST   /articles                  → Store article (auth)
GET    /articles/{slug}           → Article detail
GET    /articles/{slug}/edit      → Edit article (author)
PUT    /articles/{slug}           → Update article (author)
DELETE /articles/{slug}           → Delete article (author)

GET    /questions                 → Q&A listing
GET    /questions/ask             → Ask question (auth)
POST   /questions                 → Store question (auth)
GET    /questions/{slug}          → Question detail
POST   /questions/{id}/answers    → Post answer (auth)
POST   /answers/{id}/accept       → Accept answer (question author)
POST   /votes/{type}/{id}         → Vote (auth)

GET    /threads                   → Forum listing
GET    /threads/create            → Create thread (auth)
POST   /threads                   → Store thread (auth)
GET    /threads/{slug}            → Thread detail
POST   /threads/{id}/replies      → Post reply (auth)

GET    /topics                    → All topics
GET    /topics/{slug}             → Topic page
POST   /topics/{id}/follow        → Follow/unfollow topic (auth)

GET    /tags/{slug}               → Tag page
GET    /search                    → Search results

GET    /u/{username}              → User profile
GET    /u/{username}/collections  → User collections
GET    /u/{username}/collections/{id} → Single collection

GET    /dashboard                 → User dashboard (auth)
GET    /settings                  → Profile settings (auth)
PUT    /settings                  → Update settings (auth)

GET    /notifications             → Notifications list (auth)
POST   /notifications/read-all    → Mark all read (auth)

GET    /admin                     → Admin dashboard (admin/mod)
...    /admin/*                   → Admin routes
```

---

## 7. Controllers

Create the following controllers:

- `HomeController` — aggregated feed, trending content
- `ArticleController` — full CRUD
- `QuestionController` — full CRUD
- `AnswerController` — store, accept
- `ThreadController` — full CRUD
- `ReplyController` — store, destroy
- `CommentController` — store, destroy (polymorphic)
- `VoteController` — toggle vote (polymorphic)
- `LikeController` — toggle like
- `BookmarkController` — toggle bookmark
- `TopicController` — index, show, follow
- `TagController` — show
- `SearchController` — global search
- `UserController` — profile, collections
- `NotificationController` — index, read all
- `SettingsController` — show, update
- `Admin\DashboardController`
- `Admin\UserController`
- `Admin\TopicController`
- `Admin\TagController`
- `Admin\BadgeController`

---

## 8. Policies

Create Laravel Policies for:

- `ArticlePolicy` — update/delete: owner or admin
- `QuestionPolicy` — update/delete: owner or admin; close: mod/admin
- `AnswerPolicy` — update/delete: owner or admin
- `ThreadPolicy` — update/delete: owner or admin; pin: mod/admin
- `ReplyPolicy` — update/delete: owner or admin

---

## 9. Jobs & Events

### Events
- `ArticlePublished`
- `QuestionAnswered`
- `AnswerAccepted`
- `ThreadReplied`
- `ReputationChanged`

### Listeners
- `NotifyQuestionAuthorOnAnswer` (listens to QuestionAnswered)
- `NotifyAnswerAuthorOnAccepted` (listens to AnswerAccepted)
- `NotifyThreadAuthorOnReply` (listens to ThreadReplied)
- `AwardBadgesOnReputationChange` (listens to ReputationChanged)
- `UpdateUserReputation` (listens to ReputationChanged)

### Jobs
- `UpdateReadingTime` — dispatched on article save, calculates reading time from word count
- `SendNotificationEmail` — optional email wrapper for in-app notifications

---

## 10. Blade Views Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php           ← main layout with navbar, footer
│   ├── guest.blade.php         ← auth pages layout
│   └── admin.blade.php         ← admin panel layout
├── components/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   ├── article-card.blade.php
│   ├── question-card.blade.php
│   ├── thread-card.blade.php
│   ├── tag-badge.blade.php
│   ├── user-avatar.blade.php
│   ├── vote-buttons.blade.php
│   ├── reputation-badge.blade.php
│   └── notification-item.blade.php
├── home/
│   └── index.blade.php
├── articles/
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── questions/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── create.blade.php
├── threads/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── create.blade.php
├── topics/
│   ├── index.blade.php
│   └── show.blade.php
├── tags/
│   └── show.blade.php
├── users/
│   ├── profile.blade.php
│   └── collections.blade.php
├── dashboard/
│   └── index.blade.php
├── settings/
│   └── index.blade.php
├── search/
│   └── results.blade.php
├── notifications/
│   └── index.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── users/index.blade.php
    ├── topics/index.blade.php
    ├── tags/index.blade.php
    └── badges/index.blade.php
```

---

## 11. UI Design Guidelines

Follow these design principles when building all Blade views:

- **Framework:** Tailwind CSS v3 via CDN or NPM (prefer NPM with Vite)
- **Font:** Use `Inter` from Google Fonts for body; `Sora` or `DM Sans` for headings
- **Color palette:**
  - Primary: `indigo-600` / `indigo-700`
  - Background: `gray-50` (light mode only for now)
  - Cards: `white` with `border border-gray-200 rounded-2xl shadow-sm`
  - Text: `gray-900` headings, `gray-600` body, `gray-400` meta
  - Success: `green-500`, Danger: `red-500`, Warning: `yellow-500`
- **Components style:**
  - All buttons: `rounded-lg px-4 py-2 font-medium transition`
  - Primary button: `bg-indigo-600 hover:bg-indigo-700 text-white`
  - Ghost button: `border border-gray-300 hover:bg-gray-100 text-gray-700`
  - Cards hover: `hover:shadow-md transition-shadow`
- **Navbar:** sticky top, white background, logo left, links center (Articles, Q&A, Discussions), right side: search, notifications bell (auth), avatar dropdown (auth), Login/Register (guest)
- **Content pages:** max-width `7xl` centered, two-column layout (content + sidebar) on desktop, single column on mobile
- **Responsive:** All layouts must be mobile-friendly

---

## 12. Seeders

Create seeders for:

- `UserSeeder` — seed 1 admin (`admin@knowbase.dev`, role: admin), 1 moderator, 10 regular members using Faker
- `TopicSeeder` — seed 8 topics: Technology, Science, Design, Business, Health, Education, Arts, Personal Development
- `TagSeeder` — seed 20 common tags: PHP, Laravel, JavaScript, Python, React, UX, Productivity, etc.
- `BadgeSeeder` — seed all 8 badges listed in section 5.10
- `ArticleSeeder` — seed 20 published articles spread across users/topics
- `QuestionSeeder` — seed 30 questions, some with answers, some with accepted answers
- `ThreadSeeder` — seed 15 threads with replies

Run all seeders via `DatabaseSeeder.php`.

---

## 13. Key Implementation Notes

1. **Slug generation:** Use a `HasSlug` trait. On create, generate slug from title using `Str::slug()`. Check DB for duplicates and append `-{n}` if needed. Never regenerate slug on update.

2. **Reading time:** Article's `reading_time` is calculated as `ceil(word_count / 200)` minutes. Dispatch `UpdateReadingTime` job on article save.

3. **Voting:** The `VoteController` handles polymorphic votes for Question and Answer. Toggle behavior: if same vote exists, remove it; if opposite vote, swap it. Update `votes_count` on parent after each vote.

4. **Reputation:** Maintain a `ReputationService` class that handles all reputation point calculations. Fire `ReputationChanged` event after each change. Do not mutate reputation directly in controllers.

5. **Notifications:** Use Laravel's built-in database notification channel. Format notification data as `{ type, actor_name, content_title, url }`. Display using the `notification-item` component.

6. **Image uploads:** Store avatars and cover images in `storage/app/public/uploads/{type}/`. Generate a symbolic link with `php artisan storage:link`. Validate: max 2MB, types: jpg/jpeg/png/webp.

7. **Authorization:** Use `$this->authorize()` in controllers. Do not do role checks inline; always use Policies or Gates.

8. **Admin middleware:** Create a `EnsureUserIsAdmin` middleware for `/admin` routes. Create `EnsureUserIsModerator` for mod-level actions.

9. **Search:** Implement `toSearchableArray()` in Article, Question, Thread models returning id, title, excerpt/body snippet, tags, topic name.

10. **Pagination:** Use `paginate(15)` on all listing queries. Use Tailwind-compatible pagination views (publish vendor views or use a package like `laravel-pagination-tailwind`).

---

## 14. Environment Setup

After scaffolding, the project should:

1. Run `composer install`
2. Copy `.env.example` to `.env`
3. Set `APP_NAME=KnowBase`
4. Set `DB_CONNECTION=mysql`, `DB_DATABASE=knowbase`, `DB_USERNAME=root`, `DB_PASSWORD=`
5. Run `php artisan key:generate`
6. Run `npm install && npm run build`
7. Run `php artisan migrate --seed`
8. Run `php artisan storage:link`
9. Run `php artisan queue:work` (in a separate terminal for notifications)

Provide a `README.md` with these setup steps.

---

## 15. File & Folder Conventions

- All service classes go in `app/Services/`
- All traits go in `app/Traits/`
- All custom middleware in `app/Http/Middleware/`
- All jobs in `app/Jobs/`
- All events in `app/Events/`
- All listeners in `app/Listeners/`
- All notifications in `app/Notifications/`
- Request validation classes in `app/Http/Requests/` (one per form action)
- Use Form Requests for all create/update operations — no inline `$request->validate()` in controllers

---

## 16. Build Order for the Agent

Execute in this sequence:

1. Scaffold Laravel 11 project (`composer create-project laravel/laravel knowbase`)
2. Install Breeze (`composer require laravel/breeze`, `php artisan breeze:install blade`)
3. Install Livewire (`composer require livewire/livewire`)
4. Install Scout (`composer require laravel/scout`)
5. Configure Tailwind CSS with Vite
6. Create all migrations (section 3) and run them
7. Create all Models with relationships (section 4)
8. Create Policies (section 8)
9. Create Form Request classes for every create/update operation
10. Create Controllers (section 7) with full CRUD logic
11. Create Events, Listeners, Jobs (section 9)
12. Create Notifications
13. Create Service classes (`ReputationService`, `SlugService`)
14. Create Middleware (`EnsureUserIsAdmin`, `EnsureUserIsModerator`)
15. Register all routes (section 6)
16. Build all Blade views and components (section 10) following UI guidelines (section 11)
17. Create and run all Seeders (section 12)
18. Generate `README.md` with setup instructions

---

*End of KnowBase master prompt. Build the complete project based on these specifications.*
