# Context

## Project
SNSU-FRMS (Surigao del Norte State University - Facility Request and Monitoring System)

## Scope of this mini-spec
This mini-spec covers the two assignment features already present in the current static web app:

1. Student Profile Card on `profile.html`
2. FAQ accordion on `help.html`

## Product context
The application is a browser-based university facilities support tool. Students log in locally, manage a profile card, submit facility-related requests, and read help content. The app is implemented with plain HTML, CSS, and JavaScript and relies on `localStorage` for persistence, so the targeted features must continue to work without a network connection.

## Existing implementation anchors
- UI pages: `profile.html` and `help.html`
- Behavior: `js/script.js`
- Styling: `css/style.css`

## Primary users
- Students creating or updating their identity details before filing requests
- Students reading common help guidance in the FAQ section
- Staff indirectly consuming the profile details attached to requests
