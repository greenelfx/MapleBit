import React, { Component } from "react";
import { withRouter } from "react-router-dom";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';

export class Home extends Component {
  render() {
    return (
      <>
        <Row>
          <Col md="6">
            <h5><a href="#">News &raquo;</a></h5>
            <hr />
          </Col>
          <Col md="6">
            <h5><a href="#">Events &raquo;</a></h5>
            <hr />
          </Col>
        </Row>
        <Row className="mt-2">
          <Col md="6">
            <h5><a href="#">Rankings &raquo;</a></h5>
            <hr />
          </Col>
          <Col md="6">
            <h5><a href="#">Blogs &raquo;</a></h5>
            <hr />
          </Col>
        </Row>
      </>
    );
  }
}

export default withRouter(Home);