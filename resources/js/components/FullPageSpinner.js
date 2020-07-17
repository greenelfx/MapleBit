import React from 'react';
import Spinner from 'react-bootstrap/Spinner';
import Container from 'react-bootstrap/Container';

function FullPageSpinner() {
    return (
        <div className="vertical-center">
            <Container className="text-center">
                <Spinner animation="border" role="status">
                    <span className="sr-only">Loading...</span>
                </Spinner>
            </Container>
        </div>
    )
}

export default FullPageSpinner;