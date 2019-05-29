class Tabber {
  constructor({ list, tabs }) {
    this.list = list;
    this.tabs = tabs;
  }

  register() {
    for (let i = 0; i < this.tabs.length; i++) {
      const tab = this.tabs[i];
      tab.addEventListener("click", e => {
        const pane = e.target.dataset.target;
        const currentTab = this.list.querySelector(".js-is-active");
        const currentPane = document.querySelector(
          ".pane-list__item.js-is-active"
        );

        currentTab.classList.remove("js-is-active");
        currentPane.classList.remove("js-is-active");
        document.getElementById(pane).classList.add("js-is-active");
        e.target.classList.add("js-is-active");
      });
    }
  }
}

export default Tabber;
