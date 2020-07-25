import React, { useState } from "react";

import { useClient } from '../../context/auth-context'
import { useAsync } from '../../utils/hooks';
import Form from 'react-bootstrap/Form';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Alert from 'react-bootstrap/Alert';

function AccountUtilities() {
    const client = useClient();
    const [isLoading, setIsLoading] = useState(false);
    const [data, setData] = useState(null);

    const handleDisconnect = (event) => {
        event.preventDefault();
        setIsLoading(true);
        client('user/disconnect', {
            method: 'POST',
        }).then(data => {
            setData(data);
            setIsLoading(false);
        })
    }

    return (
        <>
            <h2 className="text-left">Account Utilities</h2>
            <hr />
            <h4>Disconnect your Account</h4>
            {data && <Alert variant={data.status}>{data.message}</Alert>}
            <Form onSubmit={handleDisconnect}>
                <Button variant="primary" type="submit" size="sm" disabled={isLoading}>
                    {isLoading ? (
                        <Spinner
                            as="span"
                            animation="border"
                            size="sm"
                            role="status"
                            aria-hidden="true"
                        />
                    ) : "Disconnect"}
                </Button>
            </Form>
        </>
    )
}

export default AccountUtilities;