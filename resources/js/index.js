import React from "react";
import ReactDOM from "react-dom";
import "bootstrap/dist/css/bootstrap.css";
import App from "./components/App";
import { AppProviders } from './context'

ReactDOM.render(<AppProviders><App /></AppProviders>, document.getElementById("root"));