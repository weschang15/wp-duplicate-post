const alert = ({ isAlert, message, cb }) => {
  return `
    <div class="wip ${isAlert ? "wip--alert" : ""}">
      <div class="wip-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path d="M10 20C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10zm0-2c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8zm-.5-5h1c.276 0 .5.224.5.5v1c0 .276-.224.5-.5.5h-1c-.276 0-.5-.224-.5-.5v-1c0-.276.224-.5.5-.5zm0-8h1c.276 0 .5.224.5.5V8l-.5 3-1 .5L9 8V5.5c0-.276.224-.5.5-.5z"></path></svg></div>
      <div class="wip-context">
        <p><strong>${message}</strong></p>
        ${cb()}
      </div>
    </div>
  `;
};

export default alert;
