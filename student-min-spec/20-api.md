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

### `facilityRequests`
Stored as JSON array in `localStorage`.

Example payload:

```json
[
  {
    "id": 1681234567890,
    "title": "Broken light in Lab B",
    "location": "Lab B",
    "issueType": "Electrical",
    "description": "The fluorescent fixture above the workbench is flickering.",
    "priority": "Medium",
    "status": "Pending",
    "reportedAt": "2024-04-23T08:00:00.000Z",
    "dueDate": "2024-04-25T00:00:00.000Z",
    "notifiedDue": false
  }
]
```

### `fmrmsNotifications`
Stored as JSON array in `localStorage`.

Example payload:

```json
[
  {
    "id": 1681234567890,
    "message": "New request created: Broken light in Lab B (Status: Pending)",
    "createdAt": 1681234567890
  }
]
```

### `helpMessages`
Stored as JSON array in `localStorage` for locally submitted help requests.

## JavaScript functions used by the features

### Profile feature
- `initProfile()`: prepares the profile page
- `populateProfile()`: loads saved values into the form
- `setupProfileAutoSave()`: attaches live preview and debounced save listeners
- `saveProfile(showAlert)`: persists profile data to `localStorage`
- `updateStudentCard()`: refreshes the visible card and related profile UI
- `handleProfilePhotoUpload(event)`: loads a chosen photo into preview state
- `getUserRole()`: reads stored user role from `localStorage`

### FAQ feature
- `initHelp()`: prepares the help page
- `initFaqAccordion()`: binds accordion toggle behavior to `.faq-item` elements
- `sendHelpMessage()`: records help messages locally

### Request and admin feature
- `getRequests()`: loads request records from `localStorage`
- `saveRequests(requests)`: writes requests back to `localStorage`
- `renderRequests()`: renders the request list and manages filters
- `addRequest()`: creates a new facility request
- `getNextStatus(status)`: returns the next workflow status
- `changeStatus(id)`: updates request status
- `cancelRequest(id)`: marks a request as cancelled
- `removeRequest(id)`: removes a request from storage
- `clearResolved()`: clears completed and cancelled requests
- `addNotification(message)`: appends a local notification
- `renderNotifications()`: renders notification logs

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

### Request page IDs
- `#requestTitle`
- `#requestLocation`
- `#requestType`
- `#requestDescription`
- `#requestDate`
- `#requestDue`
- `#requestPriority`
- `#requestFilter`
- `#taskList`
- `#taskCount`

### Notification page IDs
- `#notificationList`

### FAQ selectors
- `.faq-item`
- `.faq-question`
- `.faq-answer`
