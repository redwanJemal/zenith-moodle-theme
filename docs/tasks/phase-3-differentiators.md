# Phase 3: Differentiating Features

> **Timeline:** Weeks 7-10 · **Total Effort:** 92 hours · **Tasks:** 7
> These features are what set Zenith apart from every other Moodle theme.

---

## P3-1: Focus Mode
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 8 hours
- **Blocks:** None

**Task:** Distraction-free course viewing mode that hides everything except content.

**Files:**
- [ ] `amd/src/focusmode.js` — Toggle, section nav, progress, preference
- [ ] `scss/components/_focusmode.scss` — Focus mode styles
- [ ] `templates/navbar_fm.mustache` — Minimal focus mode navbar

**Acceptance Criteria:**
- [ ] Toggle button visible on course pages (in navbar or floating)
- [ ] Hides: navbar, drawers, footer, breadcrumbs — shows only course content
- [ ] Minimal navigation bar with: exit button, course name, progress
- [ ] Previous/Next section navigation buttons
- [ ] Progress indicator (section X of Y)
- [ ] Escape key exits focus mode
- [ ] User preference persisted (remembers per-course)
- [ ] Mobile friendly (full-screen content)
- [ ] Smooth enter/exit animation

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/amd/src/focusmode.js`

---

## P3-2: Enhanced Dashboard
- **Status:** `[ ]`
- **Dependencies:** `P1-6`, `P1-9`
- **Effort:** 10 hours
- **Blocks:** None

**Task:** Beautiful dashboard with stats widgets and personalized content.

**Widgets:**
- [ ] **Stats Row** — Courses enrolled, completed, in-progress (animated counters)
- [ ] **Activities Due** — This week's upcoming deadlines
- [ ] **Recent Courses** — Quick-access cards (last 5 accessed)
- [ ] **Calendar** — Upcoming events mini-view
- [ ] **Progress Overview** — Course completion bars

**Files:**
- [ ] `classes/external/get_dashboard_stats.php` — AJAX stats endpoint
- [ ] `templates/dashboard_stats.mustache` — Stats widget template
- [ ] `scss/components/_dashboard.scss` — Dashboard styling
- [ ] `amd/src/dashboard.js` — Animated counters, lazy load

**Acceptance Criteria:**
- [ ] Stats load via AJAX (page loads fast, stats populate after)
- [ ] Animated number counters (count up from 0)
- [ ] Mobile responsive (cards stack vertically)
- [ ] Empty states: "No courses yet" with action button
- [ ] Loading skeleton shown while AJAX fetches
- [ ] Cached (session cache, 5-minute TTL)

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/templates/dashboard_stats.mustache`

---

## P3-3: Enhanced Enrollment Page
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 10 hours
- **Blocks:** None

**Task:** Redesigned course enrollment page — Udemy/Coursera-style landing page.

**Sections:**
- [ ] Course hero banner (course image + title + short description + rating)
- [ ] Course meta: duration, skill level, enrollment count
- [ ] Instructor bio (avatar, name, role, course count)
- [ ] Course content outline (sections/topics with activity count)
- [ ] Prominent "Enrol Now" CTA button
- [ ] Related courses section

**Files:**
- [ ] `classes/EnrolmentPageHandler.php` — Page context builder
- [ ] `classes/external/enrol_get_course_content.php` — AJAX content
- [ ] `classes/external/enrol_get_course_instructors.php` — AJAX instructors
- [ ] `templates/enrolpage.mustache` — Enrollment page template
- [ ] `scss/components/_enrollment.scss` — Enrollment page styling

**Acceptance Criteria:**
- [ ] Looks like a modern course landing page
- [ ] Mobile responsive (stacked layout)
- [ ] Handles free and paid courses (pricing display)
- [ ] Loading skeleton while fetching AJAX data
- [ ] Course image fallback if none set
- [ ] Instructor data loads gracefully (handles missing data)

**Reference:** `/home/redman/Edwiser-RemUI/theme_remui/remui/classes/EnrolmentPageHandler.php`

---

## P3-4: Accessibility Toolkit
- **Status:** `[ ]`
- **Dependencies:** `P1-1`
- **Effort:** 12 hours
- **Blocks:** None

**Task:** Built-in accessibility tools — floating panel available on every page.

**Features:**
- [ ] Font size adjuster (100%, 125%, 150%)
- [ ] High contrast mode toggle (inverts contrast ratios)
- [ ] Dyslexia-friendly font toggle (loads OpenDyslexic)
- [ ] Reading ruler / focus line (horizontal line follows cursor)
- [ ] Link highlighting (underlines + outlines all links)
- [ ] Pause animations toggle

**Files:**
- [ ] `classes/accessibility/toolkit.php` — Server-side a11y helpers
- [ ] `amd/src/accessibility.js` — Client-side toolkit logic
- [ ] `scss/components/_accessibility.scss` — Toolkit panel + modes
- [ ] `templates/accessibility_panel.mustache` — Floating panel UI
- [ ] `fonts/OpenDyslexic.woff2` — Dyslexia-friendly font

