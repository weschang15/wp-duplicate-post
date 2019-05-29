const modal = ({ image, title, currentPage, privpol, endpoint } = {}) => {
  return `
    <div id="newsletter-modal" class="modal">
      <div class="modal-wrap">
        <div class="modal-content">
          <span class="modal-close"></span>
          <div class="modal-splash" style="background-image:url(${image});"></div>
          <form action="" method="POST" id="modal-form" class="modal-form" novalidate="novalidate" data-endpoint="${endpoint}">
            <h2 class="modal-form__title">${title}</h2>
            <div class="modal-form-fields">
              <div class="modal-form__field">
                <label class="u-display--hidden" for="fname">First Name</label>
                <input class="form__input modal-form__input" id="fname" type="text" name="fname" value="" placeholder="First name" required />
              </div>
              <div class="modal-form__field">
                <label class="u-display--hidden" for="lname">Last Name</label>
                <input class="form__input modal-form__input" id="lname" type="text" name="lname" value="" placeholder="Last name (optional)" />
              </div>
            </div>
            <div class="form-field modal-form__field">
              <label class="u-display--hidden" for="email">Email</label>
              <input class="form__input modal-form__input" id="email" type="email" name="email" value="" placeholder="example@gmail.com" required />
            </div>
            <div class="form-field modal-form__field">
              <input class="modal-form__input" id="privacy-policy" type="checkbox" name="privacy_policy_opt_in" value="0" required />
              <div class="state">
                <label for="privacy-policy">By clicking this box and submitting this form you agree to our <a href="${privpol}">privacy policy</a>.</label>
              </div>
            </div>
            <div class="form-field modal-form__field">
              <input class="modal-form__input" id="comms-opt-in" type="checkbox" name="comms_opt_in_checkbox" value="0" />
              <div class="state">
                <label for="comms-opt-in">Yes, please keep me informed of events, news and other updates.</label>
              </div>
            </div>
            <div>
              <input type="hidden" value="${currentPage}" name="current_page" />
            </div>
            <div class="form-field modal-form__field">
              <button class="button button--primary button--red button--medium modal-form__button">Subscribe Now</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  `;
};

export default modal;
