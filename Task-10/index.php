<?php
// Include database connection
require_once 'connection.php';

// Initialize variables
$edit_mode = false;
$edit_id = 0;
$edit_title = '';
$edit_content = '';
$edit_mood = 'neutral';
$edit_tags = '';
$message = '';

// Daily quotes
$daily_quotes = [
    "Every day may not be good, but there's something good in every day.",
    "Write it on your heart that every day is the best day in the year.",
    "The secret of getting ahead is getting started.",
    "Today is a perfect day to start living your dreams.",
    "Be yourself; everyone else is already taken.",
    "Life is what happens when you're busy making other plans.",
    "The best time for new beginnings is now.",
    "Your story matters. Write it down.",
    "Small moments, big memories.",
    "Gratitude turns what we have into enough."
];
$quote_of_the_day = $daily_quotes[date('z') % count($daily_quotes)];

// Greeting based on time
$hour = date('G');
if ($hour < 12) {
    $greeting = "Good Morning";
    $greeting_emoji = "🌅";
} elseif ($hour < 17) {
    $greeting = "Good Afternoon";
    $greeting_emoji = "☀️";
} else {
    $greeting = "Good Evening";
    $greeting_emoji = "🌙";
}

// CREATE - Handle form submission for new entry
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $entry_date = $_POST['entry_date'];
    $mood = $conn->real_escape_string($_POST['mood']);
    $tags = $conn->real_escape_string($_POST['tags']);
    
    $sql = "INSERT INTO journal (title, content, entry_date, mood, tags) VALUES ('$title', '$content', '$entry_date', '$mood', '$tags')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "New journal entry created successfully!";
        header("Location: index.php?success=created");
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}

// UPDATE - Handle form submission for updating entry
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $entry_date = $_POST['entry_date'];
    $mood = $conn->real_escape_string($_POST['mood']);
    $tags = $conn->real_escape_string($_POST['tags']);
    
    $sql = "UPDATE journal SET title='$title', content='$content', entry_date='$entry_date', mood='$mood', tags='$tags' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?success=updated");
        exit();
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}

// DELETE - Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM journal WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?success=deleted");
        exit();
    } else {
        $message = "Error deleting record: " . $conn->error;
    }
}

// EDIT - Load entry for editing
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit']);
    
    $sql = "SELECT * FROM journal WHERE id=$edit_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $edit_title = $row['title'];
        $edit_content = $row['content'];
        $edit_entry_date = $row['entry_date'];
        $edit_mood = $row['mood'] ?? 'neutral';
        $edit_tags = $row['tags'] ?? '';
    }
}

// READ - Fetch all journal entries
$sql = "SELECT * FROM journal ORDER BY entry_date DESC";
$result = $conn->query($sql);

// Handle success messages
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'created':
            $message = "Journal entry created successfully!";
            break;
        case 'updated':
            $message = "Journal entry updated successfully!";
            break;
        case 'deleted':
            $message = "Journal entry deleted successfully!";
            break;
    }
}