**Acceptance Criteria:**
- [ ] Floating a11y button (♿) on all pages (bottom-right)
- [ ] Panel opens with all toggle options
- [ ] Settings persist per-user via Moodle user preferences
- [ ] Works in combination with dark mode
- [ ] The toolkit itself is keyboard operable
- [ ] The toolkit panel is WCAG 2.2 AA compliant
- [ ] Reset all button clears all a11y preferences

---

## P3-5: Gamification UI
- **Status:** `[ ]`
- **Dependencies:** `P1-3`, `P1-6`
- **Effort:** 16 hours
- **Blocks:** None

**Task:** Native gamification elements built into the theme chrome.

**Features:**
- [ ] XP progress bar in navbar (shows current level progress)
- [ ] Level badge next to user avatar in user menu
- [ ] Daily streak counter (consecutive login days)
- [ ] Mini leaderboard widget on dashboard (top 10)
- [ ] Achievement toast notifications (XP gained, level up)

**Files:**
- [ ] `classes/gamification/engine.php` — XP calculation, level logic
- [ ] `classes/gamification/streak.php` — Streak tracking
- [ ] `amd/src/gamification.js` — UI updates, toasts, animations
- [ ] `scss/components/_gamification.scss` — XP bar, badges, leaderboard
- [ ] `templates/gamification/xp_bar.mustache` — Navbar XP bar
- [ ] `templates/gamification/leaderboard.mustache` — Dashboard widget
- [ ] `templates/gamification/achievement_toast.mustache` — Toast notification
- [ ] `db/install.xml` — Custom tables: `theme_zenith_xp`, `theme_zenith_streaks`

**Acceptance Criteria:**
- [ ] Works standalone — no external plugin required
- [ ] XP awarded for: course completion, activity completion, daily login
- [ ] Levels: 1-50 with configurable XP thresholds
- [ ] Optional integration with Level Up XP plugin (detect if installed)
- [ ] Can be enabled/disabled per-site via settings
- [ ] Mobile responsive (XP bar collapses to icon on mobile)
- [ ] Animated XP gain notification (toast with +XP value)
- [ ] Level up celebration (confetti or animation)

---

## P3-6: AI Assistant Integration
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 20 hours
- **Blocks:** None

**Task:** AI-powered features using Moodle 4.5+ AI subsystem (OpenAI, Ollama).

**Features:**
- [ ] Course content summarizer (sidebar widget on course pages)
- [ ] Smart course search / recommendations on dashboard
- [ ] "Ask about this course" chat panel (per-course context)

**Files:**
- [ ] `classes/ai/assistant.php` — AI integration layer
- [ ] `classes/ai/summarizer.php` — Content summarization logic
- [ ] `classes/ai/recommender.php` — Course recommendation engine
- [ ] `amd/src/ai/assistant.js` — Chat UI, streaming responses
- [ ] `scss/components/_ai-assistant.scss` — Chat panel, summary card
- [ ] `templates/ai/chat.mustache` — Chat interface
- [ ] `templates/ai/summary.mustache` — Summary card
- [ ] `templates/ai/recommendations.mustache` — Course recommendations

**Acceptance Criteria:**
- [ ] Uses Moodle AI subsystem API (works with OpenAI + Ollama)
- [ ] Graceful fallback if AI not configured ("AI not available" message)
- [ ] Can be enabled/disabled per-site via settings
- [ ] Rate limiting: max 20 requests/hour per user (configurable)
- [ ] Chat history persisted per session
- [ ] Streaming responses (text appears word by word)
- [ ] Mobile friendly: slide-up panel from bottom
- [ ] Loading state while AI processes

---

## P3-7: Learning Path Visualization
- **Status:** `[ ]`
- **Dependencies:** `P1-6`
- **Effort:** 16 hours
- **Blocks:** None

**Task:** Visual learning journey / roadmap showing course progression.

**Files:**
- [ ] `amd/src/learningpath.js` — SVG-based path visualization
- [ ] `scss/components/_learning-path.scss` — Path, nodes, connections
- [ ] `templates/learningpath/roadmap.mustache` — Roadmap container
- [ ] `templates/learningpath/node.mustache` — Individual course node

**Acceptance Criteria:**
- [ ] Visual roadmap with course milestones (nodes + connecting paths)
- [ ] Node states: completed (green), current (blue pulse), locked (gray), available (white)
- [ ] Shows completion percentage on each node
- [ ] Branching paths if prerequisite courses exist
- [ ] Click on node → navigates to course
- [ ] Hover on node → shows course summary tooltip
- [ ] Responsive: horizontal scroll on mobile, zoomed out view
- [ ] Animated progress (path fills as courses complete)
- [ ] SVG-based for crisp rendering at all sizes
- [ ] Dashboard widget version (compact) + full page version
