import Tabber from "./components/Tabber";

const list = document.querySelector(".tabs-list");
const tabs = document.querySelectorAll(".tabs-list__item");

const tabber = new Tabber({ list, tabs });
tabber.register();
