function createProfileDom() {
  document.body.innerHTML = `
    <input id="name" />
    <input id="studentId" />
    <input id="course" />
    <input id="year" />
    <input id="email" />
    <input id="phone" />
    <img id="profilePhoto" data-src="" src="" alt="Profile photo" />
    <span id="cardName"></span>
    <span id="cardId"></span>
    <span id="cardCourse"></span>
    <span id="cardYear"></span>
    <span id="cardEmail"></span>
    <span id="cardPhone"></span>
    <span id="userGreeting"></span>
    <div id="sidebarProfileName"></div>
    <div id="sidebarProfileMeta"></div>
    <img id="sidebarProfilePhoto" data-src="" src="" alt="Sidebar profile photo" />
    <span id="studentProfileName"></span>
    <span id="studentProfileId"></span>
    <span id="studentProfileCourse"></span>
    <span id="studentProfileYear"></span>
    <span id="studentProfileEmail"></span>
    <span id="studentProfilePhone"></span>
  `;
}

function createFaqDom() {
  document.body.innerHTML = `
    <div class="faq-item">
      <button class="faq-question" type="button" aria-expanded="false">Question 1</button>
      <div class="faq-answer">Answer 1</div>
    </div>
    <div class="faq-item">
      <button class="faq-question" type="button" aria-expanded="false">Question 2</button>
      <div class="faq-answer">Answer 2</div>
    </div>
  `;

  document.querySelectorAll('.faq-answer').forEach((answer, index) => {
    Object.defineProperty(answer, 'scrollHeight', {
      configurable: true,
      value: 120 + index,
    });
  });
}

function createRequestDom() {
  document.body.innerHTML = `
    <input id="requestTitle" />
    <input id="requestLocation" />
    <select id="requestType">
      <option value="Electrical">Electrical</option>
      <option value="Plumbing">Plumbing</option>
      <option value="HVAC">HVAC</option>
      <option value="Structural">Structural</option>
      <option value="Other">Other</option>
    </select>
    <textarea id="requestDescription"></textarea>
    <input id="requestDate" type="date" />
    <input id="requestDue" type="date" />
    <select id="requestPriority">
      <option value="Low">Low</option>
      <option value="Medium">Medium</option>
      <option value="High">High</option>
    </select>
    <input id="requestFilter" />
    <span id="taskCount"></span>
    <ul id="taskList"></ul>
  `;
}

module.exports = {
  createProfileDom,
  createFaqDom,
  createRequestDom,
};
