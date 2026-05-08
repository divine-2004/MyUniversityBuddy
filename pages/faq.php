<?php
/**
 * FAQ Page Content
 * Displays frequently asked questions and answers
 */

// Include database connection
require_once '../includes/database.php';

// FAQ categories and questions
$faqCategories = [
    'general' => [
        'title' => 'General Questions',
        'icon' => 'info-circle',
        'faqs' => [
            [
                'question' => 'What is the SNSU Facility Request and Monitoring System (FRMS)?',
                'answer' => 'The SNSU-FRMS is a comprehensive online platform designed to streamline facility booking and maintenance requests for Surigao del Norte State University students, faculty, and staff. It provides an efficient way to request access to university facilities, track request status, and manage bookings.'
            ],
            [
                'question' => 'Who can use the FRMS?',
                'answer' => 'The system is available to all SNSU community members including students, faculty, staff, and authorized personnel. Different user roles have varying levels of access and permissions within the system.'
            ],
            [
                'question' => 'Is the system accessible 24/7?',
                'answer' => 'Yes, the FRMS is available 24 hours a day, 7 days a week. However, facility bookings and requests are processed during regular business hours (Monday-Friday, 8:00 AM - 5:00 PM).'
            ]
        ]
    ],
    'requests' => [
        'title' => 'Making Requests',
        'icon' => 'clipboard-check',
        'faqs' => [
            [
                'question' => 'How do I submit a facility request?',
                'answer' => 'To submit a request: 1) Log in to your account, 2) Navigate to "My Requests" page, 3) Click "New Request" button, 4) Fill out the required information including facility type, date, time, and purpose, 5) Submit the request. You will receive a confirmation email and can track the status in your dashboard.'
            ],
            [
                'question' => 'What information do I need to provide when making a request?',
                'answer' => 'Required information includes: facility selection, request type (equipment repair, room reservation, event booking, etc.), preferred date and time, detailed description of your needs, expected number of attendees (for events), and contact information. Additional details help us process your request more efficiently.'
            ],
            [
                'question' => 'How far in advance should I make a booking request?',
                'answer' => 'We recommend submitting booking requests at least 2-3 business days in advance for standard reservations. For special events or large gatherings, please submit requests at least 1-2 weeks in advance. Some facilities may require longer notice periods during peak times.'
            ],
            [
                'question' => 'Can I modify or cancel a request after submission?',
                'answer' => 'Yes, you can modify pending requests from the "My Requests" page. Approved requests may require contacting facilities management directly for changes. Cancellations must be made at least 24 hours in advance to avoid penalties. Frequent cancellations may affect future booking privileges.'
            ]
        ]
    ],
    'facilities' => [
        'title' => 'Facilities & Availability',
        'icon' => 'building',
        'faqs' => [
            [
                'question' => 'What facilities are available for booking?',
                'answer' => 'Available facilities include computer laboratories, auditorium, gymnasium, library study rooms, conference rooms, and various classrooms. Each facility has specific booking requirements and availability schedules. Check the "Facility List" page for current availability and details.'
            ],
            [
                'question' => 'How do I check facility availability?',
                'answer' => 'You can check availability by: 1) Browsing the "Facility List" page to see current status, 2) Using the calendar view to see booked dates, 3) Contacting facilities management for real-time availability, 4) Checking the facility details page for booking restrictions and requirements.'
            ],
            [
                'question' => 'Are there any restrictions on facility usage?',
                'answer' => 'Yes, facilities have various restrictions: maximum capacity limits, time restrictions (some facilities close at specific times), equipment usage policies, food and beverage policies, and noise level guidelines. These restrictions are clearly marked on each facility\'s detail page.'
            ],
            [
                'question' => 'What happens if a facility is under maintenance?',
                'answer' => 'When a facility is under maintenance, it will be marked as "Maintenance" status and unavailable for booking. You will receive notifications about maintenance schedules, and alternative facilities may be suggested. Maintenance is typically scheduled during off-peak hours to minimize disruption.'
            ]
        ]
    ],
    'technical' => [
        'title' => 'Technical Support',
        'icon' => 'tools',
        'faqs' => [
            [
                'question' => 'I forgot my password. How can I reset it?',
                'answer' => 'Click the "Forgot Password" link on the login page. Enter your registered email address, and you will receive a password reset link. Follow the instructions in the email to create a new password. If you don\'t receive the email, check your spam folder or contact IT support.'
            ],
            [
                'question' => 'The system is running slowly. What can I do?',
                'answer' => 'Try clearing your browser cache and cookies, ensure you have a stable internet connection, and try using a different browser. If the issue persists, contact IT support with details about your device, browser, and the specific pages experiencing slowdowns.'
            ],
            [
                'question' => 'How do I update my profile information?',
                'answer' => 'Go to the "My Profile" page, click "Edit Profile", update your information, and save changes. You can modify your contact details, but some information like student ID may be read-only and require administrative approval for changes.'
            ],
            [
                'question' => 'I\'m having trouble accessing the system. What should I do?',
                'answer' => 'First, ensure you\'re using a supported browser (Chrome, Firefox, Safari, or Edge). Clear your browser cache and try again. If you still can\'t access the system, contact IT support at it@snsu.edu.ph or call extension 1234 during business hours.'
            ]
        ]
    ],
    'policies' => [
        'title' => 'Policies & Guidelines',
        'icon' => 'file-text',
        'faqs' => [
            [
                'question' => 'What is the cancellation policy?',
                'answer' => 'Bookings can be cancelled up to 24 hours before the scheduled time without penalty. Cancellations made less than 24 hours in advance may incur a penalty and could affect future booking privileges. No-shows are treated as cancellations and may result in temporary suspension of booking rights.'
            ],
            [
                'question' => 'Are there penalties for misuse of facilities?',
                'answer' => 'Yes, misuse of facilities including damage to equipment, excessive noise, unauthorized usage, or violation of facility policies may result in penalties including fines, suspension of booking privileges, or disciplinary action. All users are responsible for facility conditions during their usage.'
            ],
            [
                'question' => 'What should I do if I damage facility equipment?',
                'answer' => 'Immediately report any damage or malfunction to facilities management. Do not attempt to repair equipment yourself. You may be held responsible for repair costs depending on the circumstances. Having insurance or being covered under university insurance may help cover accidental damage.'
            ],
            [
                'question' => 'Can I bring outside equipment or caterers?',
                'answer' => 'Outside equipment and caterers require prior approval from facilities management. Some facilities have restrictions on external equipment due to safety and compatibility concerns. Catering requests must comply with university food service policies and health regulations.'
            ]
        ]
    ]
];
?>

