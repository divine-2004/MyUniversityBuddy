# TODO - Student sidebar fully functional

## Step 1: Update sidebar links (student pages)
- Update sidebar `href` values in:
  - index.html
  - profile.html
  - notes.html
  - help.html
  - notifications.html
- Goals:
  - Create Request → notes.html
  - Facility List → student/facility-list.html
  - Calendar → student/calendar.html
  - Reports → student/reports.html
  - Settings → student/settings.html


## Step 2: Create missing student pages
- Create:
  - facility-list.html
  - calendar.html
  - reports.html
  - settings.html
- Each page must include the same student sidebar and load css/style.css
- Each page must render content based on localStorage and call an initializer from js/script.js (or implement inline scripts if needed).

## Step 3: Add/extend JS for new pages
- Add functions to js/script.js:
  - initFacilityList
  - initCalendar
  - initReports
  - initStudentSettings


## Step 4: Add minimal CSS
- Update css/style.css for any new layout components used by the new pages.

## Step 5: Verification
- Manually open each student page in browser:
  - Ensure sidebar routing works.
  - Ensure Facility List / Calendar / Reports / Settings display correctly.
  - Ensure active nav styling works.

