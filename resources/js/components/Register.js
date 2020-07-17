import React from "react";
import { withRouter } from "react-router-dom";
import Form from 'react-bootstrap/Form';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import { useAuth } from '../context/auth-context'
import { useAsync } from '../utils/hooks';
import { errorsToString } from '../utils/utils';
import ReCAPTCHA from "react-google-recaptcha";

function Register() {
  const recaptchaRef = React.createRef();
  const { isLoading, isError, error, run } = useAsync()
  const { register } = useAuth();

  const handleSubmit = async (event) => {
    event.preventDefault();
    event.persist()
    const token = await recaptchaRef.current.executeAsync();
    const { username, password, email, password_confirm } = event.target.elements
    run(
      register({
        username: username.value,
        password: password.value,
        password_confirm: password_confirm.value,
        email: email.value,
      }),
    )
  }

  return (
    <>
      <h2 className="text-left">Registration</h2>
      <hr />
      <Form onSubmit={handleSubmit}>
        <ReCAPTCHA
          ref={recaptchaRef}
          size="invisible"
          // todo use props in config?
          sitekey={process.env.MIX_RECAPTCHA_SITE_KEY}
          size="invisible"
        />
        {isError && <Alert variant="danger">{errorsToString(error.errors)}</Alert>}
        <Form.Group controlId="registerFormUsername">
          <Form.Label>Username</Form.Label>
          <Form.Control name="username" type="text" placeholder="Username" disabled={isLoading} />
        </Form.Group>
        <Form.Group controlId="registerFormPassword">
          <Form.Label>Password</Form.Label>
          <Form.Control name="password" type="password" placeholder="Password" disabled={isLoading} />
        </Form.Group>
        <Form.Group controlId="registerFormPasswordConfirm">
          <Form.Label>Confirm Password</Form.Label>
          <Form.Control name="password_confirm" type="password" placeholder="Confirm Password" disabled={isLoading} />
        </Form.Group>
        <Form.Group controlId="registerFormEmail">
          <Form.Label>Email</Form.Label>
          <Form.Control name="email" type="email" placeholder="Email" disabled={isLoading} />
        </Form.Group>
        <hr />
        <Button variant="primary" type="submit" size="md" disabled={isLoading}>
          {isLoading ? (
            <Spinner
              as="span"
              animation="border"
              size="sm"
              role="status"
              aria-hidden="true"
            />
          ) : "Register"}
        </Button>
      </Form>
    </>
  );
}

export default withRouter(Register);