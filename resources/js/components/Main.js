import React from "react";
import { Switch, Route } from "react-router-dom";
import Home from "./Home";
import Register from "./Register";
import AccountUtilities from "./user-control-panel/AccountUtilities";
import UserControlPanelHome from "./user-control-panel/UserControlPanelHome";
import AccountSettings from "./user-control-panel/AccountSettings";
import ProfileManagement from "./user-control-panel/ProfileManagement";

const Main = () => (
    <main>
      <Switch>
        <Route exact path="/" component={Home} />
        <Route path="/register" component={Register} />
        <Route path="/user/account-utilities" component={AccountUtilities} />
        <Route path="/user/home" component={UserControlPanelHome} />
        <Route path="/user/account-settings" component={AccountSettings} />
        <Route path="/user/profile-management" component={ProfileManagement} />
      </Switch>
    </main>
  );
  
  export default Main;