// Mood configurations
$moods = [
    'happy' => ['emoji' => '😊', 'color' => '#FFD700', 'label' => 'Happy'],
    'excited' => ['emoji' => '🤩', 'color' => '#FF6B6B', 'label' => 'Excited'],
    'peaceful' => ['emoji' => '😌', 'color' => '#4ECDC4', 'label' => 'Peaceful'],
    'thoughtful' => ['emoji' => '🤔', 'color' => '#95E1D3', 'label' => 'Thoughtful'],
    'grateful' => ['emoji' => '🙏', 'color' => '#F38181', 'label' => 'Grateful'],
    'sad' => ['emoji' => '😢', 'color' => '#A8DADC', 'label' => 'Sad'],
    'anxious' => ['emoji' => '😰', 'color' => '#DDA15E', 'label' => 'Anxious'],
    'neutral' => ['emoji' => '😐', 'color' => '#B8B8B8', 'label' => 'Neutral']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Personal Diary ✨</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Indie+Flower&family=Patrick+Hand&family=Architects+Daughter&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Delius Swash Caps';
            padding: 20px 0 60px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 31px,
                    rgba(139, 69, 19, 0.1) 31px,
                    rgba(139, 69, 19, 0.1) 32px
                );
            pointer-events: none;
            z-index: 1;
        }
        
        .container {
            max-width: 900px;
            position: relative;
            z-index: 2;
        }
        
        .diary-header {
            text-align: center;
            margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(139, 69, 19, 0.2);
        }
        
        .diary-title {
            font-family: 'Meow Script';
            font-size: 3.5rem;
            font-weight: 700;
            color: #6B4423;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .greeting {
            font-size: 1.5rem;
            color: #8B5A3C;
            margin-bottom: 15px;
        }
        
        .daily-quote {
            font-style: italic;
            color: #A0826D;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
            border-left: 3px solid #D4A574;
            background: rgba(244, 234, 220, 0.5);
            border-radius: 5px;
        }
        
        .paper-card {
            background: #FFFEF0;
            background-image: 
                linear-gradient(90deg, transparent 79px, rgba(139, 69, 19, 0.1) 79px, rgba(139, 69, 19, 0.1) 81px, transparent 81px),
                linear-gradient(#FFF9E6 0.05em, transparent 0.05em);
            background-size: 100% 1.5em;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            border: 1px solid #E5D5B7;
            position: relative;
        }
        
        .paper-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 60px;
            width: 2px;
            height: 100%;
            background: rgba(255, 0, 0, 0.2);
        }
        
        .form-label {
            font-family: 'Delius Swash Caps';
            font-size: 1.2rem;
            color: #6B4423;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            font-family: 'Delius Swash Caps';
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid #D4A574;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #A0826D;
            box-shadow: 0 0 0 0.25rem rgba(160, 130, 109, 0.25);
            background: #FFF;
        }
        
        .form-control::placeholder {
            color: #C4B5A0;
            font-style: italic;
        }
        
        textarea.form-control {
            line-height: 1.8em;
            min-height: 150px;
        }
        
        .mood-selector {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        
        .mood-option {
            display: none;
        }
        
        .mood-label {
            cursor: pointer;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #D4A574;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .mood-option:checked + .mood-label {
            background: #6B4423;
            color: white;
            border-color: #6B4423;
            transform: scale(1.05);
        }
        
        .mood-label:hover {
            background: #F4EAE0;
            transform: translateY(-2px);
        }
        
        .btn-diary {
            font-family: 'Delius Swash Caps';
            font-size: 1.2rem;
            padding: 12px 30px;
            background: #6B4423;
            color: white;
            border: none;
            border-radius: 25px;
            box-shadow: 0 4px 10px rgba(107, 68, 35, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-diary:hover {
            background: #8B5A3C;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(107, 68, 35, 0.4);
        }
        
        .btn-edit {
            background: #D4A574;
            color: white;
        }
        
        .btn-edit:hover {
            background: #A0826D;
        }
        
        .btn-delete {
            background: #C07070;
            color: white;
        }
        
        .btn-delete:hover {
            background: #A05050;
        }
        
        .timeline {
            position: relative;
            padding-left: 50px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #D4A574, #A0826D);
        }
        
        .timeline-entry {
            position: relative;
            margin-bottom: 40px;
            padding: 25px;
            background: #FFFEF0;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border: 1px solid #E5D5B7;
            transition: all 0.3s ease;
        }
        
        .timeline-entry:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .timeline-entry.newest {
            background: linear-gradient(135deg, #FFF9E6 0%, #FFFEF0 100%);
            border: 2px solid #FFD700;
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.3);
        }
        
        .timeline-entry.newest::after {
            content: '✨ Latest';
            position: absolute;
            top: -12px;
            right: 20px;
            background: #FFD700;
            color: #6B4423;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .timeline-dot {
            position: absolute;
            left: -37px;
            top: 30px;
            width: 18px;
            height: 18px;
            background: white;
            border: 4px solid;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .entry-title {
            font-family: 'Caveat', cursive;
            font-size: 2.2rem;
            font-weight: 700;
            color: #6B4423;
            margin-bottom: 10px;
            line-height: 1.2;
        }
        
        .entry-content {
            font-family: 'Delius Swash Caps';
            font-size: 1.1rem;
            color: #5A4A3A;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .entry-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #E5D5B7;
        }
        
        .entry-date {
            font-size: 0.9rem;
            color: #A0826D;
            font-family: 'Patrick Hand', cursive;
        }
        
        .mood-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
            color: white;
        }
        
        .tag-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .tag {
            background: rgba(212, 165, 116, 0.2);
            color: #6B4423;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            border: 1px solid #D4A574;
        }
        
        .entry-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-sm-diary {
            font-family: 'Delius Swash Caps';
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 1rem;
            border: none;
            transition: all 0.3s ease;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            margin: 40px 0;
        }
        
        .empty-illustration {
            font-size: 6rem;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .empty-state h3 {
            font-family: 'Delius Swash Caps';
            font-size: 2rem;
            color: #6B4423;
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: #A0826D;
            font-size: 1.2rem;
        }
        
        .success-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #6B4423;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 1000;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #D4A574, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="diary-header">
            <div class="diary-title">My Personal Diary</div>
            <div class="greeting"><?php echo $greeting_emoji . ' ' . $greeting; ?>!</div>
            <div class="daily-quote">"<?php echo $quote_of_the_day; ?>"</div>
        </div>

        <?php if ($message): ?>
            <div class="success-toast" id="successToast">
                ✨ <?php echo $message; ?>
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('successToast').style.animation = 'slideIn 0.5s ease reverse';
                    setTimeout(() => {
                        document.getElementById('successToast').style.display = 'none';
                    }, 500);
                }, 3000);
            </script>
        <?php endif; ?>

        <!-- CREATE/UPDATE FORM -->
        <div class="paper-card">
            <h3 style="font-family: 'Delius Swash Caps'; font-size: 2.5rem; color: #6B4423; margin-bottom: 25px;">
                <?php echo $edit_mode ? '✏️ Edit Your Memory' : 'Capture Today\'s Moment'; ?>
            </h3>
            
            <form method="POST" action="index.php">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                <?php endif; ?>
                
                <div class="mb-4">
                    <label for="title" class="form-label">What's on your mind?</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           placeholder="Give this moment a title..."
                           value="<?php echo htmlspecialchars($edit_title); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="mood" class="form-label">How are you feeling?</label>
                    <div class="mood-selector">
                        <?php foreach ($moods as $mood_key => $mood_data): ?>
                            <input type="radio" class="mood-option" id="mood_<?php echo $mood_key; ?>" 
                                   name="mood" value="<?php echo $mood_key; ?>" 
                                   <?php echo ($edit_mood == $mood_key) ? 'checked' : ''; ?>>
                            <label for="mood_<?php echo $mood_key; ?>" class="mood-label">
                                <?php echo $mood_data['emoji'] . ' ' . $mood_data['label']; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="content" class="form-label">What made today meaningful?</label>
                    <textarea class="form-control" id="content" name="content" rows="8" 
                              placeholder="Dear Diary,&#10;&#10;Today was special because..." required><?php echo htmlspecialchars($edit_content); ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="tags" class="form-label">Tags (comma-separated)</label>
                    <input type="text" class="form-control" id="tags" name="tags" 
                           placeholder="family, work, goals, gratitude..."
                           value="<?php echo htmlspecialchars($edit_tags); ?>">
                </div>
                
                <div class="mb-4">
                    <label for="entry_date" class="form-label">When did this happen?</label>
                    <input type="datetime-local" class="form-control" id="entry_date" name="entry_date" 
                           value="<?php echo $edit_mode ? date('Y-m-d\TH:i', strtotime($edit_entry_date)) : date('Y-m-d\TH:i'); ?>" required>
                </div>
                
                <div class="d-flex gap-3">
                    <?php if ($edit_mode): ?>
                        <button type="submit" name="update" class="btn-diary">
                            Save Changes
                        </button>
                        <a href="index.php" class="btn-diary" style="background: #A0826D;">
                            Cancel
                        </a>
                    <?php else: ?>
                        <button type="submit" name="create" class="btn-diary">
                            Add to Diary
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="divider"></div>

        <!-- READ - DISPLAY ALL ENTRIES IN TIMELINE -->
        <h2 style="font-family: 'Caveat', cursive; font-size: 2.8rem; color: #6B4423; text-align: center; margin-bottom: 30px;">
            Your Journey Through Time
        </h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="timeline">
                <?php 
                $first = true;
                while($row = $result->fetch_assoc()): 
                    $mood = $row['mood'] ?? 'neutral';
                    $mood_data = $moods[$mood];
                    $tags = !empty($row['tags']) ? explode(',', $row['tags']) : [];
                ?>
                    <div class="timeline-entry <?php echo $first ? 'newest' : ''; ?>" 
                         style="border-left: 5px solid <?php echo $mood_data['color']; ?>;">
                        
                        <div class="timeline-dot" style="border-color: <?php echo $mood_data['color']; ?>;"></div>
                        
                        <div class="entry-meta">
                            <span class="mood-badge" style="background: <?php echo $mood_data['color']; ?>;">
                                <?php echo $mood_data['emoji'] . ' ' . $mood_data['label']; ?>
                            </span>
                            <span class="entry-date">
                                📅 <?php echo date('l, F j, Y', strtotime($row['entry_date'])); ?>
                                <span style="opacity: 0.7;">• <?php echo date('g:i A', strtotime($row['entry_date'])); ?></span>
                            </span>
                        </div>
                        
                        <h4 class="entry-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                        
                        <div class="entry-content">
                            <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                        </div>
                        
                        <?php if (!empty($tags)): ?>
                            <div class="tag-container">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="tag">🏷️ <?php echo trim(htmlspecialchars($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-actions">
                            <a href="index.php?edit=<?php echo $row['id']; ?>" 
                               class="btn-sm-diary btn-edit">
                                ✏️ Edit
                            </a>
                            <a href="index.php?delete=<?php echo $row['id']; ?>" 
                               class="btn-sm-diary btn-delete"
                               onclick="return confirm('Are you sure you want to erase this memory? 📖💭');">
                                🗑️ Delete
                            </a>
                        </div>
                    </div>
                <?php 
                    $first = false;
                endwhile; 
                ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-illustration">📖✨</div>
                <h3>Your Diary Awaits...</h3>
                <p>Start capturing life's beautiful moments.<br>Every great story begins with a single entry!</p>
                <div style="margin-top: 25px; font-size: 3rem;">
                    ✍️ 🌟 💭 📝 ❤️
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-select neutral mood if none selected on page load
        document.addEventListener('DOMContentLoaded', function() {
            const moodInputs = document.querySelectorAll('input[name="mood"]');
            const anyChecked = Array.from(moodInputs).some(input => input.checked);
            if (!anyChecked) {
                document.getElementById('mood_neutral').checked = true;
            }
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
