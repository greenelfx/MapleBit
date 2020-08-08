import React from "react";
import { withRouter } from "react-router-dom";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Button from 'react-bootstrap/Button';
import { useAuth } from '../../context/auth-context'
import { NavLink } from 'react-router-dom';

function OptionPanel(props) {
  return (
    <Card className="h-100">
      <Card.Header>{props.title}</Card.Header>
      <Card.Body className="d-flex align-items-start flex-column">
        <Card.Text>{props.text}</Card.Text>
        <div className="mt-auto ml-auto"><NavLink to={props.link}><Button variant="outline-primary">{props.linkText}</Button></NavLink></div>
      </Card.Body>
    </Card>
  )
}

function UserControlPanelHome() {
  const { user } = useAuth();
  return (
    <>
      <h3>Welcome back, {user.name}</h3>
      <hr/>
      <Row>
        <Col md="4">
          <OptionPanel title="Account Settings" text="Manage your password and other account information." link="/user/account-settings" linkText="Manage" />
        </Col>
        <Col md="4">
          <OptionPanel title="Account Utilities" text="Apply utilities to your game account, such as force logout and character moving." link="/user/account-utilities" linkText="Manage" />
        </Col>
        <Col md="4">
          <OptionPanel title="Public Profile" text="Your public profile is what other users see about you. You can enter in details about yourself here." link="/user/profile-management" linkText="Manage" />
        </Col>
      </Row>
      <Row className="mt-4">
        <Col md="4">
          <OptionPanel title="Gravatar" text="Your gravatar is the image other users see associated with your profile. After clicking the link below, please sign up or login using the same email address you used to sign up for your account here" link="#" linkText="Manage" />
        </Col>
        <Col md="4">
          <OptionPanel title="Characters" text="View characters associated with your account." link="#" linkText="View" />
        </Col>
        <Col md="4">
          <OptionPanel title="Community" text="View other public profiles in your game server." link="#" linkText="View" />
        </Col>        
      </Row>
    </>
  );
}

export default withRouter(UserControlPanelHome);