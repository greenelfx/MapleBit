import React, { useState } from "react";

import { useAuth, useClient } from '../../context/auth-context'
import Form from 'react-bootstrap/Form';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';
import { errorsToString } from '../../utils/utils';

function ProfileManagement() {
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

        const { name, age, country, motto, about } = event.target.elements
        client('user/profile/store', {
            method: 'POST',
            data: {
                name: name.value,
                age: age.value,
                country: country.value,
                motto: motto.value,
                about: about.value,
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
            <h2 className="text-left">Profile Management</h2>
            <hr />
            <Form onSubmit={handleSubmit}>
                {isSuccess && <Alert variant="success">Your profile has been updated.</Alert>}
                {isError && <Alert variant="danger">{errorsToString(data.errors)}</Alert>}
                <Form.Group controlId="inputProfileName">
                    <Form.Label>Profile Name</Form.Label>
                    <Form.Control name="name" type="text" placeholder="Profile Name" defaultValue={user.profile?.name} disabled={isLoading} />
                </Form.Group>
                <Form.Group controlId="inputAge">
                    <Form.Label>Age</Form.Label>
                    <Form.Control name="age" type="text" placeholder="Age" defaultValue={user.profile?.age} disabled={isLoading} />
                </Form.Group>
                <Form.Group controlId="inputCountry">
                    <Form.Label>Country</Form.Label>
                    <Form.Control name="country" type="text" placeholder="Country" defaultValue={user.profile?.country} disabled={isLoading} />
                </Form.Group>
                <Form.Group controlId="inputMotto">
                    <Form.Label>Motto</Form.Label>
                    <Form.Control name="motto" type="text" placeholder="Motto" defaultValue={user.profile?.motto} disabled={isLoading} />
                </Form.Group>
                <Form.Group controlId="inputAbout">
                    <Form.Label>About</Form.Label>
                    <Form.Control name="about" as="textarea" placeholder="Some text about yourself" defaultValue={user.profile?.about} disabled={isLoading} />
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

export default ProfileManagement;