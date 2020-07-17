import React from "react";
import { Switch, Route } from "react-router-dom";
import Home from "./Home";
import Register from "./Register";
import AccountUtilities from "./AccountUtilities";

const Main = () => (
    <main>
      <Switch>
        <Route exact path="/" component={Home} />
        <Route path="/register" component={Register} />
        <Route path="/user/account-utilities" component={AccountUtilities} />
      </Switch>
    </main>
  );
  
  export default Main;