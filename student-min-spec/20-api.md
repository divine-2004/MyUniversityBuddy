# API / Data Contract

## Browser storage keys

### `profileData`
Stored as JSON in `localStorage`.

Example payload:

```json
{
  "name": "Alex Dela Cruz",
  "studentId": "2024-00123",
  "course": "BSIT",
  "year": "3",
  "email": "alex@snsu.edu.ph",
  "phone": "+63 912 345 6789",
  "photo": "data:image/svg+xml,..."
}
```

### Related keys used by the current app
- `loggedIn`
- `facilityRequests`
- `fmrmsNotifications`
- `helpMessages`

## JavaScript functions used by the features

### Profile feature
- `initProfile()`: prepares the profile page
- `populateProfile()`: loads saved values into the form
- `setupProfileAutoSave()`: attaches live preview and debounced save listeners
- `saveProfile(showAlert)`: persists profile data to `localStorage`
- `updateStudentCard()`: refreshes the visible card and related profile UI
- `handleProfilePhotoUpload(event)`: loads a chosen photo into preview state

### FAQ feature
- `initHelp()`: prepares the help page
- `initFaqAccordion()`: binds accordion toggle behavior to `.faq-item` elements

## DOM contract

### Required profile form IDs
- `#name`
- `#studentId`
- `#course`
- `#year`
- `#email`
- `#phone`
- `#profilePhoto`

### Required card IDs
- `#cardName`
- `#cardId`
- `#cardCourse`
- `#cardYear`
- `#cardEmail`
- `#cardPhone`

### FAQ selectors
- `.faq-item`
- `.faq-question`
- `.faq-answer`
