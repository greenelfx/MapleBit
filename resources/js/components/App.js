import React from 'react';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Navigation from "./Navigation";
import Sidebar from "./Sidebar";
import Main from "./Main";
import './App.css';

const App = () => (
  <div id="app">
    <Container>
      <Navigation />
      <Card>
        <Card.Body>
          <Container>
            <Row>
              <Col md="3">
                <Sidebar />
              </Col>
              <Col md="9">
                <Main />
              </Col>
            </Row>
          </Container>
        </Card.Body>
      </Card>
    </Container>
    <Container className="mt-4">
      <div className="text-center">Proudly powered by MapleBit | <a href="http://forum.ragezone.com/members/1333360872.html">greenelf(x) »</a></div>
    </Container>
  </div>
);

export default App;
