# PHP Personal Diary - CRUD Application 📖✨

A beautiful, cozy digital diary application with a paper-notebook feel, built with PHP, MySQL, and Bootstrap. Features mood tracking, tags, and a timeline layout that makes journaling feel like writing in a real diary.

## Features

✅ **CREATE** - Capture today's moments with title, content, mood, tags, and date
✅ **READ** - View all entries in a beautiful timeline layout with paper texture
✅ **UPDATE** - Edit existing memories and update your feelings
✅ **DELETE** - Remove entries with a gentle confirmation
✅ **Mood Tracking** - Express how you felt with emoji moods (Happy, Excited, Peaceful, Grateful, etc.)
✅ **Tag System** - Organize entries with custom tags (family, work, goals, gratitude)
✅ **Timeline Layout** - Vertical timeline with colored mood indicators
✅ **Paper Design** - Authentic notebook feel with lined paper background
✅ **Human Touches** - Daily quotes, personalized greetings, empty state illustration
✅ **Cozy Aesthetics** - Handwritten fonts, warm colors, and gentle animations

## Design Features 🎨

- **Handwritten Fonts**: Caveat, Indie Flower, Patrick Hand, Architects Daughter
- **Paper Texture**: Lined notebook background with margin line
- **Timeline View**: Vertical timeline with colored dots based on mood
- **Mood System**: 8 different moods with unique colors and emojis
- **Tag Chips**: Beautiful tag display for easy organization
- **Newest Entry Highlight**: Gold border and "Latest" badge on most recent entry
- **Empty State**: Friendly illustration when no entries exist
- **Daily Quotes**: Rotating inspirational quotes
- **Time-based Greeting**: Good Morning/Afternoon/Evening with emojis
- **Gradient Hover**: Cards lift on hover with smooth animations

## Technologies Used

- **Backend**: PHP (mysqli)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Icons**: Bootstrap Icons

## Installation Steps

### 1. Prerequisites
- XAMPP/WAMP/LAMP (or any local PHP server)
- MySQL database
- Web browser

### 2. Setup Instructions

1. **Clone or Download the Repository**
   ```bash
   git clone <your-github-repo-url>
   ```

2. **Move Files to Web Server Directory**
   - Copy all files to your `htdocs` folder (XAMPP) or `www` folder (WAMP)
   - Example: `C:\xampp\htdocs\journal-app\`

3. **Create Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "Import" tab
   - Choose the `database.sql` file
   - Click "Go" to execute
   
   **If you already have the database running from before:**
   - Use `migrate_database.sql` instead to add mood and tags columns
   
   OR manually run these SQL commands:
   ```sql
   CREATE DATABASE journal_db;
   USE journal_db;
   -- Then copy and paste the rest from database.sql
   ```

4. **Configure Database Connection**
   - Open `connection.php`
   - Update the following if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', ''); // Add your MySQL password here
     define('DB_NAME', 'journal_db');
     ```

5. **Start Your Server**
   - Start Apache and MySQL from XAMPP/WAMP control panel
   - Open browser and navigate to: `http://localhost/journal-app/`

## File Structure

```
journal-app/
│
├── index.php              # Main application file (all CRUD operations)
├── connection.php         # Database connection file
├── database.sql           # Database schema and sample data
├── migrate_database.sql   # Migration script for existing databases
└── README.md             # This file
```

## Usage

### Create a New Entry
1. Fill in the "Capture Today's Moment" form at the top
2. Enter a title (what's on your mind)
3. Select your mood with emoji buttons
4. Write your entry (what made today meaningful)
5. Add tags separated by commas (optional)
6. Select the date and time
7. Click "✍️ Add to Diary"

### Read/View Entries
- All entries are displayed in a vertical timeline below the form
- The newest entry has a gold "✨ Latest" badge
- Each entry shows: title, mood badge, date/time, content, tags
- Entries are sorted by date (newest first)
- Colored left border indicates the mood

### Update an Entry
1. Click the "✏️ Edit" button on any entry
2. The form will populate with the entry data
3. Make your changes (title, mood, content, tags, date)
4. Click "💾 Save Changes"

### Delete an Entry
1. Click the "🗑️ Delete" button on any entry
2. Confirm: "Are you sure you want to erase this memory?"
3. Entry will be removed from database

## Screenshots

Include screenshots of:
- CREATE operation (form filled out)
- READ operation (entries displayed)
- UPDATE operation (edit mode)
- DELETE operation (before and after)

## Database Schema

**Table: journal**
| Field       | Type         | Description                    |
|-------------|--------------|--------------------------------|
| id          | INT          | Primary key (auto-increment)   |
| title       | VARCHAR(255) | Entry title                    |
| content     | TEXT         | Entry content                  |
| entry_date  | DATETIME     | Date of journal entry          |
| mood        | VARCHAR(50)  | User's mood (happy, sad, etc.) |
| tags        | TEXT         | Comma-separated tags           |
| created_at  | TIMESTAMP    | Record creation timestamp      |
| updated_at  | TIMESTAMP    | Record update timestamp        |

## Mood Options

The diary supports 8 different moods with unique colors:
- 😊 Happy (Gold)
- 🤩 Excited (Red-Orange)
- 😌 Peaceful (Turquoise)
- 🤔 Thoughtful (Mint)
- 🙏 Grateful (Coral)
- 😢 Sad (Light Blue)
- 😰 Anxious (Tan)
- 😐 Neutral (Gray)

## Author

Your Name

## License

This project is open source and available for educational purposes.