<div class="row g-4">
    <!-- FAQ Search -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body-custom">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="faqSearch" class="form-label">Search FAQs</label>
                        <input type="text" class="form-control" id="faqSearch" placeholder="Type your question here...">
                    </div>
                    <div class="col-md-4">
                        <label for="categoryFilter" class="form-label">Category</label>
                        <select class="form-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            <option value="general">General Questions</option>
                            <option value="requests">Making Requests</option>
                            <option value="facilities">Facilities & Availability</option>
                            <option value="technical">Technical Support</option>
                            <option value="policies">Policies & Guidelines</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Categories -->
    <?php foreach ($faqCategories as $categoryKey => $category): ?>
    <div class="col-xl-6 faq-category" data-category="<?php echo $categoryKey; ?>">
        <div class="card dashboard-card">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <svg class="me-2" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <?php
                        $icon = $category['icon'];
                        switch ($icon) {
                            case 'info-circle':
                                echo '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm1.138-1.496a.75.75 0 1 0-.976 1.138l.75.75a.75.75 0 0 0 1.138-.976l-.75-.75zm2.226 0a.75.75 0 1 0-.976-1.138l-.75.75a.75.75 0 0 0 1.138.976l.75-.75z"/>';
                                break;
                            case 'clipboard-check':
                                echo '<path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/><path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>';
                                break;
                            case 'building':
                                echo '<path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/><path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1z"/>';
                                break;
                            case 'tools':
                                echo '<path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13.917V14a2 2 0 1 0 4 0v-.083a3.003 3.003 0 0 0-2.207-2.709l2.594-2.63.793.793a1 1 0 0 0 1.414 0l2.675-2.675a1 1 0 0 0 .293-.708v-.07a1 1 0 0 0-.419-.814L4 2.02 1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM8 3a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 0 1h-.5v.5a.5.5 0 0 1-1 0v-.5h-.5a.5.5 0 0 1 0-1h.5v-.5A.5.5 0 0 1 8 3z"/>';
                                break;
                            case 'file-text':
                                echo '<path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>';
                                break;
                        }
                        ?>
                    </svg>
                    <?php echo htmlspecialchars($category['title']); ?>
                </h5>
            </div>
            <div class="card-body-custom">
                <div class="accordion" id="faqAccordion<?php echo ucfirst($categoryKey); ?>">
                    <?php foreach ($category['faqs'] as $index => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $categoryKey . $index; ?>">
                                <?php echo htmlspecialchars($faq['question']); ?>
                            </button>
                        </h2>
                        <div id="faq<?php echo $categoryKey . $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion<?php echo ucfirst($categoryKey); ?>">
                            <div class="accordion-body">
                                <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Contact Support -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body-custom text-center">
                <h5 class="mb-3">Still need help?</h5>
                <p class="text-muted mb-4">Can't find the answer you're looking for? Our support team is here to help.</p>
                <div class="row g-3 justify-content-center">
                    <div class="col-md-4">
                        <div class="contact-option">
                            <svg class="mb-2" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a17.266 17.266 0 0 1-.617-1.146l.547-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                            </svg>
                            <h6>Phone Support</h6>
                            <p class="small text-muted">Call us during business hours</p>
                            <strong>(086) 826-8214</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-option">
                            <svg class="mb-2" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                            </svg>
                            <h6>Email Support</h6>
                            <p class="small text-muted">Get help via email</p>
                            <strong>support@snsu.edu.ph</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-option">
                            <svg class="mb-2" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm1.138-1.496a.75.75 0 1 0-.976 1.138l.75.75a.75.75 0 0 0 1.138-.976l-.75-.75zm2.226 0a.75.75 0 1 0-.976-1.138l-.75.75a.75.75 0 0 0 1.138.976l.75-.75z"/>
                            </svg>
                            <h6>Live Chat</h6>
                            <p class="small text-muted">Chat with us online</p>
                            <strong>Available 8 AM - 5 PM</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// FAQ search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqSearch = document.getElementById('faqSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const faqCategories = document.querySelectorAll('.faq-category');

    function filterFAQs() {
        const searchValue = faqSearch.value.toLowerCase();
        const categoryValue = categoryFilter.value;

        faqCategories.forEach(category => {
            const categoryName = category.dataset.category;
            const shouldShowCategory = !categoryValue || categoryName === categoryValue;

            if (shouldShowCategory) {
                category.style.display = '';

                // If there's a search term, filter individual FAQs
                if (searchValue) {
                    const accordionItems = category.querySelectorAll('.accordion-item');
                    let hasVisibleItems = false;

                    accordionItems.forEach(item => {
                        const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                        const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
                        const isMatch = question.includes(searchValue) || answer.includes(searchValue);

                        item.style.display = isMatch ? '' : 'none';
                        if (isMatch) hasVisibleItems = true;
                    });

                    // Hide category if no items match
                    if (!hasVisibleItems) {
                        category.style.display = 'none';
                    }
                } else {
                    // Show all items in category
                    const accordionItems = category.querySelectorAll('.accordion-item');
                    accordionItems.forEach(item => {
                        item.style.display = '';
                    });
                }
            } else {
                category.style.display = 'none';
            }
        });
    }

    faqSearch.addEventListener('input', filterFAQs);
    categoryFilter.addEventListener('change', filterFAQs);

    // Highlight search terms
    function highlightSearchTerms() {
        const searchValue = faqSearch.value.toLowerCase();
        if (!searchValue) return;

        const accordionBodies = document.querySelectorAll('.accordion-body');
        accordionBodies.forEach(body => {
            const text = body.textContent;
            const highlightedText = text.replace(new RegExp(searchValue, 'gi'), match => `<mark>${match}</mark>`);
            body.innerHTML = highlightedText;
        });
    }

    faqSearch.addEventListener('input', function() {
        setTimeout(highlightSearchTerms, 300);
    });
});
</script>