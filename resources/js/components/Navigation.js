import React from 'react';
import {
    Navbar,
    Nav,
    NavDropdown,
} from 'react-bootstrap';
import { NavLink } from 'react-router-dom';
import { useAuth } from '../context/auth-context'

function Navigation() {
    const { user, logout } = useAuth();

    return (
        <Navbar bg="dark" expand="lg" variant="dark">
            <Navbar.Brand href="#home">title</Navbar.Brand>
            <Navbar.Toggle aria-controls="basic-navbar-nav" />
            <Navbar.Collapse id="basic-navbar-nav">
                <Nav className="mr-auto">
                    <Nav.Link as={NavLink} to='/' exact>Home</Nav.Link>
                    {!user && <Nav.Link as={NavLink} to='/register' exact>Register</Nav.Link>}
                    <Nav.Link as={NavLink} to='/download' exact>Download</Nav.Link>
                    <Nav.Link as={NavLink} to='/rankings' exact>Rankings</Nav.Link>
                    <Nav.Link as={NavLink} to='/vote' exact>Vote</Nav.Link>
                </Nav>
                {user &&
                (
                    <Nav>
                        <NavDropdown title={<span><img src={user.gravatar_url} alt="gravatar" className="img-fluid rounded-circle nav-avatar"/>{user.name}</span>} id="basic-nav-dropdown">
                            <NavDropdown.Item as={NavLink} to="/user/home" exact>Control Panel</NavDropdown.Item>
                            <NavDropdown.Divider />
                            <NavDropdown.Item href="#" onClick={logout}>Logout</NavDropdown.Item>
                        </NavDropdown>
                    </Nav>
                )}
            </Navbar.Collapse>
        </Navbar>
    );
}

export default Navigation;