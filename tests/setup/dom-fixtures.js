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

module.exports = {
  createProfileDom,
  createFaqDom,
};
