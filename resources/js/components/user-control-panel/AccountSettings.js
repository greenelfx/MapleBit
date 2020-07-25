import React, { useState } from "react";

import { useAuth, useClient } from '../../context/auth-context'
import Form from 'react-bootstrap/Form';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import { errorsToString } from '../../utils/utils';

function AccountSettings() {
    const { user } = useAuth();
    const client = useClient();
    const [isLoading, setIsLoading] = useState(false);
    const [isError, setIsError] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);
    const [data, setData] = useState(null);

    const handleSubmit = async (event) => {
        event.preventDefault();
        setIsLoading(true);
        setIsError(false);
        setIsSuccess(false);

        const { password, new_password, new_verify_password } = event.target.elements
        client('user/update', {
            method: 'POST',
            data: {
                password: password.value,
                new_password: new_password.value,
                new_verify_password: new_verify_password.value,
            }
        }).then(data => {
            setIsSuccess(true);
        }).catch(err => {
            setData(err);
            setIsError(true);
        }).finally(() => {
            setIsLoading(false);
        })
    }

    return (
        <>
            <h2 className="text-left">Account Settings</h2>
            <hr />
            <Form onSubmit={handleSubmit}>
                {isSuccess && <Alert variant="success">Your account has been updated.</Alert>}
                {isError && <Alert variant="danger">{errorsToString(data.errors)}</Alert>}
                <b><abbr title="You can't change this!">Username</abbr> {user.name}</b>
                <Form.Group controlId="inputCurrentPassword">
                    <Form.Label>Current Password</Form.Label>
                    <Form.Control name="password" type="password" placeholder="Current Password" disabled={isLoading} />
                </Form.Group>
                <Form.Group controlId="inputNewPassword">
                    <Form.Label>New Password</Form.Label>
                    <Form.Control name="new_password" type="password" placeholder="New Password" disabled={isLoading} />
                </Form.Group>
                <Form.Group controlId="verifyNewPassword">
                    <Form.Label>Verify New Password</Form.Label>
                    <Form.Control name="new_verify_password" type="password" placeholder="Verify New Password" disabled={isLoading} />
                </Form.Group>
                <Button variant="primary" type="submit" size="sm" disabled={isLoading}>
                    {isLoading ? (
                        <Spinner
                            as="span"
                            animation="border"
                            size="sm"
                            role="status"
                            aria-hidden="true"
                        />
                    ) : "Submit"}
                </Button>
            </Form>
        </>
    )
}

export default AccountSettings;