import { styled } from "./deps/helpers";
import donut from "./templates/donut";

function getFormData(form) {
  if (!form) {
    return null;
  }

  const formData = new FormData(form);
  return formData;
}

function submitForm(e) {
  e.preventDefault();
  const formData = getFormData(this);
  const formButton = this.querySelector("button[type='submit']");
  const originalButtonText = formButton.innerHTML;

  formButton.style = styled({
    transition: "all 200ms",
    "transform-origin": "center",
    "border-radius": "50%",
    padding: "1em"
  });

  formButton.innerHTML = donut({
    border: "4px solid rgba(255, 255, 255, 0.3)",
    "border-left-color": "#fff",
    height: "18px",
    width: "18px"
  });

  fetch(`/wp-json/wcwpdp/v1/duplicatePost/settings`, {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      formButton.innerHTML = originalButtonText;
      formButton.style = styled({
        "border-radius": "4px",
        padding: "0.75em 1em"
      });
    })
    .catch(e => console.error(e));
}

function getElement(selector) {
  if (!selector) {
    return null;
  }

  return document.querySelector(selector);
}

function go() {
  const form = getElement("#form-duplicate-post-settings");
  if (form) {
    form.addEventListener("submit", submitForm);
  }
}

go();
