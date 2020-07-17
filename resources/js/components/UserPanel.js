import React, { Component } from "react";
import Form from 'react-bootstrap/Form';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import ListGroup from 'react-bootstrap/ListGroup';
import Card from 'react-bootstrap/Card';
import { useAuth } from '../context/auth-context'
import { useAsync } from '../utils/hooks';
import { errorsToString } from '../utils/utils';

function UserPanel() {
  const { isLoading, isError, error, run } = useAsync()
  const { user, login, logout } = useAuth();

  const handleSubmit = (event) => {
    event.preventDefault();
    const { email, password } = event.target.elements
    run(
      login({
        email: email.value,
        password: password.value,
      }),
    )
  }


  return (
    <>
      {user ? (
        <ListGroup variant="flush">
          <ListGroup.Item><a href="#" onClick={logout}>Logout</a></ListGroup.Item>
        </ListGroup>
      ) :
        <Card.Body>
          <Form onSubmit={handleSubmit}>
            {isError && <Alert variant="danger">{errorsToString(error.errors)}</Alert>}
            <Form.Group controlId="formBasicEmail">
              <Form.Control name="email" type="email" placeholder="Enter email" disabled={isLoading} />
            </Form.Group>
            <Form.Group controlId="formBasicPassword">
              <Form.Control name="password" type="password" placeholder="Password" disabled={isLoading} />
            </Form.Group>
            <Button variant="primary" type="submit" block size="sm" disabled={isLoading}>
              {isLoading ? (
                <Spinner
                  as="span"
                  animation="border"
                  size="sm"
                  role="status"
                  aria-hidden="true"
                />
              ) : "Login"}
            </Button>
          </Form>
        </Card.Body>
      }
    </>
  );
}
export default UserPanel